<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class GateUserController extends BaseController
{
    protected $gateUserModel;

    public function __construct()
    {
        $this->gateUserModel = model('GateUserModel');
    }

    public function index()
    {
        $data['gateUsers'] = $this->gateUserModel->findAll();
        return view('pages/admin/gate_users/index', $data);
    }

    public function store()
    {
        $rules = [
            'name'      => 'required|max_length[100]',
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[gate_users.username]',
            'password'  => 'required|min_length[8]',
            'phone'     => 'permit_empty|max_length[25]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/gate-users')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $this->gateUserModel->save([
            'name'          => $this->request->getPost('name'),
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'         => $this->request->getPost('phone') ?: null,
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/gate-users')->with('success', 'Pengguna loket berhasil ditambahkan.');
    }

    public function update($id)
    {
        $user = $this->gateUserModel->find($id);
        if (!$user) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $rules = [
            'name'      => 'required|max_length[100]',
            'username'  => "required|alpha_numeric|min_length[3]|max_length[50]|is_unique[gate_users.username,id,$id]",
            'phone'     => 'permit_empty|max_length[25]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/gate-users')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_id', $id);
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'username'  => $this->request->getPost('username'),
            'phone'     => $this->request->getPost('phone') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->gateUserModel->update($id, $data);
        return redirect()->to('/admin/gate-users')->with('success', 'Pengguna loket berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->gateUserModel->delete($id);
        return redirect()->to('/admin/gate-users')->with('success', 'Pengguna loket berhasil dihapus.');
    }
}
