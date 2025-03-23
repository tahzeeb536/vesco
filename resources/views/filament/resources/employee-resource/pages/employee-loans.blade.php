<x-filament::page>
    <style>
        #balance-parent {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        #balance-parent > div {
            width: 100%;
        }

        @media (min-width: 550px) {
            #balance-parent > div {
                width: 45%;
            } 
        }


    </style>
    <div id="balance-parent">
        @foreach ($this->getLoanStats() as $stat)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-500">
                    {{ $stat['label'] }}
                </div>
                <div class="text-xl font-bold mt-1 {{ $stat['color'] ?? 'text-gray-900' }}">
                    {{ $stat['value'] }}
                </div>
            </div>
        @endforeach
    </div>


    {{ $this->table }}
</x-filament::page>
