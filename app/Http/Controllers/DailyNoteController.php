<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyNoteRequest;
use App\Http\Requests\UpdateDailyNoteRequest;
use App\Models\DailyNote;

class DailyNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyNoteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyNote $dailyNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyNote $dailyNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyNoteRequest $request, DailyNote $dailyNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyNote $dailyNote)
    {
        //
    }
}
