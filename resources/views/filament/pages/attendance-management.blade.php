<x-filament::page>
    <div class="flex items-center justify-between mb-4">
        <div>
            <label for="selectedDate" class="text-sm font-medium text-gray-700">Select Date</label>
            <input
                type="date"
                id="selectedDate"
                wire:model="selectedDate"
                wire:change="loadAttendanceData"
                class="border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-primary-500 focus:border-primary-500"
            />
        </div>

        <x-filament::button wire:click="saveAllAttendance" color="primary">
            Save All Attendance
        </x-filament::button>
    </div>


    <table class="w-full table-auto text-sm border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Clock In</th>
                <th class="p-2 border">Clock Out</th>
                <th class="p-2 border">Hours</th>
                <th class="p-2 border">Minutes</th>
                <th class="p-2 border">OT Hours</th>
                <th class="p-2 border">OT Minutes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
                <tr>
                    <td class="p-2 border">{{ $attendance['name'] }}</td>

                    <td class="p-2 border">
                        <select wire:model="attendances.{{ $index }}.status" class="w-full">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </td>

                    <td class="p-2 border">
                        <input type="time" wire:model="attendances.{{ $index }}.clock_in" class="w-full" />
                    </td>

                    <td class="p-2 border">
                        <input type="time" wire:model="attendances.{{ $index }}.clock_out" class="w-full" />
                    </td>

                    <td class="p-2 border">
                        <input type="number" wire:model="attendances.{{ $index }}.hours_worked" class="w-full" />
                    </td>

                    <td class="p-2 border">
                        <input type="number" wire:model="attendances.{{ $index }}.minutes_worked" class="w-full" />
                    </td>

                    <td class="p-2 border">
                        <input type="number" wire:model="attendances.{{ $index }}.overtime_hours" class="w-full" />
                    </td>

                    <td class="p-2 border">
                        <input type="number" wire:model="attendances.{{ $index }}.overtime_minutes" class="w-full" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
