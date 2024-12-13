@php
$boxes = (isset($this->record)) ? $this->record->boxes()->get()->toArray() : [];
@endphp
<div x-data="{
    customer_id: @entangle('data.customer_id'),
    rows: @js($boxes ?? []).length > 0 
        ? @js($boxes).map(item => ({
            id: item.id || Date.now(),
            cartons: item.cartons || '',
            qty_cartons: item.qty_cartons || '',
            article_no: item.article_no || '',
            details: item.details || '',
            size_qty: item.size_qty || '',
            total_qty: item.total_qty || '',
        }))
        : [{ id: Date.now(), cartons: '', qty_cartons: '', article_no: '', details: '', size_qty: '', total_qty: '' }],
    
    packagingBoxes: @entangle('data.packaging_boxes'),
    addRow() {
        this.rows.push({ id: Date.now(), cartons: '', qty_cartons: '', article_no: '', details: '', size_qty: '', total_qty: '' });
        this.syncPackagingBoxes();
    },
    removeRow(id) {
        this.rows = this.rows.filter(row => row.id !== id);
        this.syncPackagingBoxes();
    },
    calculateTotal(row) {
        total_qty = row.cartons * row.qty_cartons
        row.total_qty = total_qty;
        this.syncPackagingBoxes();
    },
    syncPackagingBoxes() {
        this.packagingBoxes = JSON.stringify(this.rows);
         console.log(this.rows); 
    }
}" >
    <div class="overflow-x-auto relative bg-gray-200" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full table-auto border border-gray-300" style="width: 100%;">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 w-1/3">
                        Cartons
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Qty / Cartons
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Article #
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Details
                    </th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">
                        Size / Qty
                    </th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">
                        Total Qty
                    </th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 bg-white" style="width: 100%;">
                <template x-for="(row, index) in rows" :key="row.id">
                    <tr style="width: 100%;">
                        <!-- Cartons -->
                        <td class="p-0 border border-gray-300 relative" style="width: 15%;">
                            <input type="number"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.cartons"
                                   min="1"
                                   @input="calculateTotal(row)" />
                        </td>

                        <!-- Qty Cartons -->
                        <td class="p-0 border border-gray-300" style="width: 15%;">
                            <input type="number"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.qty_cartons"
                                   min="1"
                                   @input="calculateTotal(row)" />
                        </td>

                        <!-- Article # -->
                        <td class="p-0 border border-gray-300" style="width: 15%;">
                            <input type="text"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model="row.article_no"
                                   @input="syncPackagingBoxes()" />
                        </td>

                        <!-- Detail -->
                        <td class="p-0 border border-gray-300" style="width: 20%;">
                            <input type="text"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model="row.details"
                                   @input="syncPackagingBoxes()" />
                        </td>

                        <!-- Size Qty -->
                        <td class="p-0 border border-gray-300" style="width: 15%;">
                            <input type="text"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model="row.size_qty"
                                   @input="syncPackagingBoxes()" />
                        </td>

                        <!-- Total Qty -->
                        <td class="p-0 border border-gray-300" style="width: 15%;">
                            <input type="text"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.total_qty"
                                   readonly />
                        </td>

                        <!-- Actions -->
                        <td class="p-0 border border-gray-300 text-center" style="width: 10%;">
                            <button type="button"
                                    class="inline-flex items-center p-1 text-blue-500 hover:text-blue-700"
                                    @click="addRow()"
                                    title="Add Row">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            <button type="button"
                                    class="inline-flex items-center p-1 text-red-500 hover:text-red-700 ml-2"
                                    @click="removeRow(row.id)"
                                    title="Delete Row"
                                    x-show="rows.length > 1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
