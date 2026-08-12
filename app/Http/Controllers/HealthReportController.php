<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHealthReportRequest;
use App\Http\Requests\UpdateHealthReportRequest;
use App\Models\HealthReport;

class HealthReportController extends Controller
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
    public function store(StoreHealthReportRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HealthReport $healthReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HealthReport $healthReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHealthReportRequest $request, HealthReport $healthReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HealthReport $healthReport)
    {
        //
    }
}
