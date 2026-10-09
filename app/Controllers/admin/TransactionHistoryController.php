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
            'currentRoute'    => 'transaction-history',
        ]);
    }
}
