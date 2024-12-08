@php
$items = (isset($this->record)) ? $this->record->items()->with('variant')->get()->toArray() : [];
@endphp
<div x-data="{
    vendor_id: @entangle('data.vendor_id'),
    rows: @js($items ?? []).length > 0 
        ? @js($items).map(item => ({
            id: item.id || Date.now(),
            variant_id: item.variant_id || '',
            variant_name: item.variant?.name || '',
            quantity: item.quantity || 1,
            unit_price: item.unit_price || 0,
            total_price: item.total_price || 0,
        }))
        : [{ id: Date.now(), variant_id: '', variant_name: '', quantity: 1, unit_price: 0, total_price: 0 }],
    
    orderItems: @entangle('data.order_items'),
    fetchVariants(query) {
        return fetch(`/api/product-variants?search=${query}`)
            .then(response => response.json())
            .catch(() => []);
    },
    addRow() {
        this.rows.push({ id: Date.now(), variant_id: '', variant_name: '', quantity: 1, unit_price: 0, total_price: 0 });
        this.syncOrderItems();
    },
    removeRow(id) {
        this.rows = this.rows.filter(row => row.id !== id);
        this.syncOrderItems();
    },
    calculateTotal(row) {
        total_price = row.quantity * row.unit_price
        row.total_price = total_price.toFixed(2);
        this.syncOrderItems();
    },
    syncOrderItems() {
        this.orderItems = JSON.stringify(this.rows);
    },
    setVariantPrice(variant_id) {
        let vendor_id = this.vendor_id;
        return fetch(`/api/variant-vendor-price?variant_id=${variant_id}&vendor_id=${vendor_id}`)
            .then(response => response.json())
            .catch(() => 0);
    }
}" >
    <div class="overflow-x-auto relative bg-gray-200" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full table-auto border border-gray-300" style="width: 100%;">
            <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 w-1/3">
                        Product Variant Name
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Quantity
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Unit Price
                    </th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">
                        Total Price
                    </th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 bg-white" style="width: 100%;">
                <template x-for="(row, index) in rows" :key="row.id">
                    <tr style="width: 100%;">
                        <!-- Product Variant Name -->
                        <td class="p-0 border border-gray-300 relative" style="width: 54%;">
                            <div x-data="{ 
                                isOpen: false, 
                                variants: [], 
                                selectedIndex: -1 
                            }" @click.outside="isOpen = false" class="relative">
                                <!-- Variant Name Input -->
                                <input type="text"
                                    x-model="row.variant_name"
                                    class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                    style="outline: none; box-shadow: none;"
                                    placeholder="Type to search..."
                                    @input.debounce.500ms="fetchVariants($event.target.value).then(data => { 
                                        variants = data; 
                                        isOpen = true; 
                                        selectedIndex = -1;
                                    })"
                                    @focus="isOpen = true"
                                    @keydown.arrow-down.prevent="if (variants.length > 0) { 
                                        selectedIndex = (selectedIndex + 1) % variants.length; 
                                        $refs.dropdown.querySelector('#variant-item-' + selectedIndex)?.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                                    }"
                                    @keydown.arrow-up.prevent="if (variants.length > 0) { 
                                        selectedIndex = (selectedIndex - 1 + variants.length) % variants.length; 
                                        $refs.dropdown.querySelector('#variant-item-' + selectedIndex)?.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                                    }"
                                    @keydown.enter.prevent="if (selectedIndex >= 0 && variants.length > 0) { 
                                        const selectedVariant = variants[selectedIndex];
                                        row.variant_id = selectedVariant.id;
                                        row.variant_name = selectedVariant.name;
                                        setVariantPrice(row.variant_id).then(price => {
                                            row.unit_price = price;
                                            calculateTotal(row);
                                            isOpen = false; 
                                        });
                                    }" />
                                
                                <!-- Hidden Input for Variant ID -->
                                <input type="hidden" x-model="row.variant_id" />

                                <!-- Dropdown Menu -->
                                <ul x-show="isOpen && variants.length > 0"
                                    x-ref="dropdown"
                                    class="absolute left-0 z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg overflow-y-auto"
                                    style="top: 100%; transform: translateY(8px); max-height: 150px;">
                                    <template x-for="(variant, i) in variants" :key="variant.id">
                                        <li @click="
                                                row.variant_id = variant.id;
                                                row.variant_name = variant.name;
                                                setVariantPrice(row.variant_id).then(price => {
                                                    row.unit_price = price;
                                                    calculateTotal(row);
                                                    isOpen = false;
                                                });"
                                            class="px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100"
                                            :class="{'bg-gray-200': selectedIndex === i}"
                                            :id="'variant-item-' + i">
                                            <span x-text="variant.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </td>

                        <!-- Quantity -->
                        <td class="p-0 border border-gray-300" style="width: 12%;">
                            <input type="number"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.quantity"
                                   min="1"
                                   @input="calculateTotal(row)" />
                        </td>

                        <!-- Unit Price -->
                        <td class="p-0 border border-gray-300" style="width: 12%;">
                            <input type="number"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.unit_price"
                                   min="0"
                                   step="any"
                                   @input="calculateTotal(row)" />
                        </td>

                        <!-- Total Price -->
                        <td class="p-0 border border-gray-300" style="width: 12%;">
                            <input type="text"
                                   class="w-full h-full text-sm text-gray-700 bg-transparent border-none px-2 py-2"
                                   style="outline: none; box-shadow: none;"
                                   x-model.number="row.total_price"
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
