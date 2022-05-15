<?php

namespace Kainotomo\Http\Controllers\Business;

use Kainotomo\Http\Controllers\Controller;
use App\Http\Requests\TaxtableRequest;
use App\Models\Portfolio\Account;
use App\Models\Portfolio\Taxtable;
use App\Models\Portfolio\TaxtableEntry;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use App\Providers\Jetstream\Jetstream;

class TaxtableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Taxtable::select('pk', 'guid', 'name');

        return Jetstream::inertia()->render(request(), 'Business/Taxtables/Index', [
            'taxtables' => $query->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taxtable = new Taxtable();
        $taxtableentry = new TaxtableEntry();
        return Jetstream::inertia()->render(request(), 'Business/Taxtables/Create', [
            'taxtable' => $taxtable,
            'taxtableentry' => $taxtableentry,
            'accounts' => Account::getFlatList(),
            'type' => ['name' => "Percent %", 'value' => 1]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TaxtableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxtableRequest $request)
    {
        $taxtable = Taxtable::create($request->all());
        $taxtable->entries()->create($request->taxtableentry);
        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'taxtable-created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Portfolio\Taxtable  $taxtable
     * @return \Illuminate\Http\Response
     */
    public function show(Taxtable $taxtable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Portfolio\Taxtable  $taxtable
     * @return \Illuminate\Http\Response
     */
    public function edit(Taxtable $taxtable)
    {
        return Jetstream::inertia()->render(request(), 'Business/Taxtables/Edit', [
            'taxtable' => $taxtable,
            'taxtableentry' => $taxtable->entries->first(),
            'accounts' => Account::getFlatList(),
            'type' => ['name' => "Percent %", 'value' => 1]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TaxtableRequest  $request
     * @param  \App\Models\Portfolio\Taxtable  $taxtable
     * @return \Illuminate\Http\Response
     */
    public function update(TaxtableRequest $request, Taxtable $taxtable)
    {
        $taxtable->update($request->all());

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'taxtable-updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Portfolio\Taxtable  $taxtable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Taxtable $taxtable)
    {
        $taxtable->entries()->delete();
        $taxtable->delete();

        return request()->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'taxtable-delete');
    }
}
