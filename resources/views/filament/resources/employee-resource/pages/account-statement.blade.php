<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-xl font-semibold">Statement of: {{ $record->name }}</h2>

        <table class="w-full table-auto border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Details</th>
                    <th class="px-4 py-2 border">Deposit</th>
                    <th class="px-4 py-2 border">Withdraw</th>
                    <th class="px-4 py-2 border">Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningBalance = 0;
                @endphp
                @forelse ($record->employee_statements->sortByDesc('datetime') as $statement)
                        @php
                            if($statement->deposit > 0) {
                                $runningBalance += $statement->deposit;
                            }
                            
                            if($statement->withdraw > 0) {
                                $runningBalance -= $statement->withdraw;
                            }
                        @endphp
                    <tr>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($statement->datetime)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 border">{{ $statement->details }}</td>
                        <td class="px-4 py-2 border text-green-600">{{ number_format($statement->deposit, 0) }}</td>
                        <td class="px-4 py-2 border text-red-600">{{ number_format($statement->withdraw, 0) }}</td>
                        <td class="px-4 py-2 border">{{ $runningBalance }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 border text-center text-gray-500">No statements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
