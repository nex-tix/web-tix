<?php

namespace App\Tools;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Helper export Excel — dipakai ReportController & ExpenseController.
 * Berisi HANYA hal yang sama di semua endpoint export (judul, format, kirim file).
 * Logika data (param, tanggal, query) tetap di controller masing-masing.
 */
trait ExcelExportTrait
{
    // Warna header senada tombol Export di app; zebra & baris total turunan merah itu
    private const WARNA_HEADER = 'E74C3C';
    private const WARNA_ZEBRA  = 'F2F2F2';
    private const WARNA_TOTAL  = 'FADBD8';

    /**
     * Kirim spreadsheet ke browser sebagai file .xlsx, tanpa menyimpan ke disk.
     * Satu-satunya tempat header download di-set (pengganti $dompdf->stream()).
     */
    private function streamXlsx(Spreadsheet $spreadsheet, string $filename): void
    {
        // Tipe MIME khas file Excel (.xlsx) + perintah unduh dengan nama file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');   // langsung keluar sebagai file, tidak ditulis ke disk
        exit;
    }

    /**
     * Blok judul laporan 4 baris di atas tabel (merge penuh, rata tengah) —
     * teksnya identik dengan header di PDF: judul, unit, periode, tanggal cetak.
     *
     * @param Worksheet $sheet      sheet aktif
     * @param string    $akhirKolom huruf kolom terakhir tabel, mis. 'H'
     * @param string    $judul      baris 1, mis. "LAPORAN REKAPITULASI TIKET"
     * @param string    $unit       baris 2, mis. "PERTUNJUKAN KECAK ULUWATU"
     * @param string    $periode    baris 3, mis. "Periode: 01/11/2026 - 24/11/2026"
     * @param string    $tglCetak   baris 4, mis. "Tanggal Laporan: 24/11/2026 10:30 WITA"
     */
    private function judulLaporan(Worksheet $sheet, string $akhirKolom, string $judul, string $unit, string $periode, string $tglCetak): void
    {
        $baris = [
            1 => [$judul, 14, true,    '1F1F1F'],
            2 => [$unit,  12, true,    '1F1F1F'],
            3 => [$periode, 10, false, '444444'],
            4 => [$tglCetak, 9, false, '888888'],
        ];

        foreach ($baris as $no => [$teks, $ukuran, $tebal, $warna]) {
            $sheet->mergeCells('A' . $no . ':' . $akhirKolom . $no);
            $sheet->setCellValue('A' . $no, $teks);
            $gaya = $sheet->getStyle('A' . $no);
            $gaya->getFont()->setSize($ukuran)->setBold($tebal);
            $gaya->getFont()->getColor()->setRGB($warna);
            $gaya->getAlignment()->setHorizontal('center')->setVertical('center');
        }

        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(2)->setRowHeight(18);
    }

    /**
     * Styling lengkap tabel laporan: semua cell ber-border tipis, header merah solid,
     * zebra, baris total menonjol, auto-filter, lebar kolom, dan siap cetak.
     * Tanpa blok judul dan tanpa freeze/sticky (ralat review).
     *
     * @param Worksheet $sheet       sheet yang sudah berisi header kolom ($barisHeader)
     *                               dan data + baris TOTAL di bawahnya
     * @param int       $barisHeader nomor baris judul kolom (mis. 1)
     * @param array     $kolomAngka  huruf kolom angka: rata kanan +
     *                               format #,##0;-#,##0;"-" (nol tampil "-")
     * @param array     $kolomTengah huruf kolom isi rata tengah (mis. ['A','B'])
     * @param array     $lebarKolom  lebar manual per kolom ['A' => 6, ...];
     *                               kolom yang tidak disebut memakai auto-size
     * @param string    $mergeTotal  huruf kolom TERAKHIR label baris total untuk di-merge
     *                               dari kolom A (default 'B' = merge A:B)
     */
    private function styleSheet(Worksheet $sheet, int $barisHeader, array $kolomAngka = [], array $kolomTengah = [], array $lebarKolom = [], string $mergeTotal = 'B'): void
    {
        // getHighestDataRow() di PhpSpreadsheet 5.x mengembalikan int (nomor baris)
        $akhirData  = $sheet->getHighestDataRow();     // baris terakhir = TOTAL
        $akhirIsi   = $akhirData - 1;                  // baris data terakhir sebelum TOTAL
        $akhirKolom = $sheet->getHighestDataColumn();  // huruf kolom terakhir (mis. 'H')

        // Sembunyikan gridlines bawaan — tabel sudah punya border sendiri
        $sheet->setShowGridlines(false);

        // --- Header kolom: merah solid, teks putih, bold, center, tinggi 24 ---
        $gayaHeader = $sheet->getStyle('A' . $barisHeader . ':' . $akhirKolom . $barisHeader);
        $gayaHeader->getFont()->setBold(true);
        $gayaHeader->getFont()->getColor()->setRGB('FFFFFF');
        $gayaHeader->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::WARNA_HEADER);
        $gayaHeader->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getRowDimension($barisHeader)->setRowHeight(24);

        // --- All borders: SETIAP cell di-set 4 sisi ---
        // Catatan: setBorders pada range ternyata hanya menghasilkan garis tepi LUAR
        // (terbukti cell tengah kosong), jadi wajib loop per cell.
        for ($r = $barisHeader; $r <= $akhirData; $r++) {
            foreach (range('A', $akhirKolom) as $kolom) {
                $sisi = $sheet->getStyle($kolom . $r)->getBorders();
                $sisi->getTop()->setBorderStyle(Border::BORDER_THIN);
                $sisi->getBottom()->setBorderStyle(Border::BORDER_THIN);
                $sisi->getLeft()->setBorderStyle(Border::BORDER_THIN);
                $sisi->getRight()->setBorderStyle(Border::BORDER_THIN);
            }
        }

        // --- Isi: kolom angka rata kanan + format nol jadi "-", kolom lain rata tengah ---
        foreach ($kolomAngka as $kolom) {
            $isi = $sheet->getStyle($kolom . ($barisHeader + 1) . ':' . $kolom . $akhirData);
            $isi->getNumberFormat()->setFormatCode('#,##0;-#,##0;"-"');
            $isi->getAlignment()->setHorizontal('right');
        }
        foreach ($kolomTengah as $kolom) {
            $sheet->getStyle($kolom . ($barisHeader + 1) . ':' . $kolom . $akhirData)
                ->getAlignment()->setHorizontal('center');
        }

        // --- Zebra striping baris data (baris total dikecualikan) ---
        for ($r = $barisHeader + 1; $r < $akhirData; $r++) {
            if (($r - $barisHeader) % 2 === 0) {
                $sheet->getStyle('A' . $r . ':' . $akhirKolom . $r)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(self::WARNA_ZEBRA);
            }
        }

        // --- Baris TOTAL: merge label dari A s/d $mergeTotal (default A:B),
        //     latar tint merah, bold + font lebih besar, border atas double ---
        $sheet->mergeCells('A' . $akhirData . ':' . $mergeTotal . $akhirData);
        $gayaTotal = $sheet->getStyle('A' . $akhirData . ':' . $akhirKolom . $akhirData);
        $gayaTotal->getFont()->setBold(true)->setSize(11);
        $gayaTotal->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::WARNA_TOTAL);
        $gayaTotal->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOUBLE);
        $gayaTotal->getAlignment()->setVertical('center');
        $sheet->getRowDimension($akhirData)->setRowHeight(20);

        // --- Padding cell: jarak teks dari border ---
        // Excel tidak punya "padding" seperti CSS: setIndent menggeser teks menjauhi
        // tepi kiri/kanan (1 karakter), dan tinggi baris memberi ruang vertikal.
        // Kolom center TIDAK diindent (Excel mengabaikan indent pada center).
        for ($r = $barisHeader; $r <= $akhirData; $r++) {
            if ($r !== $barisHeader && $r !== $akhirData) {
                $sheet->getRowDimension($r)->setRowHeight(18);   // ruang vertikal baris data
            }
            foreach (range('A', $akhirKolom) as $kolom) {
                $al = $sheet->getStyle($kolom . $r)->getAlignment();
                $al->setVertical('center');
                $h = $al->getHorizontal();
                if ($h === null || $h === 'general') {
                    $al->setHorizontal('left');     // teks polos: rata kiri eksplisit + indent
                    $al->setIndent(1);
                } elseif ($h === 'left' || $h === 'right') {
                    $al->setIndent(1);              // angka kanan / teks kiri: jarak 1 karakter
                }
            }
        }

        // --- Auto-filter di baris header (tanpa freeze/sticky — ralat review) ---
        if ($akhirIsi >= $barisHeader + 1) {
            $sheet->setAutoFilter('A' . $barisHeader . ':' . $akhirKolom . $akhirIsi);
        }

        // --- Lebar kolom: manual untuk yang disebut, auto-size untuk sisanya ---
        foreach ($lebarKolom as $kolom => $lebar) {
            $sheet->getColumnDimension($kolom)->setWidth($lebar);
        }
        foreach (range('A', $akhirKolom) as $kolom) {
            if (!array_key_exists($kolom, $lebarKolom)) {
                $sheet->getColumnDimension($kolom)->setAutoSize(true);
            }
        }

        // --- Siap cetak: A4 landscape, muat 1 halaman lebar, area cetak sesuai tabel ---
        $setup = $sheet->getPageSetup();
        $setup->setPaperSize(9);                 // 9 = A4 (kode OOXML; konstanta tidak ada di v5)
        $setup->setOrientation('landscape');
        $setup->setFitToPage(true);
        $setup->setFitToWidth(1);
        $setup->setFitToHeight(0);
        $setup->setPrintArea('A1:' . $akhirKolom . $akhirData);
    }

    /**
     * Amankan nilai string dari formula injection — nama customer/catatan dari
     * database yang diawali = + - @ bisa dieksekusi Excel sebagai formula.
     * @param array $rows baris data (array of array)
     * @return array data yang sudah diamankan
     */
    private function escapeCells(array $rows): array
    {
        // Pola: satu karakter pemicu formula di awal nilai
        $pemicu = '/^[=+\\-@]/';

        return array_map(function ($row) use ($pemicu) {
            return array_map(function ($v) use ($pemicu) {
                if (is_string($v) && preg_match($pemicu, $v)) {
                    return "'" . $v;                   // prefix kutip tunggal = teks biasa di Excel
                }
                return $v;
            }, $row);
        }, $rows);
    }
}
