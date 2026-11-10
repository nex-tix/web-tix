<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuideModel;

class GuideController extends BaseController
{
    protected GuideModel $guideModel;

    public function __construct()
    {
        $this->guideModel = new GuideModel();
    }

    public function index()
    {
        $data = [
            'activeKey' => 'guide',
            'crumbs'    => 'Users | Guides',
            'guides'    => $this->guideModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('pages/admin/guides/index', $data);
    }

    public function store()
    {
        $code = trim((string) $this->request->getPost('code'));
        if ($code === '') {
            $code = $this->guideModel->generateNextCode();
        }

        $rules = [
            'code'   => 'required|max_length[20]|is_unique[guides.code]',
            'name'   => 'required|min_length[2]|max_length[100]',
            'phone'  => 'permit_empty|max_length[25]',
            'email'  => 'permit_empty|valid_email|max_length[100]',
            'gender' => 'permit_empty|in_list[L,P]',
            'status' => 'permit_empty|in_list[active,inactive]',
        ];

        $inputData = [
            'code'      => $code,
            'name'      => trim((string) $this->request->getPost('name')),
            'phone'     => trim((string) $this->request->getPost('phone')) ?: null,
            'email'     => trim((string) $this->request->getPost('email')) ?: null,
            'gender'    => $this->request->getPost('gender') ?: null,
            'status'    => $this->request->getPost('status') ?: 'active',
            'notes'     => trim((string) $this->request->getPost('notes')) ?: null,
            'languages' => $this->parseLanguages((string) $this->request->getPost('languages')),
        ];

        if (!$this->validateData($inputData, $rules)) {
            return redirect()->to('/admin/guides')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('modal', 'create');
        }

        if ($this->guideModel->insert($inputData)) {
            return redirect()->to('/admin/guides')->with('success', 'Guide berhasil ditambahkan.');
        }

        return redirect()->to('/admin/guides')
            ->withInput()
            ->with('errors', $this->guideModel->errors())
            ->with('error', 'Gagal menambahkan guide.')
            ->with('modal', 'create');
    }

    public function update($id)
    {
        $guide = $this->guideModel->find($id);

        if (!$guide) {
            return redirect()->to('/admin/guides')->with('error', 'Guide tidak ditemukan.');
        }

        $code = trim((string) $this->request->getPost('code'));
        if ($code === '') {
            $code = $guide['code'];
        }

        $rules = [
            'code'   => "required|max_length[20]|is_unique[guides.code,id,{$id}]",
            'name'   => 'required|min_length[2]|max_length[100]',
            'phone'  => 'permit_empty|max_length[25]',
            'email'  => 'permit_empty|valid_email|max_length[100]',
            'gender' => 'permit_empty|in_list[L,P]',
            'status' => 'permit_empty|in_list[active,inactive]',
        ];

        $inputData = [
            'code'      => $code,
            'name'      => trim((string) $this->request->getPost('name')),
            'phone'     => trim((string) $this->request->getPost('phone')) ?: null,
            'email'     => trim((string) $this->request->getPost('email')) ?: null,
            'gender'    => $this->request->getPost('gender') ?: null,
            'status'    => $this->request->getPost('status') ?: 'active',
            'notes'     => trim((string) $this->request->getPost('notes')) ?: null,
            'languages' => $this->parseLanguages((string) $this->request->getPost('languages')),
        ];

        if (!$this->validateData($inputData, $rules)) {
            return redirect()->to('/admin/guides')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('modal', 'edit')
                ->with('edit_id', $id);
        }

        if ($this->guideModel->update($id, $inputData)) {
            return redirect()->to('/admin/guides')->with('success', 'Guide berhasil diperbarui.');
        }

        return redirect()->to('/admin/guides')
            ->withInput()
            ->with('errors', $this->guideModel->errors())
            ->with('error', 'Gagal memperbarui guide.')
            ->with('modal', 'edit')
            ->with('edit_id', $id);
    }

    public function delete($id)
    {
        $guide = $this->guideModel->find($id);

        if (!$guide) {
            return redirect()->to('/admin/guides')->with('error', 'Guide tidak ditemukan.');
        }

        if ($this->guideModel->delete($id)) {
            return redirect()->to('/admin/guides')->with('success', 'Guide berhasil dihapus.');
        }

        return redirect()->to('/admin/guides')->with('error', 'Gagal menghapus guide.');
    }

    /**
     * Konversi input string bahasa (dipisahkan koma) menjadi array
     */
    protected function parseLanguages(string $input): ?array
    {
        $input = trim($input);
        if ($input === '') {
            return null;
        }

        $items = array_map('trim', explode(',', $input));
        $filtered = array_filter($items, fn($item) => $item !== '');

        return !empty($filtered) ? array_values($filtered) : null;
    }
}

