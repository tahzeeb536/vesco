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
                <th class="px-3 py-4 text-left font-semibold text-gray-700">Urdu Name</th>
                <th class="px-2 py-4 text-center font-semibold text-gray-700 w-20">Status</th>
                <th class="px-2 py-4 text-center font-semibold text-gray-700 w-20">Hours</th>
                <th class="px-2 py-4 text-center font-semibold text-gray-700 w-24">Overtime</th>
                <th class="px-2 py-4 text-left font-semibold text-gray-700">Clock In</th>
                <th class="px-2 py-4 text-left font-semibold text-gray-700">Break Out</th>
                <th class="px-2 py-4 text-left font-semibold text-gray-700">Break In</th>
                <th class="px-3 py-4 text-left font-semibold text-gray-700">Update</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            @foreach($attendances as $index => $attendance)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-1 text-gray-900 font-medium truncate">
                        {{ $attendance['name'] }}
                    </td>

                    <td class="px-3 py-1 text-gray-900 font-medium truncate">
                        {{ $attendance['name_urdu'] }}
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

                    <td class="px-2 py-1 text-center">
                        <input type="text"
                            readonly
                            value="{{ str_pad($attendance['hours_worked'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($attendance['minutes_worked'], 2, '0', STR_PAD_LEFT) }}"
                            class="w-full bg-gray-100 text-gray-700 rounded border border-gray-300 shadow-sm text-xs text-center"
                        />
                    </td>

                    <td class="px-2 py-1 text-center">
                        <input type="number" step="0.01" wire:model="attendances.{{ $index }}.overtime"
                            class="w-full rounded border-gray-300 shadow-sm text-xs text-center" />
                    </td>

                    <td class="px-2 py-1">
                        <input
                            type="time"
                            wire:model.lazy="attendances.{{ $index }}.clock_in"
                            class="w-full rounded border-gray-300 shadow-sm text-xs"
                        />
                    </td>

                    <td class="px-2 py-1">
                        <input
                            type="time"
                            wire:model.lazy="attendances.{{ $index }}.break_out"
                            class="w-full rounded border-gray-300 shadow-sm text-xs"
                        />
                    </td>

                    <td class="px-2 py-1">
                        <input
                            type="time"
                            wire:model.lazy="attendances.{{ $index }}.break_in"
                            class="w-full rounded border-gray-300 shadow-sm text-xs"
                        />
                    </td>

                    <td class="px-3 py-1">
                        <select wire:model="attendances.{{ $index }}.status"
                                class="w-full rounded shadow-sm text-xs border-gray-300">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</x-filament::page>
