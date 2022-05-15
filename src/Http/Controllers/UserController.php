<?php

namespace Kainotomo\Http\Controllers;

use App\Models\Portfolio\Slot;
use App\Providers\Jetstream\Jetstream;
use Illuminate\Http\Request;

class UserController extends Controller
{

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

        return response(array_merge($user->toArray(),
        ['options' => Slot::getOptions()],
        array_filter([
            'all_teams' => Jetstream::hasTeamFeatures() ? $user->allTeams()->values() : null,
        ]), [
            'two_factor_enabled' => !is_null($user->two_factor_secret),
        ]));
    }

}
