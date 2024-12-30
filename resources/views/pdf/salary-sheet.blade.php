<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .details-table, .attendance-table, .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th, .details-table td,
        .attendance-table th, .attendance-table td,
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .details-table th, .attendance-table th, .summary-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .attendance-table th {
            font-size: 12px;
        }
        .attendance-table td {
            font-size: 12px;
        }
        .summary-table th {
            text-align: left;
        }
        .summary-table td {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Employee Details -->
    <div class="header">
        <h1>Salary Sheet</h1>
        <p>Month: {{ \Carbon\Carbon::create()->month($salaries->month)->format('F') }} {{ $salaries->year }}</p>
    </div>

    <table class="details-table">
        <tr>
            <th>Employee Name</th>
            <td>{{ $employee->name }}</td>
            <th>Father's Name</th>
            <td>{{ $employee->father_name }}</td>
        </tr>
        <tr>
            <th>CNIC</th>
            <td>{{ $employee->cnic }}</td>
            <th>Phone No.</th>
            <td>{{ $employee->phone }}</td>
        </tr>
    </table>

    <!-- Attendance Table -->
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Saturday</th>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
            </tr>
        </thead>
        <tbody>
            @php
                $daysInMonth = \Carbon\Carbon::create($salaries->year, $salaries->month)->daysInMonth;
                $firstDay = \Carbon\Carbon::create($salaries->year, $salaries->month, 1);
                $weeks = [];
                $currentWeek = [];

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = \Carbon\Carbon::create($salaries->year, $salaries->month, $day);
                    $currentWeek[$date->dayOfWeek] = [
                        'day' => $day,
                        'date' => $date->format('Y-m-d')
                    ];

                    if ($date->dayOfWeek == 5 || $day == $daysInMonth) {
                        $weeks[] = $currentWeek;
                        $currentWeek = [];
                    }
                }
            @endphp

            @foreach ($weeks as $week)
                <!-- Day Numbers Row -->
                <tr>
                    <th>Day</th>
                    @for ($day = 6; $day <= 12; $day++)
                        <td>
                            {{ isset($week[$day % 7]) ? $week[$day % 7]['day'] : '-' }}
                        </td>
                    @endfor
                </tr>
                <!-- Status Row -->
                <tr>
                    <th>Status</th>
                    @for ($day = 6; $day <= 12; $day++)
                        @php
                            $status = isset($week[$day % 7]) ? 
                                ($attendance_status->firstWhere('date', $week[$day % 7]['date'])['status'] ?? 'A') : null;
                        @endphp
                        <td>
                            {{ $status ? ($status == 'Present' ? 'P' : ($status == 'Leave' ? 'L' : 'A')) : '-' }}
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Salary Summary -->
    <table class="summary-table">
        <tr>
            <th>Total Present Days</th>
            <td>{{ $salaries->total_present_days }}</td>
        </tr>
        <tr>
            <th>Absent Days</th>
            <td>{{ collect($attendance_status)->where('status', 'Absent')->count() }}</td>
        </tr>
        <tr>
            <th>Overtime Hours</th>
            <td>{{ $salaries->total_overtime_hours }}</td>
        </tr>
        <tr>
            <th>Absent Deductions</th>
            <td>{{ number_format($salaries->absent_days_salary_deduction, 2) }}</td>
        </tr>
        <tr>
            <th>Advance Salary Deductions</th>
            <td>{{ number_format($salaries->deduction ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th>Net Pay</th>
            <td><strong>{{ number_format($salaries->net_salary, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>Generated on:</strong> {{ now()->format('d M Y, h:i A') }}</p>
    </div>
</body>
</html>
