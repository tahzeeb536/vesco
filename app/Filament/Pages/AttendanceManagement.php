<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\Action;

class AttendanceManagement extends Page implements HasTable
{
    use InteractsWithTable, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Daily Attendance';    
    protected static ?string $navigationGroup = 'Employees';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.attendance-management';

    public $selectedEmployeeId;
    public $selectedDate;
    public $attendances = [];


    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $this->attendances = Employee::where('status', 1)
            ->with(['attendance' => function ($query) {
                $query->where('date', $this->selectedDate);
            }])
            ->get()
            ->map(function ($employee) {
                $attendance = $employee->attendance->first();

                return [
                    'employee_id' => $employee->id,
                    'name' => $employee->name,
                    'status' => $attendance->status ?? 'Present',
                    'clock_in' => $attendance->clock_in ?? '08:00',
                    'clock_out' => $attendance->clock_out ?? '17:00',
                    'hours_worked' => $attendance->hours_worked ?? 8,
                    'minutes_worked' => $attendance->minutes_worked ?? 0,
                    'overtime_hours' => $attendance->overtime_hours ?? 0,
                    'overtime_minutes' => $attendance->overtime_minutes ?? 0,
                    'saved' => $attendance ? true : false, // Track if already saved
                ];
            })->toArray();
    }

    public function saveAllAttendance()
    {
        foreach ($this->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $attendanceData['employee_id'],
                    'date' => $this->selectedDate,
                ],
                [
                    'status' => $attendanceData['status'],
                    'clock_in' => $attendanceData['clock_in'],
                    'clock_out' => $attendanceData['clock_out'],
                    'hours_worked' => $attendanceData['hours_worked'],
                    'minutes_worked' => $attendanceData['minutes_worked'],
                    'overtime_hours' => $attendanceData['overtime_hours'],
                    'overtime_minutes' => $attendanceData['overtime_minutes'],
                ]
            );
        }

        Notification::make()
            ->title('Attendance Saved for All Employees')
            ->success()
            ->send();
    }


    protected function table(Table $table): Table
    {
        return $table->query(
            Employee::query()->where('status', 1)->with(['attendance' => function ($query) {
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
                        'Absent' => 'danger',
                        'Leave' => 'warning',
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

    public function updatedAttendances($value, $key)
    {
        if (preg_match('/^(\d+)\.(clock_in|clock_out)$/', $key, $matches)) {
            $index = (int)$matches[1];
            $this->calculateWorkingTime($index);
        }
    }


    public function calculateWorkingTime($index)
    {
        $attendance = $this->attendances[$index];

        $clockIn = $attendance['clock_in'] ?? null;
        $clockOut = $attendance['clock_out'] ?? null;

        if (!$clockIn || !$clockOut) {
            return;
        }

        try {
            $in = Carbon::createFromFormat('H:i', $clockIn);
            $out = Carbon::createFromFormat('H:i', $clockOut);

            // Ensure checkout is after checkin
            if ($out->lessThanOrEqualTo($in)) {
                $this->attendances[$index]['hours_worked'] = 0;
                $this->attendances[$index]['minutes_worked'] = 0;
                $this->attendances[$index]['overtime_hours'] = 0;
                $this->attendances[$index]['overtime_minutes'] = 0;
                return;
            }

            // Total working minutes minus 60-minute break
            $totalMinutes = $in->diffInMinutes($out) - 60;
            $totalMinutes = max(0, $totalMinutes); // Don't allow negative

            // Regular work: up to 8 hours = 480 minutes
            $workMinutes = min(480, $totalMinutes);
            $overtimeMinutes = max(0, $totalMinutes - 480);

            $this->attendances[$index]['hours_worked'] = intdiv($workMinutes, 60);
            $this->attendances[$index]['minutes_worked'] = $workMinutes % 60;
            $this->attendances[$index]['overtime_hours'] = intdiv($overtimeMinutes, 60);
            $this->attendances[$index]['overtime_minutes'] = $overtimeMinutes % 60;
        } catch (\Exception $e) {
            // Handle invalid times gracefully
            $this->attendances[$index]['hours_worked'] = 0;
            $this->attendances[$index]['minutes_worked'] = 0;
            $this->attendances[$index]['overtime_hours'] = 0;
            $this->attendances[$index]['overtime_minutes'] = 0;
        }
    }

}
