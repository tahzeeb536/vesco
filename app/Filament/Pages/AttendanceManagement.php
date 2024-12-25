<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Forms;
use Illuminate\Support\Facades\Validator;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Concerns\InteractsWithForms;

class AttendanceManagement extends Page implements HasTable
{
    use InteractsWithTable, InteractsWithForms, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Daily Attendance';
    // protected static ?string $navigationGroup = 'Manage Attendance';
    protected static string $view = 'filament.pages.attendance-management';

    public $selectedEmployeeId;
    public $selectedDate;

    // protected $listeners = ['refreshAttendanceTable' => '$refresh'];

    public function getTitle(): string | Htmlable
    {
        // return __('Daily Attendance (' . Carbon::parse($this->selectedDate)->format('j F, Y') . ')' );
        return __('Daily Attendance');
    }

    public function mount() {
        $this->selectedDate = now()->format('Y-m-d');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('date')
                ->label('Select Date')
                ->form([
                    Forms\Components\TextInput::make('attendance_date')
                        ->type('date')
                        ->rules('nullable|date_format:Y-m-d')
                        ->nullable()
                        ->default(now()->format('Y-m-d'))
                ])
                ->action(function (array $data) {
                    $this->selectedDate = $data['attendance_date'] ?? now()->format('Y-m-d');
                })
        ];
    }


    protected function table(Table $table): Table
    {
        return $table->query(
            Employee::query()->with(['attendance' => function($query) {
                $query->where('date', $this->selectedDate);    
            }]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_urdu')
                    ->label('Name Urdu')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('attendance.0.date')
                //     ->label('Date')
                //     ->default('-')
                //     ->formatStateUsing(function($state) {
                //         if ($state && strtotime($state)) {
                //             return Carbon::parse($state)->format('j M, Y');
                //         }
                //         return '-';
                //     }),
                Tables\Columns\TextColumn::make('attendance.0.clock_in')
                    ->label('Time In')
                    ->sortable()
                    ->default('-')
                    ->formatStateUsing(function ($state) {
                        if ($state && strtotime($state)) {
                            return Carbon::parse($state)->format('h:i A');
                        }
                        return '-';
                    }),
                Tables\Columns\TextColumn::make('attendance.0.clock_out')
                    ->label('Time Out')
                    ->sortable()
                    ->default('-')
                    ->formatStateUsing(function ($state) {
                        if ($state && strtotime($state)) {
                            return Carbon::parse($state)->format('h:i A');
                        }
                        return '-';
                    }),
                Tables\Columns\TextColumn::make('attendance.0.hours_worked')
                    ->label('Hours')
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('attendance.0.overtime_hours')
                    ->label('Overtime')
                    ->sortable()
                    ->default('-'),
                Tables\Columns\BadgeColumn::make('attendance.0.status')
                    ->label('Status')
                    ->color(function(string $state) : string {
                        return match ($state) {
                            'Present' => 'success',
                            'Leave' => 'warning',
                            'Absent' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->default('-'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('mark_attendance')
                    ->label('Update')
                    ->button()
                    ->outlined()
                    ->form(function(Employee $record) {
                            $attendance = Attendance::where('employee_id', $record->id)->where('date', $this->selectedDate)->first();
                            return 
                            [
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Present' => 'Present',
                                        'Absent' => 'Absent',
                                        'Leave' => 'Leave',
                                    ])
                                    ->rules('required|in:Present,Leave,Absent')
                                    ->default('Present')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state === 'Present') {
                                            $set('clock_in', '08:00');
                                            $set('clock_out', '17:00');
                                            $set('hours_worked', 8);
                                            $set('overtime_hours', 0);
                                        } else {
                                            $set('clock_in', null);
                                            $set('clock_out', null);
                                            $set('hours_worked', 0);
                                            $set('overtime_hours', 0);
                                        }
                                    }),
                                Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('clock_in')
                                        ->label('Time In')
                                        ->type('time')
                                        ->rules('nullable|date_format:H:i')
                                        ->nullable()
                                        ->default(isset($attendance->clock_in) ? Carbon::parse($attendance->clock_in)->format('H:i') : '08:00'),
                                    Forms\Components\TextInput::make('clock_out')
                                        ->label('Time Out')
                                        ->type('time')
                                        ->rules('nullable|date_format:H:i')
                                        ->nullable()
                                        ->default(isset($attendance->clock_out) ? Carbon::parse($attendance->clock_out)->format('H:i') : '17:00'),
                                    Forms\Components\TextInput::make('hours_worked')
                                        ->label('Hours Worked')
                                        ->numeric()
                                        ->rules('required|numeric')
                                        ->nullable()
                                        ->default(isset($attendance->hours_worked) ? Carbon::parse($attendance->hours_worked)->format('H:i') : '8.00'),
                                    Forms\Components\TextInput::make('overtime_hours')
                                        ->label('Overtime Hours')
                                        ->numeric()
                                        ->rules('nullable|numeric')
                                        ->nullable()
                                        ->default(isset($attendance->overtime_hours) ? Carbon::parse($attendance->overtime_hours)->format('H:i') : '0.00'),
                                ]),
                            ];
                        }
                    )
                    ->action(function (Employee $employee, array $data) {
                        $this->selectedEmployeeId = $employee->id;
                        $this->saveAttendance($data);
                        Notification::make()
                            ->title('Attendance Marked Successfully')
                            ->success()
                            ->send();           
                    })
            ])
            ->bulkActions([]);
    }

    public function saveAttendance($data)
    {
        // Check if today_attendance exists
        $attendance = Attendance::where('employee_id', $this->selectedEmployeeId)
            ->where('date', $this->selectedDate)
            ->first();

        if ($attendance) {
            $attendance->update([
                'clock_in' => ($data['status'] == 'Present') ? $data['clock_in'] : null,
                'clock_out' => ($data['status'] == 'Present') ? $data['clock_out'] : null,
                'hours_worked' => ($data['status'] == 'Present') ? $data['hours_worked'] : 0,
                'overtime_hours' => ($data['status'] == 'Present') ? $data['overtime_hours'] : 0,
                'status' => $data['status'],
            ]);
        } else {
            // Create a new attendance record
            Attendance::create([
                'employee_id' => $this->selectedEmployeeId,
                'date' => $this->selectedDate,
                'clock_in' => ($data['status'] == 'Present') ? $data['clock_in'] : null,
                'clock_out' => ($data['status'] == 'Present') ? $data['clock_out'] : null,
                'hours_worked' => ($data['status'] == 'Present') ? $data['hours_worked'] : null,
                'overtime_hours' => ($data['status'] == 'Present') ? $data['overtime_hours'] : null,
                'status' => $data['status'],
            ]);
        }

        // Reset form data
        $this->reset(['selectedEmployeeId']);

    }
    
}
