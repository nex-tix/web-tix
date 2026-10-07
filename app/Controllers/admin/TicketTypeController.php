<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TicketTypeModel;

class TicketTypeController extends BaseController
{
    protected $ticketTypeModel;

    public function __construct()
    {
        $this->ticketTypeModel = new TicketTypeModel();
    }

    public function index()
    {
        $data['ticketTypes'] = $this->ticketTypeModel->findAll();
        return view('pages/admin/ticket_types/index', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'ticket_category' => 'required|in_list[individual,group]',
            'discount_percent' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/ticket-types')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'ticket_category' => $this->request->getPost('ticket_category'),
            'discount_percent' => $this->request->getPost('discount_percent') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->ticketTypeModel->insert($data)) {
            return redirect()->to('/admin/ticket-types')->with('success', 'Jenis tiket berhasil ditambahkan.');
        }

        return redirect()->to('/admin/ticket-types')->withInput()->with('error', 'Gagal menambahkan jenis tiket.')->with('modal', 'create');
    }

    public function update($id)
    {
        $ticketType = $this->ticketTypeModel->find($id);

        if (!$ticketType) {
            return redirect()->to('/admin/ticket-types')->with('error', 'Jenis tiket tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'ticket_category' => 'required|in_list[individual,group]',
            'discount_percent' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/ticket-types')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_id', $id);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'ticket_category' => $this->request->getPost('ticket_category'),
            'discount_percent' => $this->request->getPost('discount_percent') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->ticketTypeModel->update($id, $data)) {
            return redirect()->to('/admin/ticket-types')->with('success', 'Jenis tiket berhasil diperbarui.');
        }

        return redirect()->to('/admin/ticket-types')->withInput()->with('error', 'Gagal memperbarui jenis tiket.')->with('modal', 'edit')->with('edit_id', $id);
    }

    public function delete($id)
    {
        $ticketType = $this->ticketTypeModel->find($id);

        if (!$ticketType) {
            return redirect()->to('/admin/ticket-types')->with('error', 'Jenis tiket tidak ditemukan.');
        }

        if ($this->ticketTypeModel->delete($id)) {
            return redirect()->to('/admin/ticket-types')->with('success', 'Jenis tiket berhasil dihapus.');
        }

        return redirect()->to('/admin/ticket-types')->with('error', 'Gagal menghapus jenis tiket.');
    }
}
