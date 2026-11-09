<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BankModel;

class BankController extends BaseController
{
    protected $bankModel;

    public function __construct()
    {
        $this->bankModel = new BankModel();
    }

    public function index()
    {
        $data = [
            'activeKey' => 'bank',
            'crumbs'    => 'Operasional | Bank',
            'banks'     => $this->bankModel->findAll(),
        ];
        return view('pages/admin/bank/index', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max_length[150]|is_unique[banks.name]',
            'code' => 'required|max_length[20]|is_unique[banks.code]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/banks')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->bankModel->insert($data)) {
            return redirect()->to('/admin/banks')->with('success', 'Bank berhasil ditambahkan.');
        }

        return redirect()->to('/admin/banks')->withInput()->with('errors', $this->bankModel->errors())->with('error', 'Gagal menambahkan bank.')->with('modal', 'create');
    }

    public function update($id)
    {
        $bank = $this->bankModel->find($id);

        if (!$bank) {
            return redirect()->to('/admin/banks')->with('error', 'Bank tidak ditemukan.');
        }

        $rules = [
            'name' => "required|max_length[150]|is_unique[banks.name,id,{$id}]",
            'code' => "required|max_length[20]|is_unique[banks.code,id,{$id}]",
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/banks')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_id', $id);
        }

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->bankModel->update($id, $data)) {
            return redirect()->to('/admin/banks')->with('success', 'Bank berhasil diperbarui.');
        }

        return redirect()->to('/admin/banks')->withInput()->with('errors', $this->bankModel->errors())->with('error', 'Gagal memperbarui bank.')->with('modal', 'edit')->with('edit_id', $id);
    }

    public function delete($id)
    {
        $bank = $this->bankModel->find($id);

        if (!$bank) {
            return redirect()->to('/admin/banks')->with('error', 'Bank tidak ditemukan.');
        }

        if ($this->bankModel->delete($id)) {
            return redirect()->to('/admin/banks')->with('success', 'Bank berhasil dihapus.');
        }

        return redirect()->to('/admin/banks')->with('error', 'Gagal menghapus bank.');
    }
}