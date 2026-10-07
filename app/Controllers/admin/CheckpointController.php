<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CheckpointModel;

class CheckpointController extends BaseController
{
    protected $checkpointModel;

    public function __construct()
    {
        $this->checkpointModel = new CheckpointModel();
    }

    public function index()
    {
        $data['checkpoints'] = $this->checkpointModel->findAll();
        return view('pages/admin/checkpoints/index', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max_length[50]|is_unique[checkpoints.name]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/checkpoints')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->checkpointModel->insert($data)) {
            return redirect()->to('/admin/checkpoints')->with('success', 'Checkpoint berhasil ditambahkan.');
        }

        return redirect()->to('/admin/checkpoints')->withInput()->with('error', 'Gagal menambahkan checkpoint.')->with('modal', 'create');
    }

    public function update($id)
    {
        $checkpoint = $this->checkpointModel->find($id);

        if (!$checkpoint) {
            return redirect()->to('/admin/checkpoints')->with('error', 'Checkpoint tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|max_length[50]|is_unique[checkpoints.name,id,' . $id . ']',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/checkpoints')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_id', $id);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->checkpointModel->update($id, $data)) {
            return redirect()->to('/admin/checkpoints')->with('success', 'Checkpoint berhasil diperbarui.');
        }

        return redirect()->to('/admin/checkpoints')->withInput()->with('error', 'Gagal memperbarui checkpoint.')->with('modal', 'edit')->with('edit_id', $id);
    }

    public function delete($id)
    {
        $checkpoint = $this->checkpointModel->find($id);

        if (!$checkpoint) {
            return redirect()->to('/admin/checkpoints')->with('error', 'Checkpoint tidak ditemukan.');
        }

        if ($this->checkpointModel->delete($id)) {
            return redirect()->to('/admin/checkpoints')->with('success', 'Checkpoint berhasil dihapus.');
        }

        return redirect()->to('/admin/checkpoints')->with('error', 'Gagal menghapus checkpoint.');
    }
}
