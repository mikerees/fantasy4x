<?php

namespace App\Http\Controllers;

use App\Entities\Building;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Building::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entities\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function show(Building $building)
    {
        if (get_class($building) ==  Building::class) {
            throw new InvalidArgumentException("Cannot show base building class");
        }
        return response($building);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entities\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function edit(Building $building)
    {
        if (get_class($building) ==  Building::class) {
            throw new InvalidArgumentException("Cannot edit base building class");
        }
        return response($building);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Building $building)
    {
        if (get_class($building) ==  Building::class) {
            throw new InvalidArgumentException("Cannot update base building class");
        }

        return response($building);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entities\Building  $building
     * @return \Illuminate\Http\Response
     */
    public function destroy(Building $building)
    {
        if (get_class($building) ==  Building::class) {
            throw new InvalidArgumentException("Cannot destroy base building class");
        }
        $building->delete();
        return response(true);
    }
}
