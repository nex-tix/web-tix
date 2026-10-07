<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = model('UserModel');
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('pages/admin/users/index', $data);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|max_length[100]',
            'username' => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[admin]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $this->userModel->save([
            'name'          => $this->request->getPost('name'),
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $rules = [
            'name'     => 'required|max_length[100]',
            'username' => "required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,id,$id]",
            'role'     => 'required|in_list[admin,staff]',
            'is_active' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_id', $id);
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'username'  => $this->request->getPost('username'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate.');
    }

    public function resetPassword($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $rules = [
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = ['password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)];
        $this->userModel->update($id, $data);
        return redirect()->to('/admin/users')->with('success', 'Password berhasil direset.');
    }

    public function delete($id)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }
}
