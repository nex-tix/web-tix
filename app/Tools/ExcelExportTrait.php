<?php

namespace App\Tools;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Helper export Excel — dipakai ReportController & ExpenseController.
 * Berisi HANYA hal yang sama di semua endpoint export (format & pengiriman file).
 * Logika data (param, tanggal, query) tetap di controller masing-masing.
 */
trait ExcelExportTrait
{
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
     * Styling standar semua laporan Excel: judul kolom tebal, freeze baris 1,
     * lebar kolom otomatis, dan format angka untuk kolom nominal.
     *
     * @param Worksheet $sheet     sheet aktif
     * @param array     $kolomUang huruf kolom nominal, mis. ['F'] atau ['E','F']
     */
    private function styleSheet(Worksheet $sheet, array $kolomUang = []): void
    {
        // getHighestDataRow() di PhpSpreadsheet 5.x langsung mengembalikan int (nomor baris),
        // getHighestDataColumn() mengembalikan huruf kolom — tidak perlu ->getEndRow() lagi
        $akhirData  = $sheet->getHighestDataRow();     // baris terakhir berisi data (mis. 88)
        $akhirKolom = $sheet->getHighestDataColumn();   // huruf kolom terakhir (mis. 'F')

        // Judul baris 1: tebal, latar abu-abu, rata tengah
        $sheet->getStyle('A1:' . $akhirKolom . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $akhirKolom . '1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9D9D9');
        $sheet->getStyle('A1:' . $akhirKolom . '1')->getAlignment()->setHorizontal('center');

        // Judul tetap terlihat saat digeser ke bawah
        $sheet->freezePane('A2');

        // Lebar kolom menyesuaikan isi terpanjang
        foreach (range('A', $akhirKolom) as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        // Kolom nominal: angka polos (bukan teks) supaya bisa dijumlahkan di Excel
        foreach ($kolomUang as $kolom) {
            $sheet->getStyle($kolom . '2:' . $kolom . $akhirData)
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }
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
