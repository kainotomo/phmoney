<?php

namespace Kainotomo\PHMoney\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class Split extends Base
{
    protected $fillable = [
        'team_id', 'tx_guid', 'account_guid', 'memo', 'action', 'reconcile_state', 'reconcile_date', 'value_num', 'value_denom', 'quantity_num', 'quantity_denom', 'lot_guid'
    ];

    /**
     * Belongs to Account
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_guid', 'guid');
    }

    /**
     * Belongs to Transaction
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_guid', 'guid');
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'guid', 'lot_guid');
    }

    /**
     * Get all splits for an account with their balances.
     * The key is the pk
     *
     * @param Account $account
     * @param Request $request
     * @return \Illuminate\Support\Collection 
     */
    public static function getBalancesForAccount(Account $account, Request $request)
    {
        $splits = DB::connection('phmoney_portfolio')->table('splits')
        ->select(
            'splits.pk',
            DB::raw('1.0*phmprt_splits.value_num/phmprt_splits.value_denom as amount'),
        )
        ->where('splits.team_id', $request->user()->currentTeam->id)
        ->where('accounts.team_id', $request->user()->currentTeam->id)
        ->where('accounts.pk', $account->pk)
        ->where('transactions.team_id', $request->user()->currentTeam->id)
        ->where('commodities.team_id', $request->user()->currentTeam->id)
        ->leftJoin('accounts', 'accounts.guid', '=', 'splits.account_guid')
        ->leftJoin('transactions', 'transactions.guid', '=', 'splits.tx_guid')
        ->leftJoin('commodities', 'commodities.guid', '=', 'accounts.commodity_guid')
        ->orderBy('transactions.post_date', 'asc')
        ->orderBy('splits.pk', 'asc')
        ->get();

        // calculate balance for all splits
        $balance = 0;
        $balances = collect();
        foreach ($splits as $split) {
            $balance += $split->amount;
            $balances[$split->pk] = round($balance, 2);
        }

        return $balances;
    }

    /**
     * Get all splits for an account with their balances
     *
     * @param Account $account
     * @param Request $request
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getForAccount(Account $account, Request $request)
    {
        if ($request->page) {
            $balances = Cache::get('balances:' . $account->guid, null);
            if (is_null($balances)) {
                Cache::forget('balances:' . $account->guid);
                $balances = Split::getBalancesForAccount($account, $request);
                Cache::put('balances:' . $account->guid, $balances, now()->addHour());
            }
        } else {
            Cache::forget('balances:' . $account->guid);
            $balances = Split::getBalancesForAccount($account, $request);
            Cache::forever('balances:' . $account->guid, $balances, now()->addHour());
        }

        $query = DB::connection('phmoney_portfolio')->table('splits')
        ->select(
            'accounts.name',
            'accounts.code',
            DB::raw('1.0*phmprt_splits.value_num/phmprt_splits.value_denom as amount'),
            'transactions.pk as pk',
            'transactions.post_date',
            'commodities.mnemonic',
            'transactions.description',
            'transactions.num',
            'splits.pk as split_pk',
            'splits.memo',
            'splits.reconcile_state'
        )
        ->where('splits.team_id', $request->user()->currentTeam->id)
        ->where('accounts.team_id', $request->user()->currentTeam->id)
        ->where('accounts.pk', $account->pk)
        ->where('transactions.team_id', $request->user()->currentTeam->id)
        ->where('commodities.team_id', $request->user()->currentTeam->id)
        ->leftJoin('accounts', 'accounts.guid', '=', 'splits.account_guid')
        ->leftJoin('transactions', 'transactions.guid', '=', 'splits.tx_guid')
        ->leftJoin('commodities', 'commodities.guid', '=', 'accounts.commodity_guid')
        ->orderBy('transactions.post_date', 'desc')
        ->orderBy('splits.pk', 'desc');

        if ($request->memo) {
            $query->where('splits.memo', 'LIKE', '%' . $request->memo . '%');
        }
        if ($request->description) {
            $query->where('transactions.description', 'LIKE', '%' . $request->description . '%');
        }
        if ($request->num) {
            $query->where('transactions.num', 'LIKE', '%' . $request->num . '%');
        }
        if ($request->date_start) {
            $date_start = (new Carbon($request->date_start))->startOfDay();
            $query->where('transactions.post_date', '>=', $date_start);
        }
        if ($request->date_end) {
            $date_end = (new Carbon($request->date_end))->endOfDay();
            $query->where('transactions.post_date', '<=', $date_end);
        }

        $splits = $query->paginate();

        foreach ($splits as $split) {
            $split->post_date = (Date::createFromTimeString($split->post_date))->toDateString();
            $split->debit = $split->amount > 0 ? (float) $split->amount : null;
            $split->credit = $split->amount < 0 ? (float) -$split->amount : null;
            $split->balance = $balances[$split->split_pk];
        }

        return $splits;
    }

    /**
     * Get all splits for an account with their balances
     *
     * @param Transaction $transaction
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getForTransaction(Transaction $transaction)
    {
        $splits = Split::select(
            '*',
            DB::raw('1.0*value_num/value_denom as amount'),
            DB::raw('1.0*quantity_num/quantity_denom as shares'),
            DB::raw('(1.0*value_num/value_denom)/(quantity_num/quantity_denom) as price')
        )
            ->with(['account', 'transaction', 'transaction.splits' => function ($query) {
                $query->select(
                    '*',
                    DB::raw('1.0*value_num/value_denom as amount'),
                    DB::raw('1.0*quantity_num/quantity_denom as shares'),
                    DB::raw('(1.0*value_num/value_denom)/(quantity_num/quantity_denom) as price')
                );
            }, 'transaction.splits.account', 'transaction.commodity'])
            ->where('tx_guid', $transaction->guid)
            ->get();
        $splits = $splits->sortBy(function ($split, $key) {
            return $split->transaction->post_date;
        })->values();

        // calculate balance for all splits
        foreach ($splits as $key => $split) {
            $split->error_message = null;
            $split->precision = strlen(strval($split->value_denom)) - 1;
            $split->precision_shares = strlen(strval($split->quantity_denom)) - 1;

            $split->debit = null;
            if ($split->amount > 0) {
                $split->debit = (float) $split->amount;
            }

            $split->credit = null;
            if ($split->amount < 0) {
                $split->credit = abs($split->amount);
            }
        }

        return $splits;
    }
}
