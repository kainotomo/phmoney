<?php

namespace Kainotomo\PHMoney\Http\Controllers;

use Kainotomo\PHMoney\Models\Slot;
use App\Providers\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kainotomo\PHMoney\Models\Book;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $book = Book::first();
        if (!$book) {
            dd($book);
            $user = $request->user();
            $team_id = $user->currentTeam->id;
            Storage::copy("samples/business_accounts.gnucash", "import/sqlite/$team_id.sqlite");
            $user->currentTeam->sqlite2mariadb();
        }
        return view('phmoney::phmoney');
    }

    /**
     * Override inertia controller
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function show(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return;
        }

        if (Jetstream::hasTeamFeatures() && $user) {
            $user->currentTeam;
        }

        return response(array_merge(
            $user->toArray(),
            ['options' => Slot::getOptions()],
            array_filter([
                'all_teams' => Jetstream::hasTeamFeatures() ? $user->allTeams()->values() : null,
            ]),
            [
                'two_factor_enabled' => !is_null($user->two_factor_secret),
            ]
        ));
    }
}
