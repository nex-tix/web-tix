<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\TicketTypeModel;

class TicketController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $ticketTypeModel = new TicketTypeModel();
        $ticketTypes = $ticketTypeModel->orderBy('name', 'ASC')->findAll();

        $dateFrom = trim((string)($this->request->getGet('date_from') ?? ''));
        $dateTo = trim((string)($this->request->getGet('date_to') ?? ''));
        $typeId = (int)($this->request->getGet('type_id') ?? 0);
        $search = trim((string)($this->request->getGet('q') ?? ''));

        $builder = $db->table('tickets t')
            ->select("
                t.*, tx.invoice_code, tx.status AS transaction_status,
                c.name AS customer_name, u.name AS admin_name,
                (SELECT GROUP_CONCAT(DISTINCT tt.name ORDER BY tt.name SEPARATOR ', ')
                   FROM ticket_details td
                   JOIN ticket_types tt ON tt.id = td.ticket_type_id
                  WHERE td.ticket_id = t.id) AS type_names
            ")
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->orderBy('t.created_at', 'DESC');

        if ($dateFrom !== '') {
            $builder->where('DATE(t.created_at) >=', $dateFrom);
        }

        if ($dateTo !== '') {
            $builder->where('DATE(t.created_at) <=', $dateTo);
        }

        if ($typeId > 0) {
            $builder->where(
                'EXISTS (SELECT 1 FROM ticket_details td WHERE td.ticket_id = t.id AND td.ticket_type_id = ' . $typeId . ')',
                null,
                false
            );
        }

        if ($search !== '') {
            $builder->like('t.ticket_code', $search);
        }

        $tickets = $builder->get()->getResultArray();

        return view('pages/admin/data-tiket/index', [
            'tickets'     => $tickets,
            'ticketTypes' => $ticketTypes,
            'filters'     => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'type_id'   => $typeId,
                'q'         => $search,
            ],
            'currentRoute' => 'data-tiket',
        ]);
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();

        $ticket = $db->table('tickets t')
            ->select("
                t.*, tx.invoice_code, tx.payment_method, tx.subtotal AS tx_subtotal,
                tx.discount_amount AS tx_discount_amount, tx.total_amount AS tx_total_amount,
                tx.status AS transaction_status, tx.created_at AS transaction_created_at,
                c.name AS customer_name, c.origin_type, c.origin, c.phone,
                u.name AS admin_name
            ")
            ->join('transactions tx', 'tx.id = t.transaction_id', 'left')
            ->join('customers c', 'c.id = tx.customer_id', 'left')
            ->join('users u', 'u.id = tx.created_by', 'left')
            ->where('t.id', $id)
            ->get()->getRowArray();

        if (!$ticket) {
            return redirect()->to('/admin/data-tiket')->with('error', 'Tiket tidak ditemukan.');
        }

        $details = $db->table('ticket_details td')
            ->select('td.*, tt.name AS type_name, tt.ticket_category')
            ->join('ticket_types tt', 'tt.id = td.ticket_type_id', 'left')
            ->where('td.ticket_id', $id)
            ->orderBy('tt.name', 'ASC')
            ->get()->getResultArray();

        $typeNames = array_values(array_unique(array_filter(array_column($details, 'type_name'))));

        return view('pages/admin/data-tiket/detail', [
            'ticket'       => $ticket,
            'details'      => $details,
            'typeNames'    => $typeNames,
            'currentRoute' => 'data-tiket',
        ]);
    }
}
