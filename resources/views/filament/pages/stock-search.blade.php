<x-filament::page>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Stock Search</h2>
        <form method="GET" action="{{ route('stock.report') }}" target="_blank" id="stock-search-form">
            <!-- Grid Layout with 3 inputs per row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Article Number -->
                <div>
                    <label for="article_number" class="block text-sm font-medium text-gray-700">Article Number</label>
                    <input type="text" name="article_number" id="article_number"
                        placeholder="Enter article number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="product_name" id="product_name"
                        placeholder="Enter product name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Vendor Name -->
                <div>
                    <label for="vendor_name" class="block text-sm font-medium text-gray-700">Vendor Name</label>
                    <input type="text" name="vendor_name" id="vendor_name"
                        placeholder="Enter vendor name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Size -->
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                    <input type="text" name="size" id="size"
                        placeholder="Enter size"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <input type="text" name="color" id="color"
                        placeholder="Enter color"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" name="category" id="category"
                        placeholder="Enter category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Store -->
                <div>
                    <label for="store" class="block text-sm font-medium text-gray-700">Store</label>
                    <input type="text" name="store" id="store"
                        placeholder="Enter store name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Room -->
                <div>
                    <label for="room" class="block text-sm font-medium text-gray-700">Room</label>
                    <input type="text" name="room" id="room"
                        placeholder="Enter room name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Rack -->
                <div>
                    <label for="rack" class="block text-sm font-medium text-gray-700">Rack</label>
                    <input type="text" name="rack" id="rack"
                        placeholder="Enter rack name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <!-- Shelf -->
                <div>
                    <label for="shelf" class="block text-sm font-medium text-gray-700">Shelf</label>
                    <input type="text" name="shelf" id="shelf"
                        placeholder="Enter shelf name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    style="background-color: #0ea5e9;">
                    Search
                </button>
            </div>
        </form>
    </div>
</x-filament::page>
