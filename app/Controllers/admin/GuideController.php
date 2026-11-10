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
            'guides'    => $this->guideModel->orderBy('id', 'ASC')->findAll(),
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
            'code'      => 'required|max_length[20]|is_unique[guides.code]',
            'name'      => 'required|min_length[2]|max_length[100]',
            'phone'     => 'required|max_length[25]',
            'email'     => 'required|valid_email|max_length[100]',
            'gender'    => 'required|in_list[L,P]',
            'status'    => 'required|in_list[active,inactive]',
            'languages' => 'required',
            'notes'     => 'permit_empty|max_length[500]',
        ];

        $messages = [
            'code' => [
                'required'   => 'Kolom kode guide tidak boleh kosong.',
                'max_length' => 'Kode guide maksimal 20 karakter.',
                'is_unique'  => 'Kode guide ini sudah terdaftar.',
            ],
            'name' => [
                'required'   => 'Kolom nama guide tidak boleh kosong.',
                'min_length' => 'Nama guide minimal 2 karakter.',
                'max_length' => 'Nama guide maksimal 100 karakter.',
            ],
            'phone' => [
                'required'   => 'Kolom no. HP / WhatsApp tidak boleh kosong.',
                'max_length' => 'No. HP / WhatsApp maksimal 25 karakter.',
            ],
            'email' => [
                'required'    => 'Kolom email tidak boleh kosong.',
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 100 karakter.',
            ],
            'gender' => [
                'required' => 'Kolom jenis kelamin tidak boleh kosong.',
                'in_list'  => 'Pilihan jenis kelamin harus Laki-laki atau Perempuan.',
            ],
            'status' => [
                'required' => 'Kolom status tidak boleh kosong.',
                'in_list'  => 'Pilihan status harus Aktif atau Nonaktif.',
            ],
            'languages' => [
                'required' => 'Kolom bahasa yang dikuasai tidak boleh kosong.',
            ],
        ];

        $inputData = [
            'code'      => $code,
            'name'      => trim((string) $this->request->getPost('name')),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'email'     => trim((string) $this->request->getPost('email')),
            'gender'    => (string) $this->request->getPost('gender'),
            'status'    => (string) ($this->request->getPost('status') ?: 'active'),
            'notes'     => trim((string) $this->request->getPost('notes')),
            'languages' => $this->parseLanguages((string) $this->request->getPost('languages')),
        ];

        if (!$this->validateData($inputData, $rules, $messages)) {
            return redirect()->to('/admin/guides')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Kolom tidak boleh kosong.')
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
            'code'      => "required|max_length[20]|is_unique[guides.code,id,{$id}]",
            'name'      => 'required|min_length[2]|max_length[100]',
            'phone'     => 'required|max_length[25]',
            'email'     => 'required|valid_email|max_length[100]',
            'gender'    => 'required|in_list[L,P]',
            'status'    => 'required|in_list[active,inactive]',
            'languages' => 'required',
            'notes'     => 'permit_empty|max_length[500]',
        ];

        $messages = [
            'code' => [
                'required'   => 'Kolom kode guide tidak boleh kosong.',
                'max_length' => 'Kode guide maksimal 20 karakter.',
                'is_unique'  => 'Kode guide ini sudah terdaftar.',
            ],
            'name' => [
                'required'   => 'Kolom nama guide tidak boleh kosong.',
                'min_length' => 'Nama guide minimal 2 karakter.',
                'max_length' => 'Nama guide maksimal 100 karakter.',
            ],
            'phone' => [
                'required'   => 'Kolom no. HP / WhatsApp tidak boleh kosong.',
                'max_length' => 'No. HP / WhatsApp maksimal 25 karakter.',
            ],
            'email' => [
                'required'    => 'Kolom email tidak boleh kosong.',
                'valid_email' => 'Format email tidak valid.',
                'max_length'  => 'Email maksimal 100 karakter.',
            ],
            'gender' => [
                'required' => 'Kolom jenis kelamin tidak boleh kosong.',
                'in_list'  => 'Pilihan jenis kelamin harus Laki-laki atau Perempuan.',
            ],
            'status' => [
                'required' => 'Kolom status tidak boleh kosong.',
                'in_list'  => 'Pilihan status harus Aktif atau Nonaktif.',
            ],
            'languages' => [
                'required' => 'Kolom bahasa yang dikuasai tidak boleh kosong.',
            ],
        ];

        $inputData = [
            'id'        => $id,
            'code'      => $code,
            'name'      => trim((string) $this->request->getPost('name')),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'email'     => trim((string) $this->request->getPost('email')),
            'gender'    => (string) $this->request->getPost('gender'),
            'status'    => (string) ($this->request->getPost('status') ?: 'active'),
            'notes'     => trim((string) $this->request->getPost('notes')),
            'languages' => $this->parseLanguages((string) $this->request->getPost('languages')),
        ];

        if (!$this->validateData($inputData, $rules, $messages)) {
            return redirect()->to('/admin/guides')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Kolom tidak boleh kosong.')
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

