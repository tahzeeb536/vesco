<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\TempLoan;
use App\Models\Attendance;
use App\Models\AdvanceSalaryBalance;
use App\Models\EmployeeStatement;
use App\Models\AdvanceSalaryDeduction;
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
    public bool $salariesCalculated = false;

    public $month;
    public $year;

    protected function getHeaderActions(): array
    {
        $actions = [
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
                    $this->salariesCalculated = true;

                    Notification::make()
                        ->title('Salary Calculations Updated')
                        ->success()
                        ->send();
                }),

            Action::make('print_all')
                ->label('Print Salary Slips')
                ->url(fn () => route('print_all_salary', [
                    'month' => $this->month ?? now()->format('m'),
                    'year'  => $this->year ?? now()->year,
                ]))
                ->openUrlInNewTab(),
        ];

        // Conditionally add "Pay Salaries"
        if ($this->salariesCalculated && $this->month && $this->year) {
            $actions[] = Action::make('pay_salaries')
                ->label('Pay Salaries')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    Salary::where('month', $this->month)
                        ->where('year', $this->year)
                        ->update(['status' => 1]);

                    Notification::make()
                        ->title('Salaries marked as paid!')
                        ->success()
                        ->send();
                });
        }

        return $actions;
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
                    $workedTime = $hoursWorked + ($minutesWorked / 60);

                    if ($workedTime < 8) {
                        $lateHours += (8 - $workedTime);
                    }

                    if ($hoursWorked > 0 || $minutesWorked > 0) {
                        $presentDays++;
                        $totalWorkedHours += $hoursWorked;
                        $totalWorkedMinutes += $minutesWorked;
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
                }
            }
            
            // Total actual worked hours
            $actualWorkedHoursTotal = $totalWorkedHours + ($totalWorkedMinutes / 60);
            // Calculate hourly rate
            $hourlyRate = $employee->basic_salary / $expectedWorkHours;
            // Base salary
            $baseSalary = $actualWorkedHoursTotal * $hourlyRate;

            // Overtime calculations
            $overtimeDuration = 8 / 6;
            $overtimeHourlyRate = $hourlyRate * $overtimeDuration;
            $overtimePay = ($totalOvertimeHours + ($totalOvertimeMinutes / 60)) * $overtimeHourlyRate;

            // Check if salary already exists
            $existingSalary = Salary::where('employee_id', $employee->id)
                ->where('month', $this->month)
                ->where('year', $this->year)
                ->first();

            $transaction = null;
            if($existingSalary) {
                if($existingSalary->status == 1) {
                    $transaction = 'paid';
                }
                else {
                    $transaction = 'update';
                }
            }
            else {
                $transaction = 'create';
            }


            if($transaction == 'create') {
                
                // loan deduction
                $advanceBalance = AdvanceSalaryBalance::where('employee_id', $employee->id)->first();
                if ($advanceBalance) {

                    if($advanceBalance->remaining_amount > 0) {
                        $deductionAmount = min($advanceBalance->monthly_deduction, $advanceBalance->remaining_amount);
                    }
                    else {
                        $deductionAmount = 0;
                    }

                    // if already exists but records not match then we will update
                    AdvanceSalaryDeduction::create([
                        'employee_id' => $employee->id,
                        'amount' => $deductionAmount,
                        'return_date' => Carbon::today(),
                        'month' => $this->month,
                        'year' => $this->year,
                        'remarks' => 'Monthly advance salary deduction',    
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $advanceBalance->update([
                        'paid_amount' => $advanceBalance->paid_amount + $deductionAmount,
                        'remaining_amount'=> $advanceBalance->remaining_amount - $deductionAmount
                    ]);
                    
                    
                }
                else {
                    $deductionAmount = 0;
                }

                // temp loan
                $tempLoanTotal = TempLoan::where('employee_id', $employee->id)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->sum('amount');

                // Total deduction calculation
                $totalDeduction = $deductionAmount + $tempLoanTotal;

                // Compute net salary correctly
                $netSalary = ($baseSalary + $overtimePay) - $totalDeduction + $employee->home_allowance + $employee->medical_allowance + $employee->mobile_allowance;
                
                $salary = Salary::create([
                    'employee_id' => $employee->id,
                    'month' => $this->month,
                    'year' => $this->year,
                    'total_present_days' => $presentDays,
                    'total_hours' => $totalWorkedHours,
                    'total_minutes' => $totalWorkedMinutes,
                    'total_overtime_hours' => $totalOvertimeHours,
                    'total_overtime_minutes' => $totalOvertimeMinutes,
                    'basic_salary' => $baseSalary,
                    'overtime' => $overtimePay,
                    'deduction' => $totalDeduction,
                    'loan_deduction' => $deductionAmount,
                    'temp_deduction' => $tempLoanTotal,
                    'net_salary' => $netSalary,
                    'late_hours' => $lateHours,
                    'home_allowance' => $employee->home_allowance,
                    'medical_allowance' => $employee->medical_allowance,
                    'mobile_allowance' => $employee->mobile_allowance,
                ]);

                EmployeeStatement::create([
                    'employee_id' => $employee->id,
                    'datetime' => now(),
                    'details' => "Salary Deposit for Month {$this->year}-{$this->month}",
                    'deposit' => $salary->basic_salary + $salary->overtime + $salary->home_allowance + $salary->medical_allowance + $salary->mobile_allowance,
                    'withdraw' => 0,
                    'type' => 'SALARY_DEPOSIT',
                    'month' => $this->month,
                    'year' => $this->year,
                ]);

                if ($tempLoanTotal > 0) {
                    EmployeeStatement::create([
                        'employee_id' => $employee->id,
                        'datetime' => now(),
                        'details' => "Advance deduction for Month {$this->year}-{$this->month}",
                        'deposit' => 0,
                        'withdraw' => $tempLoanTotal,
                        'type' => 'ADV_DEDUCT',
                        'month' => $this->month,
                        'year' => $this->year,
                    ]);
                }

                EmployeeStatement::create([
                    'employee_id' => $employee->id,
                    'datetime' => now(),
                    'details' => "Salary Paid for month {$this->year}-{$this->month}",
                    'deposit' => 0,
                    'withdraw' => $netSalary,
                    'type' => 'SALARY_WITHDRAW',
                    'month' => $this->month,
                    'year' => $this->year,
                ]);

            }
            elseif ($transaction == 'update') {

                $advanceBalance = AdvanceSalaryBalance::where('employee_id', $employee->id)->first();
            
                if ($advanceBalance) {
                    // Get the expected monthly deduction from the balance record
                    $monthlyDeduction = $advanceBalance->monthly_deduction ?? 0;
            
                    // Get the single deduction record for the current month and year
                    $loanDeduction = AdvanceSalaryDeduction::where('employee_id', $employee->id)
                        ->where('month', $this->month)
                        ->where('year', $this->year)
                        ->first();
            
                    if ($loanDeduction) {
                        // If a record exists, check if its amount matches the monthly deduction
                        $currentDeduction = $loanDeduction->amount;
                        if ($currentDeduction != $monthlyDeduction) {
                            $difference = $monthlyDeduction - $currentDeduction;
                            
                            // Update the deduction record with the expected monthly deduction
                            $loanDeduction->update([
                                'amount'      => $monthlyDeduction,
                                'return_date' => Carbon::today(),
                            ]);
            
                            // Update the balance accordingly:
                            // Increase paid_amount if the new deduction is higher; or decrease if lower.
                            $advanceBalance->update([
                                'paid_amount'     => $advanceBalance->paid_amount + $difference,
                                'remaining_amount'=> $advanceBalance->remaining_amount - $difference,
                            ]);
                        }
                        // Set deduction amount to be used in salary calculation
                        $deductionAmount = $monthlyDeduction;
                    } else {
                        // If no record exists, create one with the monthly deduction amount
                        AdvanceSalaryDeduction::create([
                            'employee_id' => $employee->id,
                            'amount'      => $monthlyDeduction,
                            'return_date' => Carbon::today(),
                            'month'       => $this->month,
                            'year'        => $this->year,
                            'remarks'     => 'Monthly advance salary deduction',
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]);
            
                        // Update the balance with the full monthly deduction amount
                        $advanceBalance->update([
                            'paid_amount'     => $advanceBalance->paid_amount + $monthlyDeduction,
                            'remaining_amount'=> $advanceBalance->remaining_amount - $monthlyDeduction,
                        ]);
                        $deductionAmount = $monthlyDeduction;
                    }
                } else {
                    $deductionAmount = 0;
                }
            
                // Handle temporary loan deductions as before
                $tempLoanTotal = TempLoan::where('employee_id', $employee->id)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->sum('amount');
            
                // Calculate the total deduction
                $totalDeduction = $deductionAmount + $tempLoanTotal;
            
                // Compute net salary
                $netSalary = ($baseSalary + $overtimePay)
                    - $totalDeduction
                    + $employee->home_allowance
                    + $employee->medical_allowance
                    + $employee->mobile_allowance;
            
                // Update the existing salary record with the recalculated values
                $existingSalary->update([
                    'total_present_days'    => $presentDays,
                    'total_hours'           => $totalWorkedHours,
                    'total_minutes'         => $totalWorkedMinutes,
                    'total_overtime_hours'  => $totalOvertimeHours,
                    'total_overtime_minutes'=> $totalOvertimeMinutes,
                    'basic_salary'          => $baseSalary,
                    'overtime'              => $overtimePay,
                    'deduction'             => $totalDeduction,
                    'loan_deduction'        => $deductionAmount,
                    'temp_deduction'        => $tempLoanTotal,
                    'net_salary'            => $netSalary,
                    'late_hours'            => $lateHours,
                    'home_allowance'        => $employee->home_allowance,
                    'medical_allowance'     => $employee->medical_allowance,
                    'mobile_allowance'      => $employee->mobile_allowance,
                ]);

                // update salary statement
                $salaryDepositStatement = EmployeeStatement::where('employee_id',  $employee->id)
                    ->where('year', $this->year)
                    ->where('month', $this->month)
                    ->where('type', 'SALARY_DEPOSIT')
                    ->first();
                
                if($salaryDepositStatement) {
                    $salaryDepositStatement->update([
                        'deposit' => $baseSalary + $overtimePay + $employee->medical_allowance + $employee->home_allowance + $employee->mobile_allowance
                    ]);
                }

                // update temp advance statement
                $tempAdvStatement = EmployeeStatement::where('employee_id',  $employee->id)
                    ->where('year', $this->year)
                    ->where('month', $this->month)
                    ->where('type', 'ADV_DEDUCT')
                    ->first();
                
                if($tempAdvStatement) {
                    $tempAdvStatement->update([
                        'withdraw' => $tempLoanTotal
                    ]);
                }

                // update temp advance statement
                $salaryWithdrawStatement = EmployeeStatement::where('employee_id',  $employee->id)
                    ->where('year', $this->year)
                    ->where('month', $this->month)
                    ->where('type', 'SALARY_WITHDRAW')
                    ->first();
                
                if($salaryWithdrawStatement) {
                    $salaryWithdrawStatement->update([
                        'withdraw' => $netSalary
                    ]);
                }

            }                                
            else {
                
                // loan deduction
                $deductionAmount = $existingSalary->loan_deduction;
                // temp loan
                $tempLoanTotal = $existingSalary->temp_deduction;
                // Total deduction calculation
                $totalDeduction = $existingSalary->deduction;
                // Compute net salary correctly
                $netSalary = $existingSalary->net_salary;

            }

        }

        $this->salariesCalculated = true;

    }




}
