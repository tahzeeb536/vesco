<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // $this->info('Moving categories data...');
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

        // $this->info('Creating store, rooms. racks and shelves...');
        // DB::connection('mysql')->table('stores')->insert([
        //     'name' => 'Store one',
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::connection('mysql')->table('rooms')->insert([
        //     'name' => 'Room one',
        //     'store_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::connection('mysql')->table('racks')->insert([
        //     'name' => 'Rack one',
        //     'room_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // DB::connection('mysql')->table('shelves')->insert([
        //     'name' => 'Shelf one',
        //     'rack_id' => 1,
        //     'status' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // $this->info('Creating sizes data...');
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


        // $this->info('Creating colors data...');
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

        // $this->info('Importing products data...');
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

        // $this->info('Importing product variants');
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
        //         continue;
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


        // $this->info('Importing vendors data...');
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

        // $this->info('Importing customers data...');
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

        // DB::connection('mysql')->table('expense_categories')->insert([
        //     'id' => 1,
        //     'name' => 'General',
        //     'status' => 1
        // ]);

        // $this->info('Importing expenses...');
        // $old_expenses = DB::connection('old_db')
        //     ->table('expenses')
        //     ->get();

        // foreach ($old_expenses as $expense) {
        //     $expense_cat = DB::connection('old_db')->table('expenses_categories')->find($expense->exp_id);
        //     if($expense->id) {
        //         $expense_cat_id = 1;
        //     }
        //     else {
        //         $expense_cat_id = $expense->exp_id;
        //     }
        //     DB::connection('mysql')->table('expenses')->insert([
        //         'id' => $expense->id,
        //         'expense_category_id' => $expense_cat_id,
        //         'date' => $expense->date,
        //         'description' => $expense->description,
        //         'amount' => $expense->amount,
        //         'expense_by' => 'a',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $this->info('Importing letterheads...');
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

        // $this->info('Importing Packaging lists...');
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

        // $this->info('Importing packaging_boxes...');
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

        // $this->info('Importing courier receipts...');
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

        // $this->info('Importing orders...');
        // $orders = DB::connection('old_orders')
        //     ->table('orders')
        //     ->get();

        // foreach ($orders as $item) {
        //     DB::connection('mysql')->table('orders')->insert([
        //         // 'id' => $item->id,
        //         'order_date' => $item->order_date,
        //         'email_date' => $item->email_date,
        //         'delivery_date' => $item->delivery_date,
        //         'order_name' => $item->name,
        //         'customer_id' => $item->customer_id,
        //         'invoice_number' => null,
        //         'status' => $item->status,
        //         'currency' => $item->currency,
        //         'order_amount' => $item->order_amount,
        //         'damage_amount' => $item->damage_amount,
        //         'grand_total' => $item->order_amount - $item->damage_amount,
        //         'paid_amount' => $item->paid_amount,
        //         'balance' => ($item->order_amount - $item->damage_amount) - $item->paid_amount,
        //         'order_file_admin' => $item->file_admin,
        //         'order_file_manager' => $item->file_manager,
        //         'total_boxes' => (isset($item->boxes) && !empty($item->boxes)) ? $item->boxes : 0,
        //         'boxes_details' => (isset($item->boxes) && !empty($item->boxes)) ? $item->boxes_details : null,
        //         'shipping_carrier' => $item->shipping_carrier,
        //         'tracking_number' => $item->tracking_number,
        //         'airway_bill_number' => $item->airway_bill_no,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $this->info('Importing invoices...');
        // $invoices = DB::connection('old_db')
        //     ->table('invoice')
        //     ->get();

        // foreach ($invoices as $item) {
            
        //     $customer = DB::connection('mysql')->table('customers')->find($item->customer_id);
            
        //     if(!$customer) {
        //         dump($item);
        //         continue;
        //     }
        //     DB::connection('mysql')->table('sale_invoices')->insert([
        //         'id' => $item->id,
        //         'customer_id' => $item->customer_id,
        //         'invoice_number' => $item->invoice_no,
        //         'invoice_date' => $item->date,
        //         'ntn' => $item->ntn_no,
        //         'financial_instrument_no' => $item->eform_no,
        //         'bank_name' => $item->bank,
        //         'shipping' => null,
        //         'port_of_loading' => null,
        //         'port_of_discharge' => null,
        //         'term' => $item->term,
        //         'hs_code' => $item->hs_code,
        //         'po_no' => $item->po_no,
        //         'freight_charges' => $item->frieght_charges,
        //         'tax_charges' => $item->tax_charges,
        //         'total_amount' => null,
        //         'paid_amount' => null,
        //         'pending_amount' => null,
        //         'note' => null,
        //         'status' => null,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }


        // $this->info('Importing invoice items...');
        // $invoice_items = DB::connection('old_db')
        //     ->table('invoice_items')
        //     ->get();

        // foreach ($invoice_items as $item) {
            
        //     $total_price = $item->qty * $item->rate;
        //     if ($item->discount > 0) {
        //         $total_price -= ($total_price * ($item->discount / 100));
        //     }
        //     $total_price = round($total_price, 2);

        //     DB::connection('mysql')->table('sale_invoice_items')->insert([
        //         'id' => $item->id,
        //         'sale_invoice_id' => $item->inv_id,
        //         'variant_id' => null,
        //         'article_number' => $item->article_no,
        //         'size' => $item->size,
        //         'color' => $item->color,
        //         'quantity' => $item->qty,
        //         'discount' => $item->discount,
        //         'unit_price' => $item->rate,
        //         'total_price' => $total_price,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $this->info('Importing invoice with IDs...');
        // $invoice_items = DB::connection('old_db')
        //     ->table('invoice_items')
        //     ->get();

        // foreach ($invoice_items as $item) {

        //     $item_id = null;
        //     $variant_id = null;

        //     $variant = DB::connection('mysql')
        //         ->table('product_variants')
        //         ->where('name', 'like', '%' . $item->article_no . '%')
        //         ->where('name', 'like', '%' . $item->size . '%')
        //         ->where('name', 'like', '%' . $item->color . '%')
        //         ->first();

        //     if($variant) {
        //         $item_id = $variant->id;
        //         $product = DB::connection('mysql')
        //             ->table('products')
        //             ->where('id', $variant->product_id)
        //             ->first();
        //         $product_name = $product->name;
        //     }
        //     else {
        //         $v = DB::connection('mysql')
        //             ->table('product_variants')
        //             ->where('name', 'like', '%'.$item->article_no.'%')
        //             ->first();

        //         if($v) {
        //             $item_id = $v->id;
                    
        //             $product = DB::connection('mysql')
        //                 ->table('products')
        //                 ->where('id', $v->product_id)
        //                 ->first();
        //             $product_name = $product->name;
        //         }
        //     }
            
        //     $total_price = $item->qty * $item->rate;
        //     if ($item->discount > 0) {
        //         $total_price -= ($total_price * ($item->discount / 100));
        //     }
        //     $total_price = round($total_price, 2);

        //     DB::connection('mysql')->table('sale_invoice_items')->insert([
        //         'id' => $item->id,
        //         'sale_invoice_id' => $item->inv_id,
        //         'variant_id' => $item_id,
        //         'product_name' => $product_name,
        //         'article_number' => $item->article_no,
        //         'size' => $item->size,
        //         'color' => $item->color,
        //         'quantity' => $item->qty,
        //         'discount' => $item->discount,
        //         'unit_price' => $item->rate,
        //         'total_price' => $total_price,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $this->info('Save sale invoice total amount');
        // $sale_invoices = \App\Models\SaleInvoice::all();

        // foreach ($sale_invoices as $invoice) {
        //     $invoice->updateTotalAmount();
        // }

        // $this->info('Import sale invoice payments');
        // $old_inv_payments = DB::connection('old_db')
        //     ->table('invoice_payments')
        //     ->get();

        // foreach ($old_inv_payments as $payment) {
        //     DB::connection('mysql')
        //         ->table('sale_invoice_payments')
        //         ->insert([
        //             'id' => $payment->id,
        //             'date' => Carbon::parse($payment->date)->format('Y-m-d'),
        //             'sale_invoice_id' => $payment->inv_id,
        //             'amount' => $payment->amount,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        // }


        // $this->info('Update sale invoice paid and pending amounts');
        // $sale_invoices = \App\Models\SaleInvoice::with('payments')->get();

        // foreach ($sale_invoices as $invoice) {
        //     $totalPaid = $invoice->payments->sum('amount');
        //     $pendingAmount = $invoice->total_amount - $totalPaid;
    
        //     $status = match (true) {
        //         $pendingAmount <= 0 => 'paid',
        //         $totalPaid > 0 => 'partially_paid',
        //         default => 'pending',
        //     };
    
        //     $invoice->update([
        //         'paid_amount' => $totalPaid,
        //         'pending_amount' => $pendingAmount,
        //         'status' => $status,
        //     ]);
        // }



        // $this->info('Importing employees....');
        // $old_employees = DB::connection('old_db')
        //     ->table('employees')
        //     ->get();

        // foreach($old_employees as $employee) {
        //     DB::connection('mysql')
        //         ->table('employees')
        //         ->insert([
        //             'id' => $employee->id,
        //             'name' => $employee->name,
        //             'name_urdu' => $employee->urdu_name,
        //             'father_name' => $employee->father_name,
        //             'dob' => $employee->dob,
        //             'cnic' => $employee->cnic,
        //             'photo' => $employee->photo,
        //             'phone' => $employee->phone,
        //             'address' => $employee->address,
        //             'type' => 'Salary Based',
        //             'basic_salary' => $employee->salary,
        //             'home_allowance' => $employee->home_allowance,
        //             'medical_allowance' => $employee->medical_allowance,
        //             'mobile_allowance' => $employee->mobile_allowance,
        //             'status' => $employee->status,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        // }

        

        // $this->info('Importing attendance data....');
        // $old_attendance = DB::connection('old_db')
        //     ->table('employees_attendance')
        //     ->get();

        // foreach($old_attendance as $attendance) {

        //     $employee_exists = DB::connection('mysql')->table('employees')->where('id', $attendance->emp_id)->first();

        //     if($employee_exists) {
        //         $status = $attendance->status;
        //         if($status == 'P') {
        //             $status = 'Present';
        //         }
        //         elseif($status == 'A') {
        //             $status = 'Absent';
        //         }
        //         else {
        //             $status = 'Leave';
        //         }

        //         $h = $attendance->hours;
        //         $hours = (int) floor($h);
        //         $minutes = (int) round(($h - $hours) * 60);

        //         $ot = $attendance->overtime;
        //         $ot_h = (int) floor($ot);
        //         $ot_m = (int) round(($ot - $ot_h) * 60);

        //         $in_time = trim($attendance->in_time);
        //         $out_time = trim($attendance->out_time);
                
        //         if (!empty($in_time) && !empty($out_time)) {
        //             $clock_in = \Carbon\Carbon::createFromFormat('g:i A', $in_time)->format('H:i:s');
        //             $clock_out = \Carbon\Carbon::createFromFormat('g:i A', $out_time)->format('H:i:s');
        //         }
        //         else {
        //             $clock_in = null;
        //             $clock_out = null;
        //         }

        //         DB::connection('mysql')
        //         ->table('attendances')
        //         ->insert([
        //             'id' => $attendance->id,
        //             'employee_id' => $attendance->emp_id,
        //             'date' => $attendance->date,
        //             'status' => $status,
        //             'clock_in' => $clock_in,
        //             'clock_out' => $clock_out,
        //             'hours_worked' => $hours,
        //             'minutes_worked' => $minutes,
        //             'overtime_hours' => $ot_h,
        //             'overtime_minutes' => $ot_m,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
            
        // }

        // $this->info('Importing loans data....');
        // $old_loans = DB::connection('old_db')
        //     ->table('employees_loans')
        //     ->get();

        // foreach($old_loans as $loan) {

        //     $employee_exists = DB::connection('mysql')->table('employees')->where('id', $loan->employee_id)->first();

        //     if($employee_exists) {
        //         DB::connection('mysql')
        //         ->table('advance_salaries')
        //         ->insert([
        //             'id' => $loan->id,
        //             'employee_id' => $loan->employee_id,
        //             'amount' => $loan->amount,
        //             'advance_date' => Carbon::parse($loan->datetime)->format('Y-m-d'),
        //             'name' => $loan->name,
        //             'remarks' => $loan->details,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
            
            
        // }

        // $this->info('Importing temp advance data....');
        // $old_temp_advance = DB::connection('old_db')
        //     ->table('employees_advance')
        //     ->get();

        // foreach($old_temp_advance as $advance) {

        //     $employee_exists = DB::connection('mysql')->table('employees')->where('id', $advance->emp_id)->first();

        //     if($employee_exists) {
        //         DB::connection('mysql')
        //         ->table('temp_loans')
        //         ->insert([
        //             'id' => $advance->adv_id,
        //             'employee_id' => $advance->emp_id,
        //             'date' => Carbon::parse($advance->date)->format('Y-m-d'),
        //             'details' => $advance->details,
        //             'amount' => $advance->amount,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }    
        // }

        // $this->info('Importing employee account statement data....');
        // $old_statement = DB::connection('old_db')
        //     ->table('employees_statement')
        //     ->get();

        // foreach($old_statement as $statement) {

        //     $employee_exists = DB::connection('mysql')->table('employees')->where('id', $statement->employee_id)->first();
            
        //     if($employee_exists) {

        //         $year = null;
        //         $month = null;
        //         if (!empty($statement->month)) {
        //             list($year, $month) = explode('-', $statement->month);
        //         }

        //         DB::connection('mysql')
        //         ->table('employee_statements')
        //         ->insert([
        //             'id' => $statement->id,
        //             'employee_id' => $statement->employee_id,
        //             'datetime' => Carbon::parse($statement->datetime)->format('Y-m-d H:i:s'),
        //             'deposit' => $statement->deposit,
        //             'withdraw' => $statement->withdraw,
        //             'type' => $statement->type,
        //             'year' => $year,
        //             'month' => $month,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }    
        // }

        // $this->info('Importing salaries data....');
        // $salaries = DB::connection('old_db')
        //     ->table('salary_sheets')
        //     ->get();

        // foreach($salaries as $salary) {

        //     $employee_exists = DB::connection('mysql')->table('employees')->where('id', $salary->emp_id)->first();
            
        //     if($employee_exists) {

        //         $year = null;
        //         $month = null;
        //         if (!empty($salary->month)) {
        //             list($year, $month) = explode('-', $salary->month);
        //         }

        //         DB::connection('mysql')
        //         ->table('salaries')
        //         ->insert([
        //             'id' => $salary->id,
        //             'employee_id' => $salary->emp_id,
        //             'month' => $month,
        //             'year' => $year,
        //             'total_present_days' => 0,
        //             'total_hours' => 0,
        //             'total_minutes' => 0,
        //             'total_overtime_hours' => 0,
        //             'total_overtime_minutes' => 0,
        //             'basic_salary' => round($salary->basic_salary),
        //             'overtime' => 0,
        //             'deduction' => $salary->monthly_deduction + $salary->advance,
        //             'loan_deduction' => $salary->monthly_deduction,
        //             'temp_deduction' => $salary->advance,
        //             'net_salary' => round($salary->final_amount),
        //             'late_hours' => 0,
        //             'home_allowance' => $salary->home_allowance,
        //             'medical_allowance' => $salary->medical_allowance,
        //             'mobile_allowance' => $salary->mobile_allowance,
        //             'status' => 1,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }    
        // }


    }

    private function generateName($articleNumber, $size, $color, $productName)
    {
        return "{$articleNumber} - {$size} - {$color} - {$productName}";
    }
}
