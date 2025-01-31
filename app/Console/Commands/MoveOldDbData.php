<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveOldDbData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-old-db-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command reads data from old database and moves to the new database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Manage categories
        $this->info('Moving categories data...');
        // $old_categories = DB::connection('old_db')->table('categories')->get();
        // foreach ($old_categories as $category) {
        //     DB::connection('mysql')->table('categories')->insert([
        //         'id' => $category->id,
        //         'name' => $category->name,
        //         'image' => $category->image,
        //         'status' => $category->status,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Creating store, rooms. racks and shelves...');
        // Create Store
        // DB::connection('mysql')->table('stores')->insert([
        //     'name' => 'Store one',
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Create Store
        // DB::connection('mysql')->table('rooms')->insert([
        //     'name' => 'Room one',
        //     'store_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Create Store
        // DB::connection('mysql')->table('racks')->insert([
        //     'name' => 'Rack one',
        //     'room_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Create Store
        // DB::connection('mysql')->table('shelves')->insert([
        //     'name' => 'Shelf one',
        //     'rack_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Create sizes, fetch all distinct sizes and create
        $this->info('Creating sizes data...');
        // $old_sizes = DB::connection('old_db')
        //     ->table('products')
        //     ->select('size')
        //     ->distinct()
        //     ->whereNotNull('size')
        //     ->where('size', '!=', '')
        //     ->get();

        // foreach ($old_sizes as $size) {
        //     DB::connection('mysql')->table('sizes')->insert([
        //         'name' => $size->size,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }


        // Create colors, fetch all distinct sizes and create
        $this->info('Creating colors data...');
        // $old_colors = DB::connection('old_db')
        //     ->table('products')
        //     ->select('color')
        //     ->distinct()
        //     ->whereNotNull('color')
        //     ->where('color', '!=', '')
        //     ->get();

        // foreach ($old_colors as $color) {
        //     DB::connection('mysql')->table('colors')->insert([
        //         'name' => $color->color,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Import products
        $this->info('Importing products data...');
        // $old_products = DB::connection('old_db')
        //     ->table('products')
        //     ->where('parent_id', 0)
        //     ->get();

        // foreach ($old_products as $product) {
        //     DB::connection('mysql')->table('products')->insert([
        //         'id' => $product->id,
        //         'name' => $product->name,
        //         'name_for_vendor' => $product->vendor_name,
        //         'category_id' => $product->category_id,
        //         'article_number' => $product->article_no,
        //         'image' => $product->image,
        //         'status' => $product->status,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // import variants
        // $old_variants = DB::connection('old_db')
        //     ->table('products')
        //     ->where('parent_id', '>', 0)
        //     ->get();

        // $all_products = DB::connection('mysql')
        //     ->table('products')
        //     ->get();
        
        // $all_colors = DB::connection('mysql')
        //     ->table('colors')
        //     ->get();
        
        // $all_sizes = DB::connection('mysql')
        //     ->table('sizes')
        //     ->get();


        // foreach ($old_variants as $product) {
        //     $new_product = $all_products->where('name', $product->name)->first();
        //     if(!$new_product) {
        //         dump('==========');
        //         dump($product);
        //         dump($new_product);
        //     }
        //     else {   
        //         $color = $all_colors->where('name', $product->color)->first();
        //         $size = $all_sizes->where('name', $product->size)->first();
        //         $variant_name = $this->generateName($new_product?->article_number, $size?->name, $color?->name, $product?->name);

        //         DB::connection('mysql')
        //             ->table('product_variants')
        //             ->insert([
        //                 'name' => $variant_name,
        //                 'product_id' => $new_product->id,
        //                 'color_id' => $color?->id,
        //                 'size_id' => $size?->id,
        //                 'vendor_price' => $product->price_vendor,
        //                 'customer_price' => $product->price,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //     }
        // }


        // Import Vendors
        $this->info('Importing vendors data...');
        // $old_vendors = DB::connection('old_db')
        //     ->table('vendors')
        //     ->select('vendors.*', 'countries.country_name')
        //     ->join('countries', 'vendors.country_id', '=', 'countries.id')
        //     ->get();

        // foreach ($old_vendors as $vendor) {
        //     DB::connection('mysql')->table('vendors')->insert([
        //         'first_name' => $vendor->first_name,
        //         'last_name' => $vendor->last_name,
        //         'full_name' => "{$vendor->first_name} {$vendor->last_name}",
        //         'email' => $vendor->email,
        //         'organization' => $vendor->organization,
        //         'phone' => $vendor->phone,
        //         'address' => $vendor->address,
        //         'city' => $vendor->city,
        //         'country' => $vendor->country_name,
        //         'currency' => $vendor->currency,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Importing customers data...');
        // $old_customers = DB::connection('old_db')
        //     ->table('customers')
        //     ->select('customers.*', 'countries.country_name')
        //     ->join('countries', 'customers.country_id', '=', 'countries.id')
        //     ->get();

        // foreach ($old_customers as $customer) {
        //     DB::connection('mysql')->table('customers')->insert([
        //         'id' => $customer->id,
        //         'first_name' => $customer->first_name,
        //         'last_name' => $customer->last_name,
        //         'full_name' => "{$customer->first_name} {$customer->last_name}",
        //         'email' => $customer->email,
        //         'organization' => $customer->organization,
        //         'phone' => $customer->phone,
        //         'address' => $customer->address,
        //         'city' => $customer->city,
        //         'country' => $customer->country_name,
        //         'currency' => $customer->currency,
        //         'status' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Importing expense categories
        // $this->info('Importing expense categories...');
        // $old_expense_categories = DB::connection('old_db')
        //     ->table('expenses_categories')
        //     ->get();

        // foreach ($old_expense_categories as $expense_category) {
        //     DB::connection('mysql')->table('expense_categories')->insert([
        //         'id' => $expense_category->id,
        //         'name' => $expense_category->name,
        //         'status' => 1
        //     ]);
        // }

        // Importing expe3nses
        $this->info('Importing expenses...');
        // $old_expenses = DB::connection('old_db')
        //     ->table('expenses')
        //     ->get();

        // foreach ($old_expenses as $expense) {
        //     DB::connection('mysql')->table('expenses')->insert([
        //         'id' => $expense->id,
        //         'expense_category_id' => $expense->exp_id,
        //         'date' => $expense->date,
        //         'description' => $expense->description,
        //         'amount' => $expense->amount,
        //         'expense_by' => 'a',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Importing letterheads...');
        // $letterHeads = DB::connection('old_db')
        //     ->table('letterheads')
        //     ->get();

        // foreach ($letterHeads as $letter) {
        //     DB::connection('mysql')->table('letter_heads')->insert([
        //         'date' => $letter->date,
        //         'ref_no' => $letter->ref,
        //         'title' => $letter->title,
        //         'content' => $letter->content,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Importing Packaging lists...');
        // $packaging_lists = DB::connection('old_db')
        //     ->table('packing_lists')
        //     ->get();

        // foreach ($packaging_lists as $list) {
        //     $country_of_origin = DB::connection('old_db')->table('countries')->where('country_code', $list->country_from)->first();
        //     $port_of_discharge = DB::connection('old_db')->table('countries')->where('country_code', $list->country)->first();
        //     $customer = DB::connection('mysql')->table('customers')->where('email', $list->email)->first();
        //     DB::connection('mysql')->table('packaging_lists')->insert([
        //         'id' => $list->list_id,
        //         'date' => $list->date,
        //         'invoice_no' => $list->invoice_no,
        //         'invoice_date' => $list->invoice_date,
        //         'e_form_no' => $list->eform_no,
        //         'country_of_origin' => $country_of_origin?->country_name,
        //         'port_of_discharge' => $port_of_discharge?->country_name,
        //         'customer_id' => $customer?->id,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $packaging_list_items = DB::connection('old_db')
        //     ->table('packing_lists_items')
        //     ->get();

        // foreach ($packaging_list_items as $list) {
        //     DB::connection('mysql')->table('packaging_boxes')->insert([
        //         'id' => $list->item_id,
        //         'packaging_list_id' => $list->list_id,
        //         'cartons' => $list->cartons,
        //         'qty_cartons' => $list->qty_per_carton,
        //         'article_no' => $list->article_no,
        //         'details' => $list->details,
        //         'size_qty' => $list->size_qty,
        //         'total_qty' => $list->cartons * $list->qty_per_carton,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Importing courier receipts...');
        // $airway_bills = DB::connection('old_db')
        //     ->table('airway_bills')
        //     ->get();

        // foreach ($airway_bills as $item) {
        //     DB::connection('mysql')->table('courier_receipts')->insert([
        //         'date' => $item->date,
        //         'airway_bill_number' => $item->serial,
        //         'destination_code' => $item->destination,
        //         'origin_code' => $item->origin_code,
        //         'shipper_account_number' => $item->sh_account,
        //         'shipper_credit_card' => $item->sh_credit_chq,
        //         'shipper_name' => $item->sh_name,
        //         'shipper_address' => $item->sh_address,
        //         'shipper_city' => $item->sh_city,
        //         'shipper_zip' => $item->sh_zip,
        //         'shipper_country' => $item->sh_country,
        //         'shipper_phone' => $item->sh_phone,
        //         'shipper_department' => $item->sh_department,
        //         'receiver_company_name' => $item->rc_company,
        //         'receiver_attention_to' => $item->rc_department,
        //         'receiver_address' => $item->rc_address,
        //         'receiver_city' => $item->rc_city,
        //         'receiver_state' => $item->rc_state,
        //         'receiver_country' => $item->rc_country,
        //         'receiver_zip' => $item->rc_zip,
        //         'receiver_phone' => $item->rc_phone,
        //         'items' => $item->items,
        //         'kilos' => $item->kilos,
        //         'type' => $item->type,
        //         'extra_information' => $item->extra_details,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        $this->info('Importing orders...');
        $orders = DB::connection('old_orders')
            ->table('orders')
            ->get();

        foreach ($orders as $item) {
            DB::connection('mysql')->table('orders')->insert([
                // 'id' => $item->id,
                'order_date' => $item->order_date,
                'email_date' => $item->email_date,
                'delivery_date' => $item->delivery_date,
                'order_name' => $item->name,
                'customer_id' => $item->customer_id,
                'invoice_number' => null,
                'status' => $item->status,
                'currency' => $item->currency,
                'order_amount' => $item->order_amount,
                'damage_amount' => $item->damage_amount,
                'grand_total' => $item->order_amount - $item->damage_amount,
                'paid_amount' => $item->paid_amount,
                'balance' => ($item->order_amount - $item->damage_amount) - $item->paid_amount,
                'order_file_admin' => $item->file_admin,
                'order_file_manager' => $item->file_manager,
                'total_boxes' => (isset($item->boxes) && !empty($item->boxes)) ? $item->boxes : 0,
                'boxes_details' => (isset($item->boxes) && !empty($item->boxes)) ? $item->boxes_details : null,
                'shipping_carrier' => $item->shipping_carrier,
                'tracking_number' => $item->tracking_number,
                'airway_bill_number' => $item->airway_bill_no,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }

    private function generateName($articleNumber, $size, $color, $productName)
    {
        return "{$articleNumber} - {$size} - {$color} - {$productName}";
    }
}
