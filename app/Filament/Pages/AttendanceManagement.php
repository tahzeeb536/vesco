<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\Action;

class AttendanceManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Daily Attendance';
    protected static string $view = 'filament.pages.attendance-management';

    public $selectedEmployeeId;
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('date')
                ->label('Select Date')
                ->form([
                    Forms\Components\DatePicker::make('attendance_date')
                        ->label('Date')
                        ->rules(['nullable', 'date'])
                        ->default(now()->format('Y-m-d')),
                ])
                ->action(function (array $data) {
                    $this->selectedDate = $data['attendance_date'] ?? now()->format('Y-m-d');
                }),
        ];
    }


    protected function table(Table $table): Table
    {
        return $table->query(
            Employee::query()->with(['attendance' => function ($query) {
                $query->where('date', $this->selectedDate);
            }])
        )
            ->description("Date: " . $this->selectedDate )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('attendance.0.clock_in')
                    ->label('Time In')
                    ->default('-')
                    ->formatStateUsing(fn($state) => $state && strtotime($state) ? Carbon::parse($state)->format('h:i A') : '-'),

                Tables\Columns\TextColumn::make('attendance.0.clock_out')
                    ->label('Time Out')
                    ->default('-')
                    ->formatStateUsing(fn($state) => $state && strtotime($state) ? Carbon::parse($state)->format('h:i A') : '-'),

                Tables\Columns\TextColumn::make('attendance.0.hours_worked')
                    ->label('Worked Time')
                    ->getStateUsing(fn($record) => 
                        ($record->attendance[0]->hours_worked ?? 0) 
                        . ':' . 
                        str_pad($record->attendance[0]->minutes_worked ?? 0, 2, '0', STR_PAD_LEFT)),

                Tables\Columns\TextColumn::make('attendance.0.overtime_hours')
                    ->label('Overtime')
                    ->getStateUsing(fn($record) => 
                        ($record->attendance[0]->overtime_hours ?? 0) 
                        . ':' . 
                        str_pad($record->attendance[0]->overtime_minutes ?? 0, 2, '0', STR_PAD_LEFT)),

                Tables\Columns\BadgeColumn::make('attendance.0.status')
                    ->label('Status')
                    ->color(fn($state) => match ($state) {
                        'Present' => 'success',
                        'Leave' => 'warning',
                        'Absent' => 'danger',
                        default => 'gray',
                    })
                    ->default('-'),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_attendance')
                    ->label('Update')
                    ->form(fn(Employee $record) => $this->getAttendanceForm($record))
                    ->action(function (Employee $record, array $data) {
                        $this->selectedEmployeeId = $record->id;
                        $this->saveAttendance($data);
                        Notification::make()
                            ->title('Attendance Updated Successfully')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    private function getAttendanceForm(Employee $record): array
    {
        $attendance = Attendance::where('employee_id', $record->id)
            ->where('date', $this->selectedDate)
            ->first();
    
        return [
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'Present' => 'Present',
                    'Leave' => 'Leave',
                    'Absent' => 'Absent',
                ])
                ->default('Present') // Default to Present
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state === 'Present') {
                        $set('clock_in', '08:00');
                        $set('clock_out', '17:00');
                        $set('hours_worked', 8);
                        $set('minutes_worked', 0);
                        $set('overtime_hours', 0);
                        $set('overtime_minutes', 0);
                    } else {
                        $set('clock_in', null);
                        $set('clock_out', null);
                        $set('hours_worked', null);
                        $set('minutes_worked', null);
                        $set('overtime_hours', null);
                        $set('overtime_minutes', null);
                    }
                }),
    
            Forms\Components\Group::make([
                Forms\Components\TimePicker::make('clock_in')
                    ->label('Time In')
                    ->default('08:00')
                    ->withoutSeconds()
                    ->nullable(),
    
                Forms\Components\TimePicker::make('clock_out')
                    ->label('Time Out')
                    ->default('17:00')
                    ->withoutSeconds()
                    ->nullable(),
            ])->columns(2),
    
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('hours_worked')
                    ->label('Hours Worked')
                    ->numeric()
                    ->default(8)
                    ->nullable()
                    ->columnSpan(3),
    
                Forms\Components\TextInput::make('minutes_worked')
                    ->label('Minutes Worked')
                    ->numeric()
                    ->default(0)
                    ->nullable()
                    ->columnSpan(3),

                Forms\Components\TextInput::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric()
                    ->default(0)
                    ->nullable()
                    ->columnSpan(3),
    
                Forms\Components\TextInput::make('overtime_minutes')
                    ->label('Overtime Minutes')
                    ->numeric()
                    ->default(0)
                    ->nullable()
                    ->columnSpan(3),
                    
            ])->columns(12),
    
        ];
    }
    


    public function saveAttendance(array $data)
    {
        $attendance = Attendance::where('employee_id', $this->selectedEmployeeId)
            ->where('date', $this->selectedDate)
            ->first();

        $hoursWorked = $data['hours_worked'] ?? 0;
        $minutesWorked = $data['minutes_worked'] ?? 0;
        $overtimeHours = $data['overtime_hours'] ?? 0;
        $overtimeMinutes = $data['overtime_minutes'] ?? 0;

        Attendance::updateOrCreate(
            [
                'employee_id' => $this->selectedEmployeeId,
                'date' => $this->selectedDate,
            ],
            [
                'status' => $data['status'],
                'clock_in' => $data['clock_in'],
                'clock_out' => $data['clock_out'],
                'hours_worked' => $hoursWorked,
                'minutes_worked' => $minutesWorked,
                'overtime_hours' => $overtimeHours,
                'overtime_minutes' => $overtimeMinutes,
            ]
        );

        $this->reset(['selectedEmployeeId']);
    }
}
