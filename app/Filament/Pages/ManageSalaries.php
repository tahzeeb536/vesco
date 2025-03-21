<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\Attendance;
use App\Models\AdvanceSalaryBalance;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;

class ManageSalaries extends Page implements HasTable
{
    use InteractsWithTable, InteractsWithForms, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Employees';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Salaries';
    protected static string $view = 'filament.pages.manage-salaries';

    public $month;
    public $year;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('date')
                ->label('Select Month and Year')
                ->form([
                    Forms\Components\Select::make('month')
                        ->options([
                            '01' => 'January',
                            '02' => 'February',
                            '03' => 'March',
                            '04' => 'April',
                            '05' => 'May',
                            '06' => 'June',
                            '07' => 'July',
                            '08' => 'August',
                            '09' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December',
                        ])
                        ->label('Month')
                        ->default(now()->format('m'))
                        ->required(),
                    Forms\Components\Select::make('year')
                        ->options(function () {
                            $currentYear = now()->year;
                            $years = [];
                            for ($i = $currentYear; $i >= $currentYear - 20; $i--) {
                                $years[$i] = $i;
                            }
                            return $years;
                        })
                        ->label('Year')
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->month = $data['month'] ?? now()->format('m');
                    $this->year = $data['year'] ?? now()->year;
                    $this->calculateSalaries();
                    Notification::make()
                        ->title('Salary Calculations Updated')
                        ->success()
                        ->send();
                }),
            // New Print All Salary Slips action
            Action::make('print_all')
                ->label('Print All Salary Slips')
                ->icon('heroicon-o-printer')
                ->url(fn () => route('print_all_salary', [
                    'month' => $this->month ?? now()->format('m'),
                    'year'  => $this->year ?? now()->year,
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function table(Table $table): Table
    {
        $selectedMonth = $this->month ?? now()->format('m');
        $selectedYear = $this->year ?? now()->year;

        return $table->query(
            Employee::query()->where('status', 1)->with(['salaries' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->where('month', $selectedMonth);
                $query->where('year', $selectedYear);
            }])
        )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('salaries.total_present_days')
                    ->label('Total Present Days')
                    ->default('-'),
                Tables\Columns\TextColumn::make('salaries.total_hours')
                    ->label('Hours'),
                Tables\Columns\TextColumn::make('salaries.total_minutes')
                    ->label('Minutes')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('salaries.total_overtime_hours')
                    ->label('Overtime Hours'),
                Tables\Columns\TextColumn::make('salaries.total_overtime_minutes')
                    ->label('Overtime Minutes')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('salaries.deduction')
                    ->label('Deductions')
                    ->default('-'),
                Tables\Columns\TextColumn::make('salaries.net_salary')
                    ->label('Net Salary')
                    ->default('-'),
            ])
            ->actions([
                // Tables\Actions\Action::make('view_salary_sheet')
                //     ->label('Print')
                //     ->icon('heroicon-o-printer')
                //     ->color('primary')
                //     ->url(fn (Employee $record) => route('print_salary', [
                //         'id'    => $record->id,
                //         'month' => $selectedMonth,
                //         'year'  => $selectedYear,
                //     ]))
                //     ->openUrlInNewTab(),
            ])
            ->description("Salaries for: " . Carbon::createFromDate($this->year, $this->month, 1)->format('F Y'))
            ->filters([])
            ->bulkActions([]);
    }

    public function calculateSalaries()
    {
        $employees = Employee::where('status', true)->get();
        // Calculate total expected hours for the month (8 hours per day)
        $currentMonthDays = Carbon::create($this->year, $this->month)->daysInMonth;
        $expectedWorkHours = $currentMonthDays * 8;
        $allowedLateHours = 32; // 4 leaves * 8 hours allowed without deduction

        foreach ($employees as $employee) {
            // Fetch attendance for the selected month and year
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();
            
            $presentDays = 0;
            $totalWorkedHours = 0;
            $totalWorkedMinutes = 0;
            $totalOvertimeHours = 0;
            $totalOvertimeMinutes = 0;
            $lateHours = 0;

            foreach ($attendances as $attendance) {
                if ($attendance->status === 'Present') {
                    $hoursWorked = $attendance->hours_worked ?? 0;
                    $minutesWorked = $attendance->minutes_worked ?? 0;
                    // Calculate worked time in hours (fractional)
                    $workedTime = $hoursWorked + ($minutesWorked / 60);
                    
                    if ($workedTime < 8) {
                        $lateHours += (8 - $workedTime);
                    }
                    
                    // Only count if there is a recorded working time
                    if ($hoursWorked > 0 || $minutesWorked > 0) {
                        $presentDays++;
                        $totalWorkedHours += $hoursWorked;
                        $totalWorkedMinutes += $minutesWorked;
                        // Normalize minutes to hours
                        if ($totalWorkedMinutes >= 60) {
                            $totalWorkedHours += floor($totalWorkedMinutes / 60);
                            $totalWorkedMinutes %= 60;
                        }
                    }
                    
                    $overtimeHours = $attendance->overtime_hours ?? 0;
                    $overtimeMinutes = $attendance->overtime_minutes ?? 0;
                    $totalOvertimeHours += $overtimeHours;
                    $totalOvertimeMinutes += $overtimeMinutes;
                    if ($totalOvertimeMinutes >= 60) {
                        $totalOvertimeHours += floor($totalOvertimeMinutes / 60);
                        $totalOvertimeMinutes %= 60;
                    }
                } else {
                    // If the employee is absent, count a full 8 hours as late hours.
                    // $lateHours += 8;
                }
            }
            
            // Total actual worked hours (including fractional hours)
            $actualWorkedHoursTotal = $totalWorkedHours + ($totalWorkedMinutes / 60);
            
            // Calculate the hourly rate using the expected hours for the month
            $hourlyRate = $employee->basic_salary / $expectedWorkHours;

            // Base salary is calculated based on actual hours worked
            $baseSalary = $actualWorkedHoursTotal * $hourlyRate;
            
            // Overtime calculation remains as before
            $overtimeDuration = 8 / 6;
            $overtimeHourlyRate = $hourlyRate * $overtimeDuration;
            $overtimePay = ($totalOvertimeHours + ($totalOvertimeMinutes / 60)) * $overtimeHourlyRate;

            // Process advance salary deduction as before
            $existingDeductionLog = DB::table('advance_salary_deductions')
                ->where('employee_id', $employee->id)
                ->whereMonth('return_date', $this->month)
                ->whereYear('return_date', $this->year)
                ->exists();
            
            $existingSalary = Salary::where('employee_id', $employee->id)
                ->where('month', $this->month)
                ->where('year', $this->year)
                ->first();

            $deductionAmount = 0;
            if (!$existingDeductionLog) {
                $advanceBalance = AdvanceSalaryBalance::where('employee_id', $employee->id)->first();
                if ($advanceBalance && $advanceBalance->remaining_amount > 0) {
                    $deductionAmount = min($advanceBalance->monthly_deduction, $advanceBalance->remaining_amount);
                    DB::table('advance_salary_deductions')->insert([
                        'employee_id' => $employee->id,
                        'amount' => $deductionAmount,
                        'return_date' => Carbon::create($this->year, $this->month, 1)->endOfMonth(),
                        'remarks' => 'Monthly advance salary deduction',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $advanceBalance->paid_amount += $deductionAmount;
                    $advanceBalance->remaining_amount -= $deductionAmount;
                    $advanceBalance->save();
                }
            } else {
                $deductionAmount = $existingSalary->deduction;
            }

            // Compute preliminary net salary (base + overtime)
            $netSalary = $baseSalary + $overtimePay;
            // Final net salary after subtracting any advance salary deductions and adding allowances
            $netSalary = $netSalary - $deductionAmount + $employee->home_allowance + $employee->medical_allowance + $employee->mobile_allowance;

            // Save the salary data including the new late_hours column
            Salary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'month' => $this->month,
                    'year' => $this->year,
                ],
                [
                    'total_present_days' => $presentDays,
                    'total_hours' => $totalWorkedHours,
                    'total_minutes' => $totalWorkedMinutes,
                    'total_overtime_hours' => $totalOvertimeHours,
                    'total_overtime_minutes' => $totalOvertimeMinutes,
                    'deduction' => $deductionAmount,
                    'net_salary' => $netSalary,
                    'late_hours' => $lateHours, // Saving calculated late hours
                    'home_allowance' => $employee->home_allowance,
                    'medical_allowance' => $employee->medical_allowance,
                    'mobile_allowance' => $employee->mobile_allowance,
                    // 'late_deduction' => $lateDeduction, // if you decide to use it later
                ]
            );
        }
    }


}
