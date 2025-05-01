<?php

namespace App\Listeners;

use App\Events\AnggaranDisetujui;
use App\Models\AnggaranFix;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SimpanAnggaranDisetujui
{
    /**
     * Handle the event.
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
