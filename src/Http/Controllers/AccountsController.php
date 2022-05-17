<?php

namespace Kainotomo\PHMoney\Http\Controllers;

use Kainotomo\PHMoney\Models\Account;
use Kainotomo\PHMoney\Models\Book;
use Kainotomo\PHMoney\Models\Commodity;
use Kainotomo\PHMoney\Models\Split;
use Kainotomo\PHMoney\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Providers\Jetstream\Jetstream;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $book = Book::with('root_account')->first();
        $query = Account::with('childs_tree_with_sum')->where(['guid' => $book->root_account_guid]);

        $accounts = $query->get();
        $accounts = $accounts[0]->childs_tree_with_sum;

        $net_assets = 0;
        $profits = 0;
        Account::calculateAmountTotal($accounts, $net_assets, $profits);

        return Jetstream::inertia()->render(request(), 'Accounts/Index',[
            'accounts' => $accounts,
            'net_assets' => $net_assets,
            'profits' => $profits,
            'commodity' => $book->root_account->commodity,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $book = Book::with('root_account')->first();
        $account = new Account();
        $account->account_type = Account::ASSET;
        $account->parent = Account::where(['guid' => $book->root_account_guid])->first();
        $account->parent_guid = $account->parent->guid;
        $account->commodity = $account->parent->commodity;
        $account->commodity_guid = $account->parent->commodity_guid;
        $accounts = Account::getFlatList(true, true);

        return Jetstream::inertia()->render(request(), 'Accounts/Create',[
            'account' => $account,
            'accounts' => $accounts,
            'account_types' => Account::TYPES,
            'currencies' => Commodity::where('namespace', Commodity::CURRENCY)->get(),
            'securities' => Commodity::where('namespace', '<>', Commodity::CURRENCY)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:2048'],
            'code' => ['string', 'string', 'max:2048', 'nullable'],
            'description' => ['string', 'string', 'max:2048', 'nullable'],
            'commodity_scu' => ['required', 'integer'],
            'hidden' => ['boolean'],
            'placeholder' => ['boolean'],
            'account_type' => ['required', 'string', 'max:2048'],
            'parent_guid' => ['required', 'exists:Kainotomo\Models\Account,guid'],
            'commodity_guid' => ['required', 'exists:Kainotomo\Models\Commodity,guid']
            ])->validate();
        $validated = array_merge(['non_std_scu' => 0], $validated);

        Account::create($validated);

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'account-created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Kainotomo\Models\Account  $account
     * @return \Inertia\Response
     */
    public function edit(Account $account)
    {
        $account->parent;
        $accounts = Account::getFlatList(true, true);

        return Jetstream::inertia()->render(request(), 'Accounts/Edit',[
            'account' => $account,
            'accounts' => $accounts,
            'account_types' => Account::TYPES
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Kainotomo\Models\Account  $account
     * @return \Inertia\Response
     */
    public function update(Request $request, Account $account)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:2048'],
            'code' => ['string', 'string', 'max:2048', 'nullable'],
            'description' => ['string', 'string', 'max:2048', 'nullable'],
            'commodity_scu' => ['required', 'integer'],
            'hidden' => ['boolean'],
            'placeholder' => ['boolean'],
            'account_type' => ['required', 'string', 'max:2048'],
            'parent_guid' => ['required', 'exists:Kainotomo\Models\Account,guid'],
            'commodity_guid' => ['required', 'exists:Kainotomo\Models\Commodity,guid']
            ])->validate();

        $account->update($validated);

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'account-updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Inertia\Response
     */
    public function destroy(Account $account)
    {
        $tx_guids = $account->splits->pluck('tx_guid');
        Split::whereIn('tx_guid', $tx_guids)->delete();
        Transaction::whereIn('guid', $tx_guids)->delete();
        $account->delete();
        return request()->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'account-deleted');
    }
}
