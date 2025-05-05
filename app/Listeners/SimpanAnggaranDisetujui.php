<?php

namespace App\Listeners;

use App\Events\AnggaranDisetujui;
use App\Models\AnggaranFix;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SimpanAnggaranDisetujui
{
    /**
     * @desc Event handler atau trigger ketika status dari rancangan anggaran disetujui maka data jumlah anggaran, departemen id
     * periode id dan jumlah anggaran akan disimpan ke table anggaran fix
     *
     * @param  \App\Events\AnggaranDisetujui  $event
     * @return void
     */
    public function handle(AnggaranDisetujui $event)
    {
        $anggaran = $event->anggaran;

        AnggaranFix::create([
            'departemen_id' => $anggaran->departemen_id,
            'periode_id' => $anggaran->periode_id,
            'jumlah_anggaran' => $anggaran->jumlah_anggaran,
        ]);
    }
}
