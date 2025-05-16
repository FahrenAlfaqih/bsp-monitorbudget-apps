<?php

namespace App\Exports;

use App\Models\Spd;
use Maatwebsite\Excel\Concerns\FromCollection;

class SpdExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Spd::all();
    }
}
