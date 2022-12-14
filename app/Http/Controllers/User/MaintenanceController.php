<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;

use PDF;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maintenances = Maintenance::with('inventory', 'inventory.device')->where('is_approved_spv','!=',3)->get();

        return view('maintenance.index', ['maintenances' => $maintenances]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Inventory $inventory)
    {
        $device = Device::find($inventory->device_id);
        return view('maintenance.create', ['inventory' => $inventory, 'device' => $device]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|integer',
        ]);

        if ($validated) {
            try {
                $maintenance = new Maintenance();
                $maintenance->create([
                    'inventory_id' => $request->inventory_id,
                    'user_id' => Auth::user()->id,
                    'result' => $request->maintenanceResult ? "Alat Bekerja dengan Baik" : "Alat Tidak Bekerja dengan Baik",
                    'raw' => json_encode($request->all())
                ]);
    
                return redirect()->route('maintenance.index')->with('success', 'Form Submitted');
            } catch (\Throwable $th) {
                return redirect()->route('maintenance.index')->with('error', 'Something wrong when submitting form');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(Maintenance $maintenance)
    {
        if ($maintenance->is_approved_spv == 3) {
            return redirect()->route('maintenance.index')->with('success', 'Data Has Been Deleted');
        }

        $raw = json_decode($maintenance->raw);

        $inventory = Inventory::find($maintenance->inventory_id);
        $device = Device::find($inventory->device_id);
        // dd($maintenance,$raw,$device);

        return view('maintenance.show', [
            'maintenance' => $maintenance, 
            'inventories' => Inventory::all(),
            'raw' => $raw,
            'device' => $device
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
        $raw = json_decode($maintenance->raw);
        
        return view('maintenance.edit', [
            'maintenance' => $maintenance, 
            'inventories' => Inventory::all(),
            'raw' => $raw
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'scheduled_date' => 'required|date',
            'done_date' => 'required|date',
            'personnel' => 'required|string',
            'inventory_id' => 'required|integer',
        ]);

        if ($validated) {
            $maintenance->scheduled_date = $request->scheduled_date;
            $maintenance->done_date = $request->done_date;
            $maintenance->personnel = $request->personnel;
            $maintenance->inventory_id = $request->inventory_id;
            $maintenance->update();

            return redirect()->route('maintenance.index')->with('success', 'Entry Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->is_approved_spv = 3;
        $maintenance->update();
        // $maintenance->delete();

        return redirect()->route('maintenance.index')->with('success', 'Entr Deleted');
    }

    public function pdf(Maintenance $maintenance)
    {
        ini_set('max_execution_time', 0);

        if ($maintenance->is_approved_spv == 3) {
            return redirect()->route('maintenance.index')->with('success', 'Data Has Been Deleted');
        }

        $raw = json_decode($maintenance->raw);
        $inventory = Inventory::find($maintenance->inventory_id);
        $device = Device::find($inventory->device_id);
        $pdf = PDF::loadView('maintenance.pdf', ['maintenance' => $maintenance, 'raw' => $raw, 'device' => $device]);

        return $pdf->stream('ipm_form'.strtotime(date('Y-m-d H:i:s')).'.pdf');
        // return view('maintenance.pdf', ['maintenance' => $maintenance, 'raw' => $raw]);
    }
}
