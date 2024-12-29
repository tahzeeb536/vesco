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
            <th>Designation</th>
            <td>{{ $employee->designation }}</td>
        </tr>
        <tr>
            <th>Basic Salary</th>
            <td>{{ number_format($employee->basic_salary, 2) }}</td>
            <th>Department</th>
            <td>{{ $employee->department }}</td>
        </tr>
    </table>

    <!-- Attendance Table -->
    <table class="attendance-table">
        <thead>
            <tr>
                <th colspan="11">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $daysInMonth = \Carbon\Carbon::create($salaries->year, $salaries->month)->daysInMonth;
                $chunks = collect(range(1, $daysInMonth))->chunk(10); // Break into chunks of 10
            @endphp

            @foreach ($chunks as $chunk)
                <tr>
                    <th>Day</th>
                    @foreach ($chunk as $day)
                        <td>{{ $day }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Status</th>
                    @foreach ($chunk as $day)
                        @php
                            $date = \Carbon\Carbon::create($salaries->year, $salaries->month, $day)->format('Y-m-d');
                            $status = $attendance_status->firstWhere('date', $date)['status'] ?? 'A';
                        @endphp
                        <td>{{ $status == 'Present' ? 'P' : ($status == 'Leave' ? 'L' : 'A') }}</td>
                    @endforeach
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
