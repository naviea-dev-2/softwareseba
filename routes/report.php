<?php
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
//Inventory
Route::get('/purchase-report','App\Http\Controllers\Report\InventoryReport@purchase')->name('report.purchase');

Route::get('/sale-discount-report','App\Http\Controllers\Report\InventoryReport@saleDiscount')->name('report.sale_discount');
Route::get('/invoice-report','App\Http\Controllers\Report\InventoryReport@invoice')->name('report.invoice');
Route::get('/pos-sale-report','App\Http\Controllers\Report\InventoryReport@posSale')->name('report.pos_sale');

Route::get('/purchase-return-report','App\Http\Controllers\Report\InventoryReport@purchaseReturn')->name('report.purchase_return');

Route::get('/invoice-return-report','App\Http\Controllers\Report\InventoryReport@invoiceReturn')->name('report.sales_return');

Route::get('/stock-report','App\Http\Controllers\Report\InventoryReport@stock')->name('report.stock');
Route::get('/product-wise-profit-report','App\Http\Controllers\Report\InventoryReport@productWiseProfit')->name('report.product_wise_profit');
Route::get('/sale-wise-profit-report','App\Http\Controllers\Report\InventoryReport@saleWiseProfit')->name('report.sale_wise_profit');
Route::get('/product-expire-report','App\Http\Controllers\Report\InventoryReport@productExpire')->name('report.product_expire');
Route::get('/damage-product-report','App\Http\Controllers\Report\InventoryReport@damageProduct')->name('report.damage_product_report');
Route::get('/damage-product-stock','App\Http\Controllers\Report\InventoryReport@damageProductStock')->name('report.damage_product_stock');

//account report
Route::get("expense",[App\Http\Controllers\Account\TransactionController::class,'expense'])->name("account.report.expense");
Route::get('/vendor-due-report','App\Http\Controllers\Report\InventoryReport@vendorDue')->name('report.vendor_due');

Route::get('/customer-due-report','App\Http\Controllers\Report\InventoryReport@customerDue')->name('report.customer_due');

//hr Reports
Route::get('/attendance-report','App\Http\Controllers\Report\HrReportController@attendance')->name('report.attendance');
Route::get('/salary_sheet-report','App\Http\Controllers\Report\HrReportController@salarySheet')->name('report.salary_sheet');
Route::get('/emp-leave-report','App\Http\Controllers\Report\HrReportController@empLeave')->name('report.emp_leave');
Route::get('/emp-loan-report','App\Http\Controllers\Report\HrReportController@empLoan')->name('report.emp_loan');
Route::get('/emp-bonus-report','App\Http\Controllers\Report\HrReportController@empBonus')->name('report.emp_bonus');

//end hr report

//
});
//print

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    Route::get('/report/purchase_print','App\Http\Controllers\Report\PrintReport@purchaseReport')->name('purchase_report_print');
    Route::get('/report/purchase_return_print','App\Http\Controllers\Report\PrintReport@purchaseReutnReport')->name('purchase_return_report_print');
    Route::get('/report/sales_discount_print','App\Http\Controllers\Report\PrintReport@saleDiscount')->name('sales_discount_report_print');
    Route::get('/report/invoice_print','App\Http\Controllers\Report\PrintReport@invoiceReport')->name('invoice_report_print');
    Route::get('/report/pos_sale_print','App\Http\Controllers\Report\PrintReport@posSaleReport')->name('pos_sale_report_print');
    Route::get('/report/invoice_return_print','App\Http\Controllers\Report\PrintReport@invoiceReturnReport')->name('invoice_return_report_print');
    Route::get('/report/stock_print','App\Http\Controllers\Report\PrintReport@stockReport')->name('stock_report_print');
    Route::get('/report/product-wise-profit','App\Http\Controllers\Report\PrintReport@productWiseProfit')->name('product_wise_profit_report_print');
    Route::get('/report/sale-wise-profit','App\Http\Controllers\Report\PrintReport@saleWiseProfit')->name('sale_wise_profit_report_print');
    Route::get('/report/product-expire-print','App\Http\Controllers\Report\PrintReport@productExpire')->name('product_expire_report_print');
    Route::get('/report/damage-report-print','App\Http\Controllers\Report\PrintReport@damageProductReport')->name('damage_product_report_print');
    Route::get('/report/damage-stock-print','App\Http\Controllers\Report\PrintReport@damageProductStockReport')->name('damage_product_stock_print');
    
    //accounting
    Route::get('/balance_sheet_print','App\Http\Controllers\Report\PrintReport@balanceSheet')->name('balance_sheet_print');
    Route::get('/trail_balance_print','App\Http\Controllers\Report\PrintReport@trailBalance')->name('trail_balance_print');
    Route::get('/prof_loss_print','App\Http\Controllers\Report\PrintReport@profLoss')->name('prof_loss_print');
    Route::get('/ledger_summary_print','App\Http\Controllers\Report\PrintReport@ledgerSummary')->name('ledger_summary_print');
    Route::get('/transaction_print','App\Http\Controllers\Report\PrintReport@transaction')->name('transaction_print');

    Route::get('/vendor-due-print','App\Http\Controllers\Report\PrintReport@vendorDue')->name('vendor_due_print');

    Route::get('/customer-due-print','App\Http\Controllers\Report\PrintReport@customerDue')->name('customer_due_print');
    Route::get('/expense-report-print','App\Http\Controllers\Report\PrintReport@expenseReport')->name('expense_report_print');

    //Hr
   Route::get('/attendance_print','App\Http\Controllers\Report\PrintReport@attendance')->name('report_attendance_print');
    Route::get('/salary_sheet_print','App\Http\Controllers\Report\PrintReport@salarySheet')->name('report_salary_sheet_print');
    Route::get('/emp-leave-print','App\Http\Controllers\Report\PrintReport@empLeave')->name('report_emp_leave_print');
    Route::get('/emp-loan-print','App\Http\Controllers\Report\PrintReport@empLoan')->name('report_emp_loan_print');
    Route::get('/emp-bonus-print','App\Http\Controllers\Report\PrintReport@empBonus')->name('report_emp_bonus_print');

});

//excel

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    Route::get('/report/purchase_excel','App\Http\Controllers\Report\ReportExportController@purchaseReport')->name('purchase_report_excel');
    Route::get('/report/purchase_return_excel','App\Http\Controllers\Report\ReportExportController@purchaseReutnReport')->name('purchase_return_report_excel');
    Route::get('/report/pos_sale_excel','App\Http\Controllers\Report\ReportExportController@posSaleReport')->name('pos_sale_report_excel');
    Route::get('/report/invoice_excel','App\Http\Controllers\Report\ReportExportController@invoiceReport')->name('invoice_report_excel');
    Route::get('/report/sale_discount_excel','App\Http\Controllers\Report\ReportExportController@saleDiscount')->name('sale_discount_report_excel');
    Route::get('/report/invoice_return_excel','App\Http\Controllers\Report\ReportExportController@invoiceReturnReport')->name('invoice_return_report_excel');
    Route::get('/report/stock_excel','App\Http\Controllers\Report\ReportExportController@stockReport')->name('stock_report_excel');
    Route::get('/report/product_wise_profit_excel','App\Http\Controllers\Report\ReportExportController@productWiseProfit')->name('product_wise_profit_report_excel');
    Route::get('/report/sale_wise_profit_excel','App\Http\Controllers\Report\ReportExportController@saleWiseProfit')->name('sale_wise_profit_report_excel');
    Route::get('/report/product-expire-excel','App\Http\Controllers\Report\ReportExportController@productExpire')->name('product_expire_report_excel');
    Route::get('/report/damage-product-report-excel','App\Http\Controllers\Report\ReportExportController@damageProductReport')->name('damage_product_report_excel');
    Route::get('/report/damage-product-stock-print','App\Http\Controllers\Report\ReportExportController@damageProductStock')->name('damage_product_stock_excel');
    
     //accounting
    Route::get('/balance_sheet_excel','App\Http\Controllers\Report\ReportExportController@balanceSheet')->name('balance_sheet_excel');
    Route::get('/trail_balance_excel','App\Http\Controllers\Report\ReportExportController@trailBalance')->name('trail_balance_excel');
    Route::get('/prof_loss_excel','App\Http\Controllers\Report\ReportExportController@profLoss')->name('prof_loss_excel');
    Route::get('/ledger_summary_excel','App\Http\Controllers\Report\ReportExportController@ledgerSummary')->name('ledger_summary_excel');
    Route::get('/transaction_excel','App\Http\Controllers\Report\ReportExportController@transaction')->name('transaction_excel');
    Route::get('/test_excel','App\Http\Controllers\Report\ReportExportController@test_excel')->name('test_excel');

    Route::get('/vendor-due-excel','App\Http\Controllers\Report\ReportExportController@vendorDue')->name('vendor_due_excel');

    Route::get('/customer-due-excel','App\Http\Controllers\Report\ReportExportController@customerDue')->name('customer_due_excel');
    Route::get('/expense-report-excel','App\Http\Controllers\Report\ReportExportController@expenseReport')->name('expense_report_excel');
     //Hr
    Route::get('/attendance_excel','App\Http\Controllers\Report\ReportExportController@attendance')->name('report_attendance_excel');
    Route::get('/salary_sheet_excel','App\Http\Controllers\Report\ReportExportController@salarySheet')->name('report_salary_sheet_excel');
    Route::get('/emp-leave-excel','App\Http\Controllers\Report\ReportExportController@empLeave')->name('report_emp_leave_excel');
    Route::get('/emp-loan-excel','App\Http\Controllers\Report\ReportExportController@empLoan')->name('report_emp_loan_excel');
    Route::get('/emp-bonus-excel','App\Http\Controllers\Report\ReportExportController@empBonus')->name('report_emp_bonus_excel');
});
//pdf
Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    Route::get('/report/purchase_pdf','App\Http\Controllers\Report\PdfExportController@purchaseReport')->name('purchase_report_pdf');
    Route::get('/report/purchase_return_pdf','App\Http\Controllers\Report\PdfExportController@purchaseReutnReport')->name('purchase_return_report_pdf');
    Route::get('/report/pos_sale_pdf','App\Http\Controllers\Report\PdfExportController@posSaleReport')->name('pos_sale_report_pdf');
    Route::get('/report/sales_discount_pdf','App\Http\Controllers\Report\PdfExportController@saleDiscount')->name('sale_discount_report_pdf');
    Route::get('/report/invoice_pdf','App\Http\Controllers\Report\PdfExportController@invoiceReport')->name('invoice_report_pdf');
    Route::get('/report/invoice_return_pdf','App\Http\Controllers\Report\PdfExportController@invoiceReturnReport')->name('invoice_return_report_pdf');
    Route::get('/report/stock_pdf','App\Http\Controllers\Report\PdfExportController@stockReport')->name('stock_report_pdf');
    Route::get('/report/product_wise_profit_pdf','App\Http\Controllers\Report\PdfExportController@productWiseProfit')->name('product_wise_profit_report_pdf');
    Route::get('/report/sale_wise_profit_pdf','App\Http\Controllers\Report\PdfExportController@saleWiseProfit')->name('sale_wise_profit_report_pdf');
    Route::get('/report/product-expire-pdf','App\Http\Controllers\Report\PdfExportController@productExpire')->name('product_expire_report_pdf');
    Route::get('/report/damage-product-report-pdf','App\Http\Controllers\Report\PdfExportController@damageProductReport')->name('damage_product_report_pdf');
    Route::get('/report/damage-product-stock-pdf','App\Http\Controllers\Report\PdfExportController@damageProductStock')->name('damage_product_stock_pdf');
    
    //accounting
    Route::get('/balance_sheet_pdf','App\Http\Controllers\Report\PdfExportController@balanceSheet')->name('balance_sheet_pdf');
    Route::get('/trail_balance_pdf','App\Http\Controllers\Report\PdfExportController@trailBalance')->name('trail_balance_pdf');
    Route::get('/prof_loss_pdf','App\Http\Controllers\Report\PdfExportController@profLoss')->name('prof_loss_pdf');
    Route::get('/ledger_summary_pdf','App\Http\Controllers\Report\PdfExportController@ledgerSummary')->name('ledger_summary_pdf');
    Route::get('/transaction_pdf','App\Http\Controllers\Report\PdfExportController@transaction')->name('transaction_pdf');

    Route::get('/vendor-due-pdf','App\Http\Controllers\Report\PdfExportController@vendorDue')->name('vendor_due_pdf');

    Route::get('/customer-due-pdf','App\Http\Controllers\Report\PdfExportController@customerDue')->name('customer_due_pdf');
    Route::get('/expense-report-pdf','App\Http\Controllers\Report\PdfExportController@expenseReport')->name('expense_report_pdf');

     //Hr
    Route::get('/attendance_pdf','App\Http\Controllers\Report\PdfExportController@attendance')->name('report_attendance_pdf');
    Route::get('/salary_sheet_pdf','App\Http\Controllers\Report\PdfExportController@salarySheet')->name('report_salary_sheet_pdf');
    Route::get('/emp-leave-pdf','App\Http\Controllers\Report\PdfExportController@empLeave')->name('report_emp_leave_pdf');
    Route::get('/emp-loan-pdf','App\Http\Controllers\Report\PdfExportController@empLoan')->name('report_emp_loan_pdf');
    Route::get('/emp-bonus-pdf','App\Http\Controllers\Report\PdfExportController@empBonus')->name('report_emp_bonus_pdf');
});
