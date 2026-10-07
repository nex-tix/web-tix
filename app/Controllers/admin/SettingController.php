<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $data['settings'] = $this->settingModel->findAll();
        return view('pages/admin/settings/index', $data);
    }

    public function store()
    {
        $rules = [
            'setting_key' => 'required|max_length[50]|is_unique[settings.setting_key]',
            'setting_value' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/settings')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'create');
        }

        $data = [
            'setting_key' => $this->request->getPost('setting_key'),
            'setting_value' => $this->request->getPost('setting_value'),
            'updated_by' => session()->get('user_id'),
        ];

        if ($this->settingModel->insert($data)) {
            return redirect()->to('/admin/settings')->with('success', 'Setting berhasil ditambahkan.');
        }

        return redirect()->to('/admin/settings')->withInput()->with('error', 'Gagal menambahkan setting.')->with('modal', 'create');
    }

    public function update($key)
    {
        $setting = $this->settingModel->find($key);

        if (!$setting) {
            return redirect()->to('/admin/settings')->with('error', 'Setting tidak ditemukan.');
        }

        $rules = [
            'setting_value' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/settings')->withInput()->with('errors', $this->validator->getErrors())->with('modal', 'edit')->with('edit_key', $key);
        }

        $data = [
            'setting_value' => $this->request->getPost('setting_value'),
            'updated_by' => session()->get('user_id'),
        ];

        if ($this->settingModel->update($key, $data)) {
            return redirect()->to('/admin/settings')->with('success', 'Setting berhasil diperbarui.');
        }

        return redirect()->to('/admin/settings')->withInput()->with('error', 'Gagal memperbarui setting.')->with('modal', 'edit')->with('edit_key', $key);
    }

    public function delete($key)
    {
        $setting = $this->settingModel->find($key);

        if (!$setting) {
            return redirect()->to('/admin/settings')->with('error', 'Setting tidak ditemukan.');
        }

        if ($this->settingModel->delete($key)) {
            return redirect()->to('/admin/settings')->with('success', 'Setting berhasil dihapus.');
        }

        return redirect()->to('/admin/settings')->with('error', 'Gagal menghapus setting.');
    }
}
