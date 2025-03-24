<x-filament-panels::page>
    <x-slot name="headerActions">
        @foreach ($this->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </x-slot>    
    
    <style>
        .table-wrapper {
            overflow-x: scroll;
        }
        table {
            font-size: 14px;
        }
        table td, table th {
            border: 1px solid gray;
            padding: 10px;
        }
        .btn-get_data {
            margin-left: 5px;
            background: lightgray;
            padding: 9px 15px;
            border-radius: 5px;
        }
        #employee_salar_month {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        @media screen and (min-width: 650px) {
            #employee_salar_month {
                flex-direction: row;
                align-items: center;
            }
        }
    </style>

    <!-- Filter Form -->
    <form method="GET" action="" id="employee_salar_month">
        <label for="month">Month:</label>
        <select name="month" id="month">
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}" {{ $month == $this->selectedMonth ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                </option>
            @endforeach
        </select>
        <label for="year">Year:</label>
        <input type="number" name="year" id="year" value="{{ $this->selectedYear }}">
        <button type="submit" class="btn-get_data">Get Data</button>
    </form>

    <!-- Attendance Table -->
    <div class="table-wrapper">
        <div>
            <h2>Employee Name: <b>{{ $employee->name }}</b></h2>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Days</th> {{-- Header for column label --}}
                    @foreach ($attendanceData['dayNames'] as $dayName)
                        <th>{{ $dayName }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th> {{-- Empty cell for alignment --}}
                    @foreach ($attendanceData['days'] as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Status</strong></td>
                    @foreach ($attendanceData['status'] as $status)
                        <td class="text-center">{{ ($status == 'Present') ? 'P' : 'A' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>Hours</strong></td>
                    @foreach ($attendanceData['hours'] as $hour)
                        <td class="text-center">{{ $hour }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>Late</strong></td>
                    @foreach ($attendanceData['late'] as $late)
                        <td class="text-center">{{ $late }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>O.T</strong></td>
                    @foreach ($attendanceData['ot'] as $ot)
                        <td class="text-center">{{ $ot }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="{{ count($attendanceData['days']) + 1 }}">
                        <!-- Example summary: adjust calculations as needed -->
                        <strong>Absents:</strong> {{ $attendanceData['absentCount']}} 
                        <strong>Leaves:</strong> 0 
                        <strong>Presents:</strong> {{ count($attendanceData['days']) }} 
                        <strong>Total Hours:</strong> {{ array_sum($attendanceData['hours']) }} 
                        <strong>Overtime:</strong> {{ array_sum($attendanceData['ot']) }}
                        <strong>Total Late Hours:</strong> {{ array_sum($attendanceData['late']) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
