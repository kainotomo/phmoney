<?php

namespace Kainotomo\Http\Controllers;

use App\Models\Portfolio\Account;
use App\Models\Portfolio\Base;
use App\Models\Portfolio\Commodity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Providers\Jetstream\Jetstream;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Jetstream::inertia()->render(request(), 'Reports/Index', [
            'settings' => auth()->user()->settings,
        ]);
    }

    /**
     * Increase date based on step size
     *
     * @param Request $request
     * @param Carbon $date
     * @return Carbon
     */
    protected function increaseDate(Request $request, Carbon $date) {
        $result = new Carbon($date->toDateTimeString());
        switch ($request->step_size) {
            case 'One Day':
                $result->addDay();
                break;
            case 'One Week':
                $result->addWeek();
                break;
            case 'Two Weeks':
                $result->addWeeks(2);
                break;
            case 'One Month':
                $result->addMonth();
                break;
            case 'Quarter Year':
                $result->addQuarter();
                break;
            case 'Half Year':
                $result->addQuarters(2);
                break;
            case 'One Year':
                $result->addYear();
                break;
            default:
                $result->addMonth();
                break;
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $default_accounts
     * @return array
     */
    protected function chart(Request $request, array $default_accounts)
    {
        $date_start = $this->getStartDate($request);
        $date_end = $this->getEndDate($request);

        $columns = [
            0 => ['type' => 'string', 'name' => 'Date'],
        ];

        $rows = [];
        foreach ($default_accounts as $i => $default_account) {
            $columns[] = ['type' => 'number', 'name' => $default_account['name']];
            $date_from = new Carbon($date_start->toDateTimeString());
            $date_to = $this->increaseDate($request, $date_from);
            $j = 0;
            while ($date_from->lessThanOrEqualTo($date_end)) {
                $query = DB::connection('mysql_portfolio')->table('splits')
                    ->select(
                        DB::raw('sum(splits.value_num/splits.value_denom) as amount'),
                    )
                    ->where('transactions.post_date', '>=', $date_from)
                    ->where('transactions.post_date', '<=', $date_to)
                    ->where('splits.team_id', $request->user()->currentTeam->id)
                    ->where('accounts.team_id', $request->user()->currentTeam->id)
                    ->where('transactions.team_id', $request->user()->currentTeam->id)
                    ->leftJoin('accounts', 'accounts.guid', '=', 'splits.account_guid')
                    ->leftJoin('transactions', 'transactions.guid', '=', 'splits.tx_guid');
                if ($request->accounts) {
                    $query->whereIn('accounts.guid', explode(",", $request->accounts));
                } else {
                    $query->whereIn('accounts.account_type', $default_account['accounts']);
                }
                $amounts = $query->get();
                $rows[$j][0] = $date_from->toFormattedDateString();
                $rows[$j][$i + 1] = $amounts->sum('amount');

                $date_from = $this->increaseDate($request, $date_from);
                $date_to = $this->increaseDate($request, $date_to);
                $j++;
            }
        }

        return [
            'columns' => $columns,
            'rows' => $rows,
            'date_start' => $date_start,
            'date_end' => $date_end,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $default_accounts
     * @return array
     */
    protected function piechart(Request $request, array $default_accounts)
    {
        $date_start = $this->getStartDate($request);
        $date_end = $this->getEndDate($request);

        $amounts = DB::connection('mysql_portfolio')->table('splits')
            ->select(
                'accounts.guid',
                'accounts.name',
                DB::raw('sum(1.0*splits.value_num/splits.value_denom) as amount'),
                'transactions.post_date',
                'commodities.mnemonic',
                'commodities.fraction',
            )
            ->where('transactions.post_date', '<=', $date_end)
            ->where('splits.team_id', $request->user()->currentTeam->id)
            ->where('accounts.team_id', $request->user()->currentTeam->id)
            ->where('transactions.team_id', $request->user()->currentTeam->id)
            ->where('commodities.team_id', $request->user()->currentTeam->id)
            ->leftJoin('accounts', 'accounts.guid', '=', 'splits.account_guid')
            ->leftJoin('transactions', 'transactions.guid', '=', 'splits.tx_guid')
            ->leftJoin('commodities', 'commodities.guid', '=', 'accounts.commodity_guid')
            ->groupBy('splits.account_guid')
            ->get();

        $accounts = Account::getFlatList(false, true, null, null, 0, $date_start, $date_end, $amounts);
        $rows = collect($accounts->all());

        if ($request->accounts) {
            $rows = $rows->whereIn('guid', explode(",", $request->accounts));
        }

        $assets_rows = $rows->whereIn('type', $default_accounts);
        $assets_rows = !is_null($request->level) ? $assets_rows->where('level', '=', $request->level) : $assets_rows->where('level', '=', 0);
        $result = [];
        $total = 0;
        foreach ($assets_rows as $item) {
            $total += abs($item['amount_sum']);
            $result[] = [
                0 => $item['name_simple'],
                1 => abs($item['amount_sum'])
            ];
        }

        return [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'accounts' => $accounts,
            'rows' => $result,
            'total' => $total
        ];
    }

    /**
     * Display transactions reports.
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @param \Illuminate\Http\Request $request
     * @param int $setting_id
     * @return \Inertia\Response
     */
    public function transactions(Request $request)
    {
        $date_start = $this->getStartDate($request);
        $date_end = $this->getEndDate($request);

        $rows = DB::connection('mysql_portfolio')->table('splits')
            ->select(
                'accounts.guid',
                'accounts.name',
                'splits.guid as split_guid',
                'splits.memo',
                DB::raw('splits.value_num/splits.value_denom as amount'),
                'transactions.post_date',
                'transactions.description',
                'transactions.num',
                'commodities.namespace',
                'commodities.mnemonic',
                'commodities.fraction',
            )
            ->whereIn('splits.account_guid', explode(",", $request->accounts))
            ->where('splits.team_id', $request->user()->currentTeam->id)
            ->where('accounts.team_id', $request->user()->currentTeam->id)
            ->where('transactions.team_id', $request->user()->currentTeam->id)
            ->where('commodities.team_id', $request->user()->currentTeam->id)
            ->where('transactions.post_date', '>=', $date_start)
            ->where('transactions.post_date', '<=', $date_end)
            ->leftJoin('accounts', 'accounts.guid', '=', 'splits.account_guid')
            ->leftJoin('transactions', 'transactions.guid', '=', 'splits.tx_guid')
            ->leftJoin('commodities', 'commodities.guid', '=', 'accounts.commodity_guid')
            ->get();

        $rows = $rows->groupBy('guid');

        foreach ($rows as $key => $value) {
            $rows[$key] = collect([
                'guid' => $key,
                'name' => $value[0]->name,
                'rows' => $value,
                'total' => $value->sum('amount'),
                'commodity' => [
                    'namespace' => $value[0]->namespace,
                    'mnemonic' => $value[0]->mnemonic,
                    'fraction' => $value[0]->fraction,
                ],
            ]);
        }

        return Jetstream::inertia()->render(request(), 'Reports/Transactions', [
            'print' => $request->print == 'true' ? true :  false,
            'currencies' => Commodity::where('namespace', Commodity::CURRENCY)->get(),
            'settings' => $request->user()->currentTeam->settings()->where('type', $request->decodedPath())->get(),
            'type' => $request->decodedPath(),
            'title' => $request->title ?? null,
            'company' => $request->company ?? null,
            'currency' => $request->currency ? json_decode($request->currency, true) : Commodity::where('namespace', Commodity::CURRENCY)->first(),
            'date_start' => $date_start,
            'date_end' => $date_end,
            'accounts' => Account::getFlatList(false, true, null, null, 0),
            'rows' => $rows,
            'total' => $rows->sum('total'),
        ]);
    }
}
