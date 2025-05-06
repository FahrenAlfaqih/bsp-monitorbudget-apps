<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function getTotalAnggaran($departemen_id, $periode_id)
    {
        $total_anggaran = 0;
        DB::select('CALL GetTotalAnggaranByDeptPeriod(?, ?, @total_anggaran)', [$departemen_id, $periode_id]);
        $result = DB::select('SELECT @total_anggaran as total_anggaran');
        if ($result && isset($result[0])) {
            $total_anggaran = $result[0]->total_anggaran;
        }
        return response()->json(['total_anggaran' => $total_anggaran]);
    }
}
