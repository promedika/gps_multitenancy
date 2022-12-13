<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use PDF;
use DB;

class BookletController extends Controller
{
    public function index()
    {
        $chunks = Inventory::select('id', 'barcode')->get()->chunk(100);

        return view('booklet.index', ['chunks' => $chunks]);
    }

    public function generate($offset)
    {
        ini_set('max_execution_time', 300);

        $inventories = Inventory::offset($offset * 100)->take(100)->get();

        $db_host = explode('.', $_SERVER['HTTP_HOST'])[0];

        $tenant = DB::connection('host')->table('tenants')->where('database', explode('.', $_SERVER['HTTP_HOST'])[0])->get();
        $tenant_code = $tenant[0]->code;

        foreach ($inventories as $k => $v) {
            $len = (substr($v->barcode, 0,1) != 0 && strlen($tenant_code) == 3) ? 3 : 4;
            $cst_barcode = trim(str_replace("‘","",$v->barcode));
            $v->custom_barcode = (strlen($v->barcode) <= 4 && stripos($cst_barcode, $tenant_code) === false) ? '-' : substr($cst_barcode, $len);
        }
        
        $pdf = PDF::loadView('booklet.pdf', ['inventories' => $inventories]);

        return $pdf->stream('booklet_'.strtotime(date('Y-m-d H:i:s')).'.pdf');
        // return view('booklet.pdf', ['inventories' => $inventories]);
    }
}
