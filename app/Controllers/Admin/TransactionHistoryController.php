<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\TicketModel;
use App\Models\TicketDetailModel;
use App\Models\TicketTypeModel;
use App\Models\SettingModel;
use App\Models\PrintLogModel;

class TransactionHistoryController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userModel = new UserModel();

        $users = $userModel->findAll();

        $date = trim((string)($this->request->getGet('date') ?? ''));
        $payment = trim((string)($this->request->getGet('payment') ?? ''));
        $userId = (int)($this->request->getGet('user_id') ?? 0);
        $search = trim((string)($this->request->getGet('q') ?? ''));

        $builder = $db->table('transactions tx')
            ->select('tx.*, c.name as customer_name, c.origin_type, c.origin, u.name as admin_name, COUNT(DISTINCT t.id) as ticket_count, COALESCE(SUM(t.total_visitors), 0) as total_visitors')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->join('tickets t', 't.transaction_id = tx.id', 'left')
            ->groupBy('tx.id')
            ->orderBy('tx.created_at', 'DESC');

        if ($date !== '') {
            $builder->where('DATE(tx.created_at)', $date);
        }

        if ($payment !== '') {
            $builder->where('tx.payment_method', $payment);
        }

        if ($userId > 0) {
            $builder->where('tx.created_by', $userId);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('tx.invoice_code', $search)
                ->orLike('c.name', $search)
                ->groupEnd();
        }

        $transactions = $builder->get()->getResultArray();

        return view('pages/admin/transaction-history/index', [
            'activeKey' => 'riwayat',
            'crumbs'    => 'Operasional | Riwayat Transaksi',
            'transactions' => $transactions,
            'users'        => $users,
            'filters'      => [
                'date'    => $date,
                'payment' => $payment,
                'user_id' => $userId,
                'q'       => $search,
            ],
            'currentRoute' => 'transaction-history',
        ]);
    }

    public function detail($id)
    {
        $transactionModel = new TransactionModel();
        $customerModel = new CustomerModel();
        $ticketModel = new TicketModel();
        $ticketDetailModel = new TicketDetailModel();
        $ticketTypeModel = new TicketTypeModel();
        $userModel = new UserModel();

        $transaction = $transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Transaksi tidak ditemukan.');
        }

        $customer = $customerModel->find($transaction['customer_id']);
        $adminUser = $userModel->find($transaction['created_by']);

        $tickets = $ticketModel->where('transaction_id', $id)->orderBy('id', 'ASC')->findAll();
        $ticketIds = array_column($tickets, 'id');

        $allDetails = [];
        if (!empty($ticketIds)) {
            $allDetails = $ticketDetailModel->whereIn('ticket_id', $ticketIds)->findAll();
        }

        $detailsByTicket = [];
        foreach ($allDetails as $detail) {
            $detailsByTicket[$detail['ticket_id']][] = $detail;
        }

        foreach ($tickets as &$ticket) {
            $ticket['details'] = $detailsByTicket[$ticket['id']] ?? [];
            $subtotal = 0;
            foreach ($ticket['details'] as $det) {
                $subtotal += (float)($det['subtotal'] ?? 0);
            }
            $ticket['computed_subtotal'] = $subtotal;
        }
        unset($ticket);

        $ticketTypeIds = array_unique(array_column($allDetails, 'ticket_type_id'));
        $ticketTypesById = [];
        if (!empty($ticketTypeIds)) {
            $ticketTypes = $ticketTypeModel->whereIn('id', $ticketTypeIds)->findAll();
            foreach ($ticketTypes as $tt) {
                $ticketTypesById[$tt['id']] = $tt;
            }
        }

        return view('pages/admin/transaction-history/detail', [
            'transaction'     => $transaction,
            'customer'        => $customer,
            'adminUser'       => $adminUser,
            'tickets'         => $tickets,
            'ticketTypesById' => $ticketTypesById,
            'activeKey'       => 'riwayat',               // aktifkan navlink Riwayat Penjualan di halaman detail
            'crumbs'          => 'Operasional | Riwayat Transaksi | Detail',
            'currentRoute'    => 'transaction-history',
        ]);
    }

    public function void($id)
    {
        $transactionModel = new TransactionModel();
        $ticketModel = new TicketModel();

        $transaction = $transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($transaction['status'] === 'cancelled') {
            return redirect()->to('/admin/transaction-history')->with('error', 'Transaksi sudah dibatalkan.');
        }

        $voidReason = trim((string)($this->request->getPost('void_reason') ?? ''));
        if ($voidReason === '') {
            return redirect()->back()->with('error', 'Alasan void wajib diisi.')->withInput();
        }

        $usedTickets = $ticketModel
            ->where('transaction_id', $id)
            ->where('status', 'used')
            ->countAllResults();

        if ($usedTickets > 0) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Tidak dapat void: ada tiket yang sudah digunakan.');
        }

        $userId = (int) session()->get('user_id');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $lockedTickets = $db->query(
                'SELECT id, status FROM tickets WHERE transaction_id = ? FOR UPDATE',
                [$id]
            )->getResultArray();

            foreach ($lockedTickets as $lockedTicket) {
                if ($lockedTicket['status'] === 'used') {
                    $db->transRollback();
                    return redirect()->to('/admin/transaction-history')
                        ->with('error', 'Tidak dapat void: ada tiket yang sudah digunakan.');
                }
            }

            $updated = $transactionModel->update($id, [
                'status'       => 'cancelled',
                'voided_by'    => $userId,
                'voided_at'    => date('Y-m-d H:i:s'),
                'void_reason'  => $voidReason,
            ]);

            if (!$updated) {
                throw new \RuntimeException(
                    'Gagal update transaksi: ' . implode('; ', $transactionModel->errors() ?? [])
                );
            }

            $db->table('tickets')->where('transaction_id', $id)->update(['status' => 'cancelled']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/admin/transaction-history')->with('error', 'Gagal membatalkan transaksi.');
            }

            return redirect()->to('/admin/transaction-history')->with('success', 'Transaksi berhasil dibatalkan (void).');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/admin/transaction-history')->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Data tiket yang layak cetak ulang untuk 1 transaksi (endpoint AJAX modal di halaman riwayat).
     * Filter sama dengan PosController::reprint lama: tiket aktif + berlaku hari ini.
     */
    public function reprintData($transactionId)
    {
        $transactionModel = new TransactionModel();
        $ticketModel = new TicketModel();

        $transaction = $transactionModel->find($transactionId);
        if (!$transaction) {
            return response()->setJSON(['error' => 'Transaksi tidak ditemukan.'], 404);
        }

        $tickets = $ticketModel
            ->where('transaction_id', $transactionId)
            ->where('status', 'active')
            ->where('valid_date', date('Y-m-d'))
            ->orderBy('id', 'ASC')
            ->findAll();

        $items = [];
        foreach ($tickets as $t) {
            $items[] = [
                'id'              => (int) $t['id'],
                'ticket_code'     => (string) $t['ticket_code'],
                'ticket_category' => (string) $t['ticket_category'],
                'total_visitors'  => (int) $t['total_visitors'],
                'valid_date'      => (string) $t['valid_date'],
            ];
        }

        return response()->setJSON([
            'invoice_code' => (string) $transaction['invoice_code'],
            'tickets'      => $items,
        ]);
    }

    /**
     * Eksekusi cetak ulang 1 tiket dari modal riwayat. Validasi sama dengan PosController::doReprint lama;
     * semua error redirect balik ke halaman riwayat, sukses buka halaman receipt supaya tiket tercetak.
     */
    public function reprint($ticketId)
    {
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find($ticketId);

        if (!$ticket) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Tiket tidak ditemukan.');
        }

        if ($ticket['status'] !== 'active') {
            return redirect()->to('/admin/transaction-history')->with('error', 'Tiket tidak aktif. Tidak dapat dicetak ulang.');
        }

        if ($ticket['valid_date'] !== date('Y-m-d')) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Tiket tidak berlaku hari ini. Tidak dapat dicetak ulang.');
        }

        $reason = trim((string) ($this->request->getPost('reprint_reason') ?? ''));
        if ($reason === '') {
            return redirect()->back()->with('error', 'Alasan cetak ulang wajib diisi.')->withInput();
        }

        $printLogModel = new PrintLogModel();
        $printLogModel->insert([
            'ticket_id'      => $ticketId,
            'printed_by'     => (int) session()->get('user_id'),
            'print_type'     => 'reprint',
            'printed_at'     => date('Y-m-d H:i:s'),
            'reprint_reason' => $reason,
        ], false);

        return redirect()->to(base_url("admin/pos/receipt/{$ticket['transaction_id']}?ticket_id={$ticketId}"))
            ->with('success', 'Cetak ulang berhasil. Tiket siap dicetak.');
    }

    /**
     * Ubah payment_method transaksi selesai (cash ↔ debit) dari modal riwayat.
     * Hanya field payment_method yang boleh berubah; status harus completed.
     */
    public function updatePayment($id)
    {
        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($transaction['status'] !== 'completed') {
            return redirect()->to('/admin/transaction-history')->with('error', 'Hanya transaksi selesai yang bisa diubah metode bayarnya.');
        }

        $paymentMethod = (string) $this->request->getPost('payment_method');
        if (!in_array($paymentMethod, ['cash', 'debit'], true)) {
            return redirect()->back()->with('error', 'Metode bayar tidak valid.')->withInput();
        }

        if ($paymentMethod === $transaction['payment_method']) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Metode bayar tidak berubah.');
        }

        if (!$transactionModel->update($id, ['payment_method' => $paymentMethod])) {
            return redirect()->to('/admin/transaction-history')->with('error', 'Gagal memperbarui metode bayar.');
        }

        $label = $paymentMethod === 'cash' ? 'Tunai' : 'Debit';
        return redirect()->to('/admin/transaction-history')->with('success', 'Metode bayar ' . $transaction['invoice_code'] . ' diubah menjadi ' . $label . '.');
    }
}
