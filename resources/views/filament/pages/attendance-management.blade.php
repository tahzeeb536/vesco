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
            Save Attendance
        </x-filament::button>
    </div>


    <table class="min-w-full text-xs divide-y divide-gray-200 border border-gray-300 shadow-sm rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-3 py-4 text-left font-semibold text-gray-700">Name</th>
                <th class="px-3 py-4 text-left font-semibold text-gray-700">Update</th>
                <th class="px-2 py-4 text-left font-semibold text-gray-700">Clock In</th>
                <th class="px-2 py-4 text-left font-semibold text-gray-700">Clock Out</th>
                <th class="px-1 py-4 text-center font-semibold text-gray-700 w-16">Hrs</th>
                <th class="px-1 py-4 text-center font-semibold text-gray-700 w-16">Min</th>
                <th class="px-1 py-4 text-center font-semibold text-gray-700 w-16">OT Hrs</th>
                <th class="px-1 py-4 text-center font-semibold text-gray-700 w-16">OT Min</th>
                <th class="px-2 py-4 text-center font-semibold text-gray-700 w-20">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @foreach($attendances as $index => $attendance)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-1 text-gray-900 font-medium truncate">{{ $attendance['name'] }}</td>

                    <td class="px-3 py-1">
                        <select wire:model="attendances.{{ $index }}.status"
                                class="w-full rounded shadow-sm text-xs {{ ($attendance['status'] == 'present') ? 'text-success' : 'border-gray-300' }}">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </td>

                    <td class="px-2 py-1">
                        <input type="time" wire:model="attendances.{{ $index }}.clock_in"
                            class="w-full rounded border-gray-300 shadow-sm text-xs" />
                    </td>

                    <td class="px-2 py-1">
                        <input type="time" wire:model="attendances.{{ $index }}.clock_out"
                            class="w-full rounded border-gray-300 shadow-sm text-xs" />
                    </td>

                    <td class="px-1 py-1 text-center">
                        <input type="number" wire:model="attendances.{{ $index }}.hours_worked"
                            class="w-full rounded border-gray-300 shadow-sm text-xs text-center" />
                    </td>

                    <td class="px-1 py-1 text-center">
                        <input type="number" wire:model="attendances.{{ $index }}.minutes_worked"
                            class="w-full rounded border-gray-300 shadow-sm text-xs text-center" />
                    </td>

                    <td class="px-1 py-1 text-center">
                        <input type="number" wire:model="attendances.{{ $index }}.overtime_hours"
                            class="w-full rounded border-gray-300 shadow-sm text-xs text-center" />
                    </td>

                    <td class="px-1 py-1 text-center">
                        <input type="number" wire:model="attendances.{{ $index }}.overtime_minutes"
                            class="w-full rounded border-gray-300 shadow-sm text-xs text-center" />
                    </td>

                    <td class="px-2 py-1 text-center">
                        @if($attendance['saved'])
                            <span class="inline-flex items-center px-2 py-0.5 text-green-700 bg-green-100 rounded-full">
                                {{ $attendance['status'] }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-orange-700 bg-orange-100 rounded-full">
                                -
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



</x-filament::page>
