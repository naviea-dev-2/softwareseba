<?php
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    //report

    Route::get("balance-sheet",[App\Http\Controllers\Account\TransactionController::class,'balanceSheet'])->name("balance_sheet");
    Route::get("trail-balance",[App\Http\Controllers\Account\TransactionController::class,'Trailbalance'])->name("trail_balance");
    Route::get("ledger-summary",[App\Http\Controllers\Account\TransactionController::class,'ledgerSummary'])->name("ledger_summary");
    Route::get("profit-and-loss",[App\Http\Controllers\Account\TransactionController::class,'profitLoss'])->name("profit_loss");
    //transaction
    Route::get('a-transaction', [App\Http\Controllers\Account\TransactionController::class,"transaction"])->name('account.report.transaction');
    Route::post('a-transaction', [App\Http\Controllers\Account\TransactionController::class,"postTranaction"])->name('account.report.transaction');

    //journal
    Route::get('journal', [App\Http\Controllers\Account\TransactionController::class,"journal"])->name('account.report.journal');
    Route::post('journal', [App\Http\Controllers\Account\TransactionController::class,"postJournal"])->name('account.report.journal');

    //income_statement
    Route::get('income_statement', [App\Http\Controllers\Account\TransactionController::class,"incomeStatement"])->name('account.report.income_statement');
    Route::post('income_statement', [App\Http\Controllers\Account\TransactionController::class,"postIncomeStatement"])->name('account.report.income_statement');
    //cash summary
    Route::get('cash_summary', [App\Http\Controllers\Account\TransactionController::class,"cashSummary"])->name('account.report.cash_summary');
    Route::post('cash_summary', [App\Http\Controllers\Account\TransactionController::class,"postCashSummary"])->name('account.report.cash_summary');
    //cash flow
    Route::get('cash_flow', [App\Http\Controllers\Account\TransactionController::class,"cashFlow"])->name('account.report.cash_flow');
    Route::post('cash_flow', [App\Http\Controllers\Account\TransactionController::class,"postCashFlow"])->name('account.report.cash_flow');

    //account_head
    Route::resource('account_head', App\Http\Controllers\Account\AccountHeadController::class);
    Route::get('/account-head-delete/{id}','App\Http\Controllers\Account\AccountHeadController@destroy')->name('account_head.delete');
    Route::get('select2/accounts',[App\Http\Controllers\Account\AccountHeadController::class,'select2Account'])->name('select2.accounts');
    Route::get('/select2/ledgers', [App\Http\Controllers\Account\AccountHeadController::class, 'select2Ledger'])->name('select2.ledger');
    //transaction
    Route::resource('transaction', App\Http\Controllers\Account\TransactionController::class);


    //balance_account
    Route::resource('balance_account', App\Http\Controllers\Account\BalanceAccountController::class);
    Route::get('select2/balance_accounts',[App\Http\Controllers\Account\BalanceAccountController::class,'select2BalanceAccounts'])->name('select2.balance_accounts');



    //bankaccount
    Route::get('/bank-account-view','App\Http\Controllers\Account\bankaccountController@view')->name('bankaccount.view');
    Route::post('/bank-account-store','App\Http\Controllers\Account\bankaccountController@store')->name('bankaccount.store');
    Route::get('/bank-account-edit','App\Http\Controllers\Account\bankaccountController@edit')->name('bankaccount.edit');
    Route::post('/bank-account-delete','App\Http\Controllers\Account\bankaccountController@delete')->name('bankaccount.delete');

    //expense category
    Route::get('/expense-category-list','App\Http\Controllers\Account\ExpenseCategoryController@index')->name('expense_category.index');
    Route::post('/expense-category-store','App\Http\Controllers\Account\ExpenseCategoryController@store')->name('expense_category.store');
    Route::get('/expense-category-edit','App\Http\Controllers\Account\ExpenseCategoryController@edit')->name('expense_category.edit');
    Route::get('/expense-category-delete/{id}','App\Http\Controllers\Account\ExpenseCategoryController@delete')->name('expense_category.delete');

    //expense
    Route::get('/expense-list','App\Http\Controllers\Account\ExpenseController@index')->name('expense.index');
    Route::post('/expense-store','App\Http\Controllers\Account\ExpenseController@store')->name('expense.store');
    Route::get('/expense-edit','App\Http\Controllers\Account\ExpenseController@edit')->name('expense.edit');
    Route::get('/expense-delete/{id}','App\Http\Controllers\Account\ExpenseController@delete')->name('expense.delete');
    //payment method
    Route::get('/payment-method-list','App\Http\Controllers\Account\PaymentMethodController@index')->name('payment_method.index');
    Route::post('/payment-method-store','App\Http\Controllers\Account\PaymentMethodController@store')->name('payment_method.store');
    Route::get('/payment-method-edit','App\Http\Controllers\Account\PaymentMethodController@edit')->name('payment_method.edit');
    Route::get('/payment-method-delete/{id}','App\Http\Controllers\Account\PaymentMethodController@delete')->name('payment_method.delete');
    Route::get('select2/payment_methods',[App\Http\Controllers\Account\PaymentMethodController::class,'select2PaymentMthods'])->name('select2.payment_methods');

    //vourcher
    Route::get('debit-vouchar', [App\Http\Controllers\Account\VoucherController::class, 'createDebit'])->name('debit_vouchar.create');
    Route::get('credit-vouchar', [App\Http\Controllers\Account\VoucherController::class, 'createCredit'])->name('credit_vouchar.create');
    Route::get('entry-journal', [App\Http\Controllers\Account\VoucherController::class, 'createJournal'])->name('journal.create');
    Route::get('entry-contra', [App\Http\Controllers\Account\VoucherController::class, 'createContra'])->name('contra.create');
    Route::post('delete/vouchar', [App\Http\Controllers\Account\VoucherController::class,"destroy"])->name('account.voucher.delete');
    Route::post('store/vouchar', [App\Http\Controllers\Account\VoucherController::class,"store"])->name('account.voucher.store');
    Route::post('download/vouchar', [App\Http\Controllers\Account\VoucherController::class,"exportDownload"])->name('account.voucher.download');

    Route::get('index-voucher', [App\Http\Controllers\Account\VoucherController::class,"index"])->name('account.voucher.index');
    Route::post('ajax-voucher', [App\Http\Controllers\Account\VoucherController::class,"ajaxData"])->name('account.voucher.ajax');
    Route::get('voucher/edit/{id}', [App\Http\Controllers\Account\VoucherController::class,"edit"])->name('account.voucher.edit');
    Route::post('voucher/update/{id}', [App\Http\Controllers\Account\VoucherController::class,"update"])->name('account.voucher.update');
});
