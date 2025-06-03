<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SpdExport implements FromCollection, WithHeadings, WithStyles
{
    protected $selectedSpds;

    public function __construct(Collection $selectedSpds)
    {
        $this->selectedSpds = $selectedSpds;
    }

    public function collection()
    {
        $mappedData = $this->selectedSpds->map(function ($spd, $index) {
            return [
                'No' => $index + 1,
                'Nama' => $spd->nama_pegawai,
                'Nomor SPD' => $spd->nomor_spd,
                'Dept' => $spd->departemen->nama ?? '-',
                'BS NO' => $spd->departemen->bs_number ?? '-',
                'PR' => $spd->pr ?? '-',
                'PO' => $spd->po ?? '-',
                'SES' => $spd->ses ?? '-',
                'Biaya DPD' => 'Rp ' . number_format($spd->totalBiayaDpd(), 0, ',', '.'),
                'Submit Finec' => $spd->tanggal_pengajuan ? $spd->tanggal_pengajuan->format('d M Y') : ($spd->updated_at ? $spd->updated_at->format('d M Y') : '-'),
                'Status (1 Week)' => $spd->status_1_week ?? '-',
                'Payment By Finec' => $spd->payment_by_finec ?? '-',
                'Keterangan' => $spd->keterangan ?? '-',
            ];
        });

        $totalBiayaDPD = $this->selectedSpds->sum(function ($spd) {
            return $spd->totalBiayaDpd();
        });

        $mappedData->push([
            'No' => '',
            'Nama' => 'Total Biaya DPD',
            'Nomor SPD' => '',
            'Dept' => '',
            'BS NO' => '',
            'PR' => '',
            'PO' => '',
            'SES' => '',
            'Biaya DPD' => 'Rp ' . number_format($totalBiayaDPD, 0, ',', '.'),
            'Submit Finec' => '',
            'Status (1 Week)' => '',
            'Payment By Finec' => '',
            'Keterangan' => '',
        ]);

        return $mappedData;
    }



    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Nomor SPD',
            'Dept',
            'BS NO',
            'PR',
            'PO',
            'SES',
            'Biaya DPD',
            'Submit Finec',
            'Status (1 Week)',
            'Payment By Finec',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'a9d08e'],
            ],
        ]);

        $sheet->getStyle('A1:M' . $sheet->getHighestRow())
            ->applyFromArray([
                'font' => [
                    'size' => 13,
                    'name' => 'Calibri',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $lastRow = $sheet->getHighestRow();
        $sheet->getDefaultRowDimension()->setRowHeight(32.1);
        $sheet->getStyle('I' . $lastRow)->getFont()->setColor(new Color(Color::COLOR_RED));
    }
}
