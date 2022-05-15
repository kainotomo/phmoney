<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/gnucash', function () {
        return view('gnucash');
    })->name('gnucash');

    Route::prefix('/user')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'show'])->name('user');
    });

    Route::get('/dashboard2', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard2');

    Route::prefix('/accounts')->group(function () {
        Route::get('/', [\App\Http\Controllers\AccountsController::class, 'index'])->name('accounts');
        Route::get('/create', [\App\Http\Controllers\AccountsController::class, 'create'])->name('accounts.create');
        Route::post('/store', [\App\Http\Controllers\AccountsController::class, 'store'])->name('accounts.store');
        Route::get('/edit/{account}', [\App\Http\Controllers\AccountsController::class, 'edit'])->name('accounts.edit');
        Route::post('/update/{account}', [\App\Http\Controllers\AccountsController::class, 'update'])->name('accounts.update');
        Route::delete('/destroy/{account}', [\App\Http\Controllers\AccountsController::class, 'destroy'])->name('accounts.destroy');
    });

    Route::prefix('/reconcile/{account}')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReconcileController::class, 'index'])->name('reconcile');
        Route::post('/update', [\App\Http\Controllers\ReconcileController::class, 'update'])->name('reconcile.update');
    });

    Route::prefix('/transactions/{account}')->group(function () {
        Route::get('/', [\App\Http\Controllers\TransactionsController::class, 'index'])->name('transactions');
        Route::get('/create', [\App\Http\Controllers\TransactionsController::class, 'create'])->name('transactions.create');
        Route::post('/store', [\App\Http\Controllers\TransactionsController::class, 'store'])->name('transactions.store');
        Route::get('/edit/{transaction}', [\App\Http\Controllers\TransactionsController::class, 'edit'])->name('transactions.edit');
        Route::get('/duplicate/{transaction}', [\App\Http\Controllers\TransactionsController::class, 'duplicate'])->name('transactions.duplicate');
        Route::post('/update/{transaction}', [\App\Http\Controllers\TransactionsController::class, 'update'])->name('transactions.update');
        Route::delete('/destroy/{transaction}', [\App\Http\Controllers\TransactionsController::class, 'destroy'])->name('transactions.destroy');
    });

    Route::prefix('/settings')->group(function () {
        Route::put('/store', [\App\Http\Controllers\SettingController::class, 'store'])->name('settings.store');
        Route::post('/update/{setting}', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::delete('/destroy/{setting}', [\App\Http\Controllers\SettingController::class, 'destroy'])->name('settings.destroy');
    });

    Route::prefix('/import')->group(function () {
        Route::prefix('/transactions-from-csv')->group(function () {
            Route::get('/page1', [\App\Http\Controllers\Import\TransactionsFromCsvController::class, 'page1'])->name('import.transactions-from-csv.page1');
            Route::get('/page2', [\App\Http\Controllers\Import\TransactionsFromCsvController::class, 'page2'])->name('import.transactions-from-csv.page2');
            Route::get('/page3', [\App\Http\Controllers\Import\TransactionsFromCsvController::class, 'page3'])->name('import.transactions-from-csv.page3');
            Route::post('/page3/update', [\App\Http\Controllers\Import\TransactionsFromCsvController::class, 'page3Update'])->name('import.transactions-from-csv.page3.update');
            Route::get('/page4', [\App\Http\Controllers\Import\TransactionsFromCsvController::class, 'page4'])->name('import.transactions-from-csv.page4');
        });
    });

    Route::prefix('/reports')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports');
        Route::get('/transactions', [\App\Http\Controllers\ReportController::class, 'transactions'])->name('reports.transactions');
        Route::prefix('/assets_liabilities')->group(function () {
            Route::get('/balance_sheet', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'balance_sheet'])->name('reports.balance_liabilities.balance_sheet');
            Route::get('/general_ledger', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'general_ledger'])->name('reports.balance_liabilities.general_ledger');
            Route::get('/assets_columnchart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'assets_columnchart'])->name('reports.balance_liabilities.assets_columnchart');
            Route::get('/assets_piechart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'assets_piechart'])->name('reports.balance_liabilities.assets_piechart');
            Route::get('/liabilities_columnchart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'liabilities_columnchart'])->name('reports.balance_liabilities.liabilities_columnchart');
            Route::get('/liabilities_piechart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'liabilities_piechart'])->name('reports.balance_liabilities.liabilities_piechart');
            Route::get('/networth_columnchart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'networth_columnchart'])->name('reports.balance_liabilities.networth_columnchart');
            Route::get('/networth_linechart', [\App\Http\Controllers\Reports\AssetsLiabilitiesController::class, 'networth_linechart'])->name('reports.balance_liabilities.networth_linechart');
        });
        Route::prefix('/business')->group(function () {
            Route::get('/customer_report', [\App\Http\Controllers\Reports\BusinessController::class, 'customer_report'])->name('reports.business.customer_report');
            Route::get('/customer_summary', [\App\Http\Controllers\Reports\BusinessController::class, 'customer_summary'])->name('reports.business.customer_summary');
            Route::get('/employee_report', [\App\Http\Controllers\Reports\BusinessController::class, 'employee_report'])->name('reports.business.employee_report');
            Route::get('/vendor_report', [\App\Http\Controllers\Reports\BusinessController::class, 'vendor_report'])->name('reports.business.vendor_report');
        });
        Route::prefix('/income_expense')->group(function () {
            Route::get('/cash_flow', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'cash_flow'])->name('reports.income_expense.cash_flow');
            Route::get('/cash_flow_columnchart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'cash_flow_columnchart'])->name('reports.income_expense.cash_flow_columnchart');
            Route::get('/expenses_columnchart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'expenses_columnchart'])->name('reports.income_expense.expenses_columnchart');
            Route::get('/expenses_piechart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'expenses_piechart'])->name('reports.income_expense.expenses_piechart');
            Route::get('/incomeexpense_columnchart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'incomeexpense_columnchart'])->name('reports.income_expense.incomeexpense_columnchart');
            Route::get('/incomeexpense_linechart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'incomeexpense_linechart'])->name('reports.income_expense.incomeexpense_linechart');
            Route::get('/income_columnchart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'income_columnchart'])->name('reports.income_expense.income_columnchart');
            Route::get('/income_piechart', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'income_piechart'])->name('reports.income_expense.income_piechart');
            Route::get('/profit_loss', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'profit_loss'])->name('reports.income_expense.profit_loss');
            Route::get('/trial_balance', [\App\Http\Controllers\Reports\IncomeExpenseController::class, 'trial_balance'])->name('reports.income_expense.trial_balance');
        });
    });

    Route::prefix('/business')->group(function () {
        Route::get('/', [\App\Http\Controllers\BusinessController::class, 'index'])->name('business');

        Route::prefix('/payment')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\PaymentController::class, 'index'])->name('business.payment');
            Route::post('/', [\App\Http\Controllers\Business\PaymentController::class, 'store'])->name('business.payment.store');
        });

        Route::prefix('/customers')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\CustomerController::class, 'index'])->name('business.customers');
            Route::get('/create', [\App\Http\Controllers\Business\CustomerController::class, 'create'])->name('business.customers.create');
            Route::post('/store', [\App\Http\Controllers\Business\CustomerController::class, 'store'])->name('business.customers.store');
            Route::get('/edit/{customer}', [\App\Http\Controllers\Business\CustomerController::class, 'edit'])->name('business.customers.edit');
            Route::post('/update/{customer}', [\App\Http\Controllers\Business\CustomerController::class, 'update'])->name('business.customers.update');
            Route::delete('/destroy/{customer}', [\App\Http\Controllers\Business\CustomerController::class, 'destroy'])->name('business.customers.destroy');
        });

        Route::prefix('/vendors')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\VendorController::class, 'index'])->name('business.vendors');
            Route::get('/create', [\App\Http\Controllers\Business\VendorController::class, 'create'])->name('business.vendors.create');
            Route::post('/store', [\App\Http\Controllers\Business\VendorController::class, 'store'])->name('business.vendors.store');
            Route::get('/edit/{vendor}', [\App\Http\Controllers\Business\VendorController::class, 'edit'])->name('business.vendors.edit');
            Route::post('/update/{vendor}', [\App\Http\Controllers\Business\VendorController::class, 'update'])->name('business.vendors.update');
            Route::delete('/destroy/{vendor}', [\App\Http\Controllers\Business\VendorController::class, 'destroy'])->name('business.vendors.destroy');
        });

        Route::prefix('/employees')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\EmployeeController::class, 'index'])->name('business.employees');
            Route::get('/create', [\App\Http\Controllers\Business\EmployeeController::class, 'create'])->name('business.employees.create');
            Route::post('/store', [\App\Http\Controllers\Business\EmployeeController::class, 'store'])->name('business.employees.store');
            Route::get('/edit/{employee}', [\App\Http\Controllers\Business\EmployeeController::class, 'edit'])->name('business.employees.edit');
            Route::post('/update/{employee}', [\App\Http\Controllers\Business\EmployeeController::class, 'update'])->name('business.employees.update');
            Route::delete('/destroy/{employee}', [\App\Http\Controllers\Business\EmployeeController::class, 'destroy'])->name('business.employees.destroy');
        });

        Route::prefix('/jobs')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\JobController::class, 'index'])->name('business.jobs');
            Route::get('/create', [\App\Http\Controllers\Business\JobController::class, 'create'])->name('business.jobs.create');
            Route::post('/store', [\App\Http\Controllers\Business\JobController::class, 'store'])->name('business.jobs.store');
            Route::get('/edit/{job}', [\App\Http\Controllers\Business\JobController::class, 'edit'])->name('business.jobs.edit');
            Route::post('/update/{job}', [\App\Http\Controllers\Business\JobController::class, 'update'])->name('business.jobs.update');
            Route::delete('/destroy/{job}', [\App\Http\Controllers\Business\JobController::class, 'destroy'])->name('business.jobs.destroy');
        });

        Route::prefix('/invoices')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\InvoiceController::class, 'index'])->name('business.invoices');
            Route::get('/create', [\App\Http\Controllers\Business\InvoiceController::class, 'create'])->name('business.invoices.create');
            Route::post('/store', [\App\Http\Controllers\Business\InvoiceController::class, 'store'])->name('business.invoices.store');
            Route::get('/edit/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'edit'])->name('business.invoices.edit');
            Route::post('/update/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'update'])->name('business.invoices.update');
            Route::delete('/destroy/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'destroy'])->name('business.invoices.destroy');
            Route::get('/post/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'edit_post'])->name('business.invoices.edit_post');
            Route::post('/post/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'post'])->name('business.invoices.post');
            Route::delete('/post/{invoice}', [\App\Http\Controllers\Business\InvoiceController::class, 'unpost'])->name('business.invoices.unpost');
            Route::get('/jobs', [\App\Http\Controllers\Business\InvoiceController::class, 'jobs'])->name('business.invoices.jobs');

            Route::prefix('/{invoice}/entrys')->group(function () {
                Route::get('/', [\App\Http\Controllers\Business\EntryController::class, 'index'])->name('business.entrys');
                Route::get('/create', [\App\Http\Controllers\Business\EntryController::class, 'create'])->name('business.entrys.create');
                Route::post('/store', [\App\Http\Controllers\Business\EntryController::class, 'store'])->name('business.entrys.store');
                Route::get('/edit/{entry}', [\App\Http\Controllers\Business\EntryController::class, 'edit'])->name('business.entrys.edit');
                Route::post('/update/{entry}', [\App\Http\Controllers\Business\EntryController::class, 'update'])->name('business.entrys.update');
                Route::delete('/destroy/{entry}', [\App\Http\Controllers\Business\EntryController::class, 'destroy'])->name('business.entrys.destroy');
            });
        });

        Route::prefix('/taxtables')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\TaxtableController::class, 'index'])->name('business.taxtables');
            Route::get('/create', [\App\Http\Controllers\Business\TaxtableController::class, 'create'])->name('business.taxtables.create');
            Route::post('/store', [\App\Http\Controllers\Business\TaxtableController::class, 'store'])->name('business.taxtables.store');
            Route::get('/edit/{taxtable}', [\App\Http\Controllers\Business\TaxtableController::class, 'edit'])->name('business.taxtables.edit');
            Route::post('/update/{taxtable}', [\App\Http\Controllers\Business\TaxtableController::class, 'update'])->name('business.taxtables.update');
            Route::delete('/destroy/{taxtable}', [\App\Http\Controllers\Business\TaxtableController::class, 'destroy'])->name('business.taxtables.destroy');

            Route::prefix('/{taxtable}/taxtableentrys')->group(function () {
                Route::get('/', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'index'])->name('business.taxtableentrys');
                Route::get('/create', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'create'])->name('business.taxtableentrys.create');
                Route::post('/store', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'store'])->name('business.taxtableentrys.store');
                Route::get('/edit/{taxtableentry}', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'edit'])->name('business.taxtableentrys.edit');
                Route::post('/update/{taxtableentry}', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'update'])->name('business.taxtableentrys.update');
                Route::delete('/destroy/{taxtableentry}', [\App\Http\Controllers\Business\TaxtableEntryController::class, 'destroy'])->name('business.taxtableentrys.destroy');
            });
        });

        Route::prefix('/billterms')->group(function () {
            Route::get('/', [\App\Http\Controllers\Business\BilltermController::class, 'index'])->name('business.billterms');
            Route::get('/create', [\App\Http\Controllers\Business\BilltermController::class, 'create'])->name('business.billterms.create');
            Route::post('/store', [\App\Http\Controllers\Business\BilltermController::class, 'store'])->name('business.billterms.store');
            Route::get('/edit/{billterm}', [\App\Http\Controllers\Business\BilltermController::class, 'edit'])->name('business.billterms.edit');
            Route::post('/update/{billterm}', [\App\Http\Controllers\Business\BilltermController::class, 'update'])->name('business.billterms.update');
            Route::delete('/destroy/{billterm}', [\App\Http\Controllers\Business\BilltermController::class, 'destroy'])->name('business.billterms.destroy');
        });
    });

    Route::prefix('/teams')->group(function () {
        Route::prefix('/{team}')->group(function () {
            Route::get('/options/show', [\App\Http\Controllers\Teams\OptionsController::class, 'index'])->name('teams.options.store');
            Route::put('/options/store', [\App\Http\Controllers\Teams\OptionsController::class, 'store'])->name('teams.options.store');
            Route::get('/database/download', [\App\Http\Controllers\Teams\OptionsController::class, 'download'])->name('teams.database.download');
            Route::post('/database/upload', [\App\Http\Controllers\Teams\OptionsController::class, 'upload'])->name('teams.database.upload');
        });
    });
});
