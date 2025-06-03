<?php

namespace App\Http\Controllers;

use App\Models\AnggaranFix;
use App\Models\Departemen;
use App\Models\Dpd;
use App\Models\Pegawai;
use App\Models\PeriodeAnggaran;
use App\Models\ReimburseRule;
use App\Models\Spd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use App\Exports\SpdExport;
use Maatwebsite\Excel\Facades\Excel;



class SpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Spd::with(['departemen', 'user', 'details']);

        if ($request->filled('departemen')) {
            $query->where('departemen_id', $request->departemen);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_transport')) {
            $query->where('jenis_transport', $request->jenis_transport);
        }
        if ($request->filled('nomor_spd')) {
            $query->where('nomor_spd', $request->nomor_spd);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_berangkat', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_berangkat', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('tanggal_berangkat', '<=', $request->end_date);
        }

        $spds = $query->get();
        $departemenList = Departemen::all();

        return view('spd.index', compact('spds', 'departemenList'));
    }


    public function show(Spd $spd)
    {
        $spd->load('details', 'departemen');

        return view('spd.show', compact('spd'));
    }


    public function ajukan(Request $request)
    {
        $ids = $request->input('spd_ids');

        if (!$ids) {
            return back()->with('error', 'Pilih minimal satu SPD untuk diajukan.');
        }

        Spd::whereIn('id', $ids)->update(['status' => 'diajukan']);
        $selectedSpds = Spd::whereIn('id', $ids)->with(['departemen', 'details'])->get();
        $fileName = 'Laporan_SPD_Diajukan_' . date('Ymd_His') . '.xlsx';
        Alert::success('Berhasil', 'Laporan SPD berhasil diajukan ke finance!');
        return Excel::download(new SpdExport($selectedSpds), $fileName);
        return back();
    }

    public function pengajuan(Request $request)
    {
        $query = Spd::query()->where('status', 'diajukan');

        if ($request->filled('departemen')) {
            $query->where('departemen_id', $request->departemen);
        }

        $spds = $query->get();
        $departemenList = Departemen::all();

        return view('spd.pengajuan', compact('spds', 'departemenList'));
    }


    public function editStatus(Spd $spd)
    {
        if ($spd->status !== 'diajukan') {
            return redirect()->route('spd.pengajuan')->with('error', 'SPD sudah diproses.');
        }
        $spd->load('details');
        $pegawaiLevelId = $spd->pegawai->pegawai_level_id;
        $reimburseRules = ReimburseRule::where('pegawai_level_id', $pegawaiLevelId)->get();

        return view('spd.update-status', compact('spd', 'reimburseRules'));
    }


    public function updateStatus(Request $request, Spd $spd)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'total_biaya' => 'required_if:status,disetujui|nullable|numeric',
            'tanggal_deklarasi' => 'required|date',
            'uraian' => 'required|string',
        ]);

        // Ambil sisa anggaran fix departemen untuk periode SPD tersebut
        $departemenId = $spd->departemen_id;
        $periodeId = $spd->periode_id;

        // Total anggaran fix yang disetujui untuk departemen dan periode terkait
        $totalAnggaranFix = AnggaranFix::where('departemen_id', $departemenId)
            ->where('periode_id', $periodeId)
            ->sum('jumlah_anggaran');

        // Total DPD yang sudah terpakai pada periode dan departemen tsb (kecuali SPD yang sedang diproses)
        $totalDpdTerpakai = Dpd::whereHas('spd', function ($query) use ($departemenId, $periodeId) {
            $query->where('departemen_id', $departemenId)
                ->where('periode_id', $periodeId);
        })
            ->where('spd_id', '!=', $spd->id) // exclude current SPD jika sudah ada DPD-nya
            ->sum('total_biaya');

        // Hitung sisa anggaran yang tersedia
        $sisaAnggaran = $totalAnggaranFix - $totalDpdTerpakai;

        if ($request->status === 'disetujui' && $request->total_biaya > $sisaAnggaran) {
            Alert::warning('Perhatian', 'Total biaya melebihi sisa anggaran departemen yang tersedia (Rp ' . number_format($sisaAnggaran, 0, ',', '.') . '). Mohon sesuaikan biaya.');
            return back()->withInput();
        }

        $spd->update([
            'status' => $request->status,
            'uraian' => $request->uraian,
            'tanggal_deklarasi' => $request->tanggal_deklarasi,
        ]);

        if ($request->status === 'disetujui') {
            $dpdExists = Dpd::where('spd_id', $spd->id)->exists();

            if (!$dpdExists) {
                Dpd::create([
                    'spd_id' => $spd->id,
                    'user_id' => Auth::id(),
                    'total_biaya' => $request->total_biaya,
                    'pr' => $request->pr,
                    'po' => $request->po,
                    'ses' => $request->ses,
                    'tanggal_deklarasi' => $request->tanggal_deklarasi,
                    'uraian' => $request->uraian,
                ]);
            }
        }

        Alert::success('Berhasil', 'Persetujuan SPD berhasil diproses!');
        return redirect()->route('spd.pengajuan');
    }




    public function create()
    {
        $departemen = Departemen::all();
        $users = User::all();
        $pegawais = Pegawai::all();
        $periodes = PeriodeAnggaran::orderBy('mulai', 'desc')->first();

        return view('spd.create', compact('departemen', 'users', 'pegawais', 'periodes'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'periode_id' => 'required|exists:periode_anggaran,id',
            'nomor_spd' => 'required|unique:surat_perjalanan_dinas,nomor_spd',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'kegiatan' => 'nullable|string',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'jenis_transport' => 'nullable|string',
            'nama_transport' => 'nullable|string',

            // validasi untuk detail biaya
            'details' => 'required|array|min:1',
            'details.*.jenis_biaya' => 'required|string',
            'details.*.nominal' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        $pegawai = Pegawai::find($validated['pegawai_id']);

        $spd = Spd::create([
            'departemen_id' => $validated['departemen_id'],
            'pegawai_id' => $pegawai->id,
            'periode_id' => $validated['periode_id'],
            'user_id' => Auth::id(),
            'nomor_spd' => $validated['nomor_spd'],
            'nama_pegawai' => $pegawai->nama_pegawai,
            'asal' => $validated['asal'],
            'tujuan' => $validated['tujuan'],
            'kegiatan' => $validated['kegiatan'],
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jenis_transport' => $validated['jenis_transport'],
            'nama_transport' => $validated['nama_transport'],
        ]);

        // Simpan detail biaya
        foreach ($validated['details'] as $detail) {
            $spd->details()->create([
                'jenis_biaya' => $detail['jenis_biaya'],
                'nominal' => $detail['nominal'],
                'keterangan' => $detail['keterangan'] ?? null,
            ]);
        }

        Alert::success('Berhasil', 'Surat Perjalanan Dinas dan rincian biaya berhasil ditambahkan!');

        return redirect()->route('spd.index');
    }

    public function edit(Spd $spd)
    {
        $departemen = Departemen::all();
        $pegawais = Pegawai::all();
        $periodes = PeriodeAnggaran::orderBy('mulai', 'desc')->first();
        $spd->load('details');

        // Hapus 'details' dari compact karena tidak ada variabel $details
        return view('spd.edit', compact('spd', 'departemen', 'pegawais', 'periodes'));
    }


    public function update(Request $request, Spd $spd)
    {
        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'periode_id' => 'required|exists:periode_anggaran,id',
            'nomor_spd' => 'required|unique:surat_perjalanan_dinas,nomor_spd,' . $spd->id,
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'kegiatan' => 'nullable|string',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'jenis_transport' => 'nullable|string',
            'nama_transport' => 'nullable|string',

            'details' => 'required|array|min:1',
            'details.*.jenis_biaya' => 'required|string',
            'details.*.nominal' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        $pegawai = Pegawai::find($validated['pegawai_id']);

        $spd->update([
            'departemen_id' => $validated['departemen_id'],
            'pegawai_id' => $pegawai->id,
            'periode_id' => $validated['periode_id'],
            'user_id' => Auth::id(),
            'nomor_spd' => $validated['nomor_spd'],
            'nama_pegawai' => $pegawai->nama_pegawai,
            'asal' => $validated['asal'],
            'tujuan' => $validated['tujuan'],
            'kegiatan' => $validated['kegiatan'],
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jenis_transport' => $validated['jenis_transport'],
            'nama_transport' => $validated['nama_transport'],
        ]);

        $spd->details()->delete();

        foreach ($validated['details'] as $detail) {
            $spd->details()->create([
                'jenis_biaya' => $detail['jenis_biaya'],
                'nominal' => $detail['nominal'],
                'keterangan' => $detail['keterangan'] ?? null,
            ]);
        }

        Alert::success('Berhasil', 'Surat Perjalanan Dinas berhasil diupdate!');
        return redirect()->route('spd.index');
    }
}
