<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Salary Slips</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 1000px;
            margin: 0 auto 20px auto;
            border: 2px solid #000;
            padding: 10px;
        }
        .logo {
            text-align: center;
            padding: 10px 0;
        }
        .logo h1 {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 0 0;
            margin: 10px;
            text-transform: uppercase;
        }
        .bt {
            border-top: 1px solid #000;
        }
        .break-page {
            display: block;
            page-break-after: always;
        }
        .heading {
            font-size: 20px;
            text-align: center;
            padding: 0;
            margin: 0;
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    @foreach($employees as $employee)
        @php 
            $salary = $employee->salaries->first();
            $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
            $period = \Carbon\Carbon::create($year, $month)->format('F, Y');
        @endphp
        <div class="container">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="50%" valign="middle">
                        <img height="50" src="{{ asset('images/ilogo.png') }}" alt="">
                    </td>
                    <td width="50%" align="right">
                        <img src="{{ ($employee->photo) ? asset('storage/employee-images/'.$employee->photo) : asset('images/placeholder.png') }}" height="100" alt="">
                    </td>
                </tr>
            </table>

            <table border="1" width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse">
                <tr>
                    <td colspan="2">
                        <h1 class="heading">Pay Slip for the period of {{ $period }}</h1>
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="left">
                        <strong>Name</strong> <br> {{ $employee->name }}
                    </td>
                    <td width="50%">
                        <strong>Father Name / Phone</strong> <br> {{ $employee->father_name }} / {{ $employee->phone }}
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="left">
                        <strong>Earnings</strong>
                    </td>
                    <td width="50%">
                        <strong>Deduction</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="5" border="0" width="100%">
                            <tr>
                                <td>Basic Pay: </td>
                                <td width="100">{{ number_format($employee->basic_salary, 0) }}</td>
                            </tr>
                            <tr>
                                <td>Overtime hours: </td>
                                <td width="100">({{ $salary ? $salary->total_overtime_hours : 0 }})</td>
                            </tr>
                            <tr>
                                <td>Late hours:</td>
                                <td>{{ ($salary->late_hours == 0.00) ? 0 :  $salary->late_hours }}</td>
                            </tr>
                            <tr>
                                <td>Absents: </td>
                                <td width="100">({{ $employee->absent_days }})</td>
                            </tr>
                            <tr>
                                <td>Medical Allowance: </td>
                                <td width="100">{{ $employee->medical_allowance }}</td>
                            </tr>
                            <tr>
                                <td>Home Allowance: </td>
                                <td width="100">{{ $employee->home_allowance }}</td>
                            </tr>
                            <tr>
                                <td>Mobile Allowance: </td>
                                <td width="100">{{ $employee->mobile_allowance }}</td>
                            </tr>
                            <tr>
                                <td class="bt"><strong>Total Earnings</strong>: </td>
                                <td class="bt" width="100"><strong>{{ $salary ? number_format(($salary->net_salary + $salary->deduction)) : 0 }}</strong></td>
                            </tr>
                        </table>
                    </td>
                    <td valign="bottom">
                        <table cellspacing="0" cellpadding="5" border="0" width="100%">
                            <tr>
                                <td>Advance Salary: </td>
                                <td>{{ $salary ? number_format($salary->temp_deduction) : 0 }}</td>
                            </tr>
                            <tr>
                                <td>Monthly Deductions: </td>
                                <td width="75">{{ $salary ? number_format($salary->loan_deduction) : 0 }}</td>
                            </tr>
                            <tr>
                                <td class="bt"><strong>Total Deductions</strong>: </td>
                                <td class="bt" width="100"><strong>{{ $salary ? number_format($salary->deduction) : 0 }}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><strong>NET SALARY: Rs.{{ $salary ? number_format($salary->net_salary) : 0 }}/-</strong></td>
                    <td>
                        @php
                            $balance = isset($employee->advance_salary_balance) ? number_format($employee->advance_salary_balance->remaining_amount) : 0;
                            if($balance > 0) {
                                $balance = '-' . $balance;
                            }
                        @endphp
                        <strong>Current Balance: </strong>{{ $balance }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table border="1" style="border-collapse:collapse;" width="100%" cellpadding="3" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Days</th>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <th>{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php 
                                            $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                                            $attendance = $employee->attendance_status->firstWhere('date', $date);
                                        @endphp
                                        <td>
                                            @if($attendance)
                                                @if($attendance['status'] === 'Present')
                                                    P
                                                @elseif($attendance['status'] === 'Leave')
                                                    L
                                                @else
                                                    A
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>Hours</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php 
                                            $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                                            $attendance = $employee->attendance_status->firstWhere('date', $date);
                                        @endphp
                                        <td>{{ $attendance ? $attendance['hours_worked'] : '-' }}</td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>Late</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        <td>0</td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>O.T</td>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php 
                                            $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                                            $attendance = $employee->attendance_status->firstWhere('date', $date);
                                        @endphp
                                        <td>{{ $attendance ? $attendance['overtime_hours'] : '-' }}</td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="break-page"></div>
    @endforeach
</body>
</html>
