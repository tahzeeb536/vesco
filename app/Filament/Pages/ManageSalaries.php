<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\AdvanceSalaryBalance;
use App\Models\Attendance;
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
use Illuminate\Support\Facades\Validator;

class ManageSalaries extends Page implements HasTable
{
    use InteractsWithTable, InteractsWithForms, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                        ->default(now()->format('m')) // Default to current month
                        ->required(),
                    Forms\Components\Select::make('year')
                        ->options(function () {
                            $currentYear = now()->year;
                            $years = [];
                            for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                                $years[$i] = $i;
                            }
                            return $years;
                        })
                        ->label('Year')
                        ->default(now()->year) // Default to current year
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
        ];
    }

    public function table(Table $table): Table {
        $selectedMonth = $this->month ?? now()->format('m');
        $selectedYear = $this->year ?? now()->year;
    
        return $table->query(
            Employee::query()->with(['salaries' => function ($query) use ($selectedMonth, $selectedYear) {
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
                ->label('Total Days'),
            Tables\Columns\TextColumn::make('salaries.total_overtime_hours')
                ->label('Total Overtime'),
            Tables\Columns\TextColumn::make('salaries.absent_days_salary_deduction')
                ->label('Absence Deduction'),
            Tables\Columns\TextColumn::make('salaries.deduction')
                ->label('Advance Deduction'),
            Tables\Columns\TextColumn::make('salaries.net_salary')
                ->label('Net Salary'),
        ])
        ->actions([
            Tables\Actions\Action::make('view_salary_sheet')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(fn (Employee $record) => route('print_salary', [
                    'id' => $record->id,
                    'month' => $selectedMonth,
                    'year' => $selectedYear,
                ]))
                ->openUrlInNewTab()
                ->tooltip('View and Print Salary Sheet'),
        ])
        ->filters([])
        ->bulkActions([]);
    }
    
    
    public function calculateSalaries()
    {
        $employees = Employee::where('status', true)->get();
        $today = Carbon::today();

        foreach ($employees as $employee) {
            // Fetch attendance for the employee
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();

            // Mark past and current Fridays as present
            $datesInMonth = Carbon::create($this->year, $this->month)->daysInMonth;
            for ($day = 1; $day <= $datesInMonth; $day++) {
                $date = Carbon::create($this->year, $this->month, $day);
                if ($date->isFriday() && $date <= $today) {
                    Attendance::updateOrCreate(
                        ['employee_id' => $employee->id, 'date' => $date],
                        ['status' => 'Present', 'hours_worked' => 8.0, 'overtime_hours' => 0.0]
                    );
                }
            }

            // Recalculate attendance after updating Fridays
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();

            // Calculate total minutes worked and overtime minutes
            $totalPresentDays = $attendances->where('status', 'Present')->count();
            $totalMinutesWorked = $attendances->sum(function ($attendance) {
                [$hours, $minutes] = explode(':', gmdate('H:i', $attendance->hours_worked * 3600));
                return $hours * 60 + $minutes;
            });

            $totalOvertimeMinutes = $attendances->sum(function ($attendance) {
                [$hours, $minutes] = explode(':', gmdate('H:i', $attendance->overtime_hours * 3600));
                return $hours * 60 + $minutes;
            });

            $totalAbsentDays = $attendances->where('status', 'Absent')->count();

            // Salary calculations
            $dailySalary = $employee->basic_salary / 30; // Assuming 30 days in a month
            $minuteSalary = $dailySalary / 480; // Daily salary divided by total minutes in a day

            $absentDaysDeduction = $dailySalary * $totalAbsentDays;
            $earnedSalaryFromMinutes = $minuteSalary * $totalMinutesWorked;

            // Calculate overtime bonus
            $overtimeBonus = ($totalOvertimeMinutes / 360) * $dailySalary; // 360 minutes = 6 hours equivalent to a full day

            // Net salary calculation
            $netSalary = $earnedSalaryFromMinutes + $overtimeBonus - $absentDaysDeduction;

            // Deduct advance salary installment if applicable
            $advanceBalance = AdvanceSalaryBalance::where('employee_id', $employee->id)->first();
            if ($advanceBalance && $advanceBalance->remaining_amount > 0) {
                $monthlyDeduction = $advanceBalance->monthly_deduction;
                $deduction = min($netSalary, $monthlyDeduction);
                $netSalary -= $deduction;

                // Update advance balance
                $advanceBalance->remaining_amount -= $deduction;
                $advanceBalance->paid_amount += $deduction;
                $advanceBalance->save();
            }

            // Store salary in the table
            Salary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'month' => $this->month,
                    'year' => $this->year,
                ],
                [
                    'total_present_days' => $totalPresentDays,
                    'total_overtime_hours' => $totalOvertimeMinutes / 60,
                    'absent_days_salary_deduction' => $absentDaysDeduction,
                    'net_salary' => $netSalary,
                ]
            );
        }
    }


}
