<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PackagingList;
use App\Models\LetterHead;
use App\Models\SaleInvoice;
use App\Models\Salary;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Crypt;

class PrintDocsController extends Controller
{
    public function printPO($record) {
        $purchaseOrder = PurchaseOrder::with('vendor', 'items')->findOrFail($record);
        return view('pdf.print-po', compact('purchaseOrder'));
    }

    public function printPONoPrice($record) {
        $purchaseOrder = PurchaseOrder::with('vendor', 'items')->findOrFail($record);
        return view('pdf.print-po-no-price', compact('purchaseOrder'));
    }

    public function print_letter_head_with_logo($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_with_logo', compact('letterHead'));
    }

    public function print_letter_head_without_logo($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_without_logo', compact('letterHead'));
    }

    public function print_letter_head_without_stamp($record) {
        $letterHead = LetterHead::findOrFail($record);
        return view('pdf.print_letter_head_without_stamp', compact('letterHead'));
    }
    
    public function print_packaging_list_with_logo($record) {
        $packagingList = PackagingList::findOrFail($record);
        return view('pdf.print_packaging_list_with_logo', compact('packagingList'));
    }

    public function print_packaging_list_with_stamp($record) {
        $packagingList = PackagingList::findOrFail($record);
        return view('pdf.print_packaging_list_with_stamp', compact('packagingList'));
    }

    public function share_packaging_list($record) {
        $decodedId = Crypt::decrypt($record);
        $packagingList = PackagingList::findOrFail($decodedId);
        return view('pdf.share_packaging_list', compact('packagingList'));
    }

    public function print_sale_invoice($record) {
        $saleInvoice = SaleInvoice::findOrFail($record);
        return view('pdf.print_sale_invoice', compact('saleInvoice'));
    }

    public function print_sale_invoice_with_stamp($record) {
        $saleInvoice = SaleInvoice::findOrFail($record);
        return view('pdf.print_sale_invoice_with_stamp', compact('saleInvoice'));
    }

    public function print_salary($employee_id, $month, $year)
    {
        // Fetch employee details
        $employee = Employee::findOrFail($employee_id);

        // Fetch salary details
        $salaries = Salary::where('employee_id', $employee_id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        // Fetch attendance details
        $attendances = Attendance::where('employee_id', $employee_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // Map attendance data (including overtime_hours)
        $attendanceStatus = $attendances->map(function ($attendance) {
            // Ensure $attendance->date is a Carbon instance
            $attendanceDate = $attendance->date instanceof \Carbon\Carbon
                ? $attendance->date
                : \Carbon\Carbon::parse($attendance->date);

            return [
                'date' => $attendanceDate->format('Y-m-d'),
                'status' => $attendance->status,
                // Include this line if your 'attendance' table has an 'overtime_hours' column
                'overtime_hours' => $attendance->overtime_hours ?? 0,
                'overtime_minutes' => $attendance->overtime_minutes ?? 0,
                'hours_worked' => $attendance->hours_worked ?? 0,
                'minutes_worked' => $attendance->minutes_worked ?? 0,
            ];
        });
        // Prepare data for the view
        $data = [
            'employee'           => $employee,
            'salaries'           => $salaries,
            'attendance_status'  => $attendanceStatus,
        ];

        // Return the view
        return view('pdf.salary-sheet', $data);
    }


}
