<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Cek apakah user sudah login
        if (!$session->has('user_id') || !$session->has('user_logged_in')) {
            // Redirect ke login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user masih aktif
        $userModel = model('UserModel');
        $user = $userModel->find($session->get('user_id'));
        
        if (!$user || !$user['is_active']) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Akun Anda tidak aktif.');
        }

        // BR-17: Web hanya dapat diakses pengguna internal (role Admin) yang login
        if ($user['role'] !== 'admin') {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Akses ditolak. Hanya Admin yang dapat mengakses web.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
