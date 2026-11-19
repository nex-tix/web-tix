<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BankModel;
use App\Models\ExpenseCategoryModel;
use App\Models\ExpenseModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExpenseController extends BaseController
{
    protected ExpenseModel $expenseModel;
    protected ExpenseCategoryModel $categoryModel;
    protected BankModel $bankModel;

    public function __construct()
    {
        $this->expenseModel  = new ExpenseModel();
        $this->categoryModel = new ExpenseCategoryModel();
        $this->bankModel     = new BankModel();
    }

    /**
     * Halaman Utama Pengeluaran (Tabel, Filter, Stat Cards, & Modal)
     */
    public function index()
    {
        $startInput    = $this->request->getGet('start');
        $endInput      = $this->request->getGet('end');
        $categoryId    = $this->request->getGet('category_id');
        $paymentMethod = $this->request->getGet('payment_method');
        $search        = trim((string)($this->request->getGet('q') ?? ''));

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $categories = $this->categoryModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $banks      = $this->bankModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        $builder = $this->expenseModel->builder('expenses e')
            ->select('
                e.*,
                ec.name AS category_name,
                b.name AS bank_name,
                b.code AS bank_code,
                u.name AS creator_name,
                u.username AS creator_username
            ')
            ->join('expense_categories ec', 'ec.id = e.category_id', 'left')
            ->join('banks b', 'b.id = e.bank_id', 'left')
            ->join('users u', 'u.id = e.created_by', 'left');

        if ($startDate !== null) {
            $builder->where('e.expense_date >=', $startDate);
        }
        if ($endDate !== null) {
            $builder->where('e.expense_date <=', $endDate);
        }

        if (!empty($categoryId)) {
            $builder->where('e.category_id', $categoryId);
        }
        if (!empty($paymentMethod)) {
            $builder->where('e.payment_method', $paymentMethod);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('e.expense_code', $search)
                ->orLike('e.title', $search)
                ->orLike('e.notes', $search)
                ->orLike('ec.name', $search)
                ->orLike('b.name', $search)
                ->orLike('b.code', $search)
                ->groupEnd();
        }

        $expenses = $builder->orderBy('e.expense_date', 'ASC')
            ->orderBy('e.id', 'ASC')
            ->get()
            ->getResultArray();

        // Hitung KPI Ringkasan
        $totalPengeluaran = 0;
        $totalCash        = 0;
        $totalTransfer    = 0;
        $totalGuideSalary = 0;

        foreach ($expenses as $exp) {
            $amt = (float) ($exp['amount'] ?? 0);
            $totalPengeluaran += $amt;

            if (($exp['payment_method'] ?? '') === 'cash') {
                $totalCash += $amt;
            } elseif (($exp['payment_method'] ?? '') === 'transfer') {
                $totalTransfer += $amt;
            }

            if (stripos($exp['category_name'] ?? '', 'guide') !== false) {
                $totalGuideSalary += $amt;
            }
        }

        $data = [
            'activeKey'        => 'expense',
            'crumbs'           => 'Akuntansi | Pengeluaran',
            'expenses'         => $expenses,
            'categories'       => $categories,
            'banks'            => $banks,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'filters'          => [
                'category_id'    => $categoryId,
                'payment_method' => $paymentMethod,
                'q'              => $search,
            ],
            'kpi'              => [
                'total_pengeluaran' => $totalPengeluaran,
                'total_cash'        => $totalCash,
                'total_transfer'    => $totalTransfer,
                'total_guide'       => $totalGuideSalary,
                'total_transaksi'   => count($expenses),
            ],
        ];

        return view('pages/admin/expense/index', $data);
    }

    /**
     * Simpan Catatan Pengeluaran Baru
     */
    public function store()
    {
        $rawAmount = $this->request->getPost('amount');
        // Bersihkan format rupiah jika ada titik atau prefix
        $cleanAmount = preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string) $rawAmount));

        $paymentMethod = $this->request->getPost('payment_method') ?: 'cash';
        $bankId        = $this->request->getPost('bank_id');

        $rules = [
            'expense_date'   => 'required|valid_date[Y-m-d]',
            'category_id'    => 'required|is_not_unique[expense_categories.id]',
            'title'          => 'required|max_length[255]',
            'amount'         => 'required|numeric|greater_than[0]',
            'payment_method' => 'required|in_list[cash,transfer]',
        ];

        if ($paymentMethod === 'transfer') {
            $rules['bank_id'] = 'required|is_not_unique[banks.id]';
        }

        $inputData = [
            'expense_date'   => $this->request->getPost('expense_date'),
            'category_id'    => $this->request->getPost('category_id'),
            'title'          => trim((string) $this->request->getPost('title')),
            'amount'         => (float) $cleanAmount,
            'payment_method' => $paymentMethod,
            'bank_id'        => $paymentMethod === 'transfer' ? (!empty($bankId) ? (int)$bankId : null) : null,
            'notes'          => trim((string) $this->request->getPost('notes')),
        ];

        if (!$this->validateData($inputData, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('modal', 'create');
        }

        $inputData['expense_code'] = $this->expenseModel->generateNextExpenseCode();
        $inputData['created_by']   = session()->get('user_id') ?: null;

        if ($this->expenseModel->insert($inputData)) {
            return redirect()->to('/admin/expenses')->with('success', 'Catatan pengeluaran ' . $inputData['expense_code'] . ' berhasil disimpan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan pengeluaran. Silakan coba lagi.')
            ->with('modal', 'create');
    }

    /**
     * Simpan Kategori Pengeluaran Baru secara Instan (AJAX / Form)
     */
    public function storeCategory()
    {
        $name        = trim((string) $this->request->getPost('name'));
        $description = trim((string) $this->request->getPost('description'));

        if (empty($name)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Nama kategori wajib diisi.',
                ]);
            }
            return redirect()->back()->with('error', 'Nama kategori wajib diisi.');
        }

        // Cek duplikasi nama
        $existing = $this->categoryModel->where('LOWER(name)', strtolower($name))->first();
        if ($existing) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success'  => true,
                    'category' => $existing,
                    'message'  => 'Kategori sudah ada dan terpilih.',
                ]);
            }
            return redirect()->back()->with('error', 'Kategori ini sudah terdaftar.');
        }

        $newId = $this->categoryModel->insert([
            'name'        => $name,
            'description' => $description ?: null,
            'is_active'   => 1,
        ]);

        if ($newId) {
            $created = $this->categoryModel->find($newId);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success'  => true,
                    'category' => $created,
                    'message'  => 'Kategori baru berhasil ditambahkan.',
                ]);
            }
            return redirect()->to('/admin/expenses')->with('success', 'Kategori baru berhasil ditambahkan.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan kategori baru.',
            ]);
        }
        return redirect()->back()->with('error', 'Gagal menyimpan kategori baru.');
    }

    /**
     * Hapus Catatan Pengeluaran
     */
    public function delete($id)
    {
        $expense = $this->expenseModel->find($id);
        if (!$expense) {
            return redirect()->to('/admin/expenses')->with('error', 'Catatan pengeluaran tidak ditemukan.');
        }

        if ($this->expenseModel->delete($id)) {
            return redirect()->to('/admin/expenses')->with('success', 'Catatan pengeluaran ' . esc($expense['expense_code']) . ' berhasil dihapus.');
        }

        return redirect()->to('/admin/expenses')->with('error', 'Gagal menghapus pengeluaran.');
    }

    /**
     * Ekspor Laporan Pengeluaran ke PDF
     */
    public function exportPdf()
    {
        $startInput    = $this->request->getGet('start');
        $endInput      = $this->request->getGet('end');
        $categoryId    = $this->request->getGet('category_id');
        $paymentMethod = $this->request->getGet('payment_method');
        $search        = trim((string)($this->request->getGet('q') ?? ''));

        $range     = $this->resolveDateRange($startInput, $endInput);
        $startDate = $range['startDate'];
        $endDate   = $range['endDate'];

        $selectedCategory = !empty($categoryId) ? $this->categoryModel->find($categoryId) : null;

        $builder = $this->expenseModel->builder('expenses e')
            ->select('
                e.*,
                ec.name AS category_name,
                b.name AS bank_name,
                b.code AS bank_code,
                u.name AS creator_name,
                u.username AS creator_username
            ')
            ->join('expense_categories ec', 'ec.id = e.category_id', 'left')
            ->join('banks b', 'b.id = e.bank_id', 'left')
            ->join('users u', 'u.id = e.created_by', 'left');

        if ($startDate !== null) {
            $builder->where('e.expense_date >=', $startDate);
        }
        if ($endDate !== null) {
            $builder->where('e.expense_date <=', $endDate);
        }

        if (!empty($categoryId)) {
            $builder->where('e.category_id', $categoryId);
        }
        if (!empty($paymentMethod)) {
            $builder->where('e.payment_method', $paymentMethod);
        }
        if ($search !== '') {
            $builder->groupStart()
                ->like('e.expense_code', $search)
                ->orLike('e.title', $search)
                ->orLike('e.notes', $search)
                ->orLike('ec.name', $search)
                ->orLike('b.name', $search)
                ->orLike('b.code', $search)
                ->groupEnd();
        }

        $expenses = $builder->orderBy('e.expense_date', 'ASC')
            ->orderBy('e.id', 'ASC')
            ->get()
            ->getResultArray();

        $totalNominal = 0;
        foreach ($expenses as $exp) {
            $totalNominal += (float) ($exp['amount'] ?? 0);
        }

        $data = [
            'expenses'         => $expenses,
            'selectedCategory' => $selectedCategory,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'paymentMethod'    => $paymentMethod,
            'search'           => $search,
            'totalNominal'     => $totalNominal,
        ];

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pages/admin/expense/pdf-expense', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        if ($startDate && $endDate) {
            $filename = "laporan-pengeluaran-{$startDate}-sd-{$endDate}.pdf";
        } elseif ($startDate) {
            $filename = "laporan-pengeluaran-mulai-{$startDate}.pdf";
        } elseif ($endDate) {
            $filename = "laporan-pengeluaran-sampai-{$endDate}.pdf";
        } else {
            $filename = "laporan-pengeluaran-semua-periode.pdf";
        }
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Resolusi rentang tanggal filter (opsional)
     */
    private function resolveDateRange(?string $startInput, ?string $endInput): array
    {
        $startDate = null;
        $endDate   = null;

        if (!empty($startInput)) {
            $parsedStart = strtotime(str_replace('/', '-', $startInput));
            if ($parsedStart !== false) {
                $startDate = date('Y-m-d', $parsedStart);
            }
        }

        if (!empty($endInput)) {
            $parsedEnd = strtotime(str_replace('/', '-', $endInput));
            if ($parsedEnd !== false) {
                $endDate = date('Y-m-d', $parsedEnd);
            }
        }

        if ($startDate !== null && $endDate !== null && $startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }
}
