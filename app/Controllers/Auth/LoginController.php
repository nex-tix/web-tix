<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
        {
            helper('form');
            // Jika sudah login, redirect ke dashboard
            if (session()->get('user_logged_in')) {
                return redirect()->to(site_url('admin/dashboard'));
            }

            return view('pages/auth/login');
        }

    public function authenticate()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('login')
                ->withInput()
                ->with('error', $this->validator->getError('username') ?: $this->validator->getError('password'));
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        // Get rate limit settings from DB
        $settingModel = model(SettingModel::class);
        $maxAttempts  = (int) $settingModel->getSetting('login_max_attempts', 5);
        $lockMinutes  = (int) $settingModel->getSetting('login_lock_minutes', 15);

        // Check if rate limited
        if ($this->isRateLimited($username, $maxAttempts, $lockMinutes)) {
            return redirect()->to('login')
                ->withInput()
                ->with('error', "Terlalu banyak percobaan login gagal. Akun terkunci selama {$lockMinutes} menit.");
        }

        $userModel = model(UserModel::class);
        $user      = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->recordFailedAttempt($username, $maxAttempts, $lockMinutes);
            return redirect()->to('login')
                ->withInput()
                ->with('error', 'Username atau kata sandi salah.');
        }

        if (!$user['is_active']) {
            return redirect()->to('login')
                ->withInput()
                ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.');
        }

        // BR-17: Web hanya dapat diakses pengguna internal (role Admin)
        if ($user['role'] !== 'admin') {
            return redirect()->to('login')
                ->withInput()
                ->with('error', 'Akses ditolak. Hanya Admin yang dapat login ke sistem web.');
        }

        // Login sukses: bersihkan hitungan gagal & regenerate session ID
        $this->clearFailedAttempts($username);
        session()->regenerate();

        session()->set([
            'user_id'        => (int) $user['id'],
            'user_name'      => $user['name'],
            'username'       => $user['username'],
            'role'           => $user['role'],
            'user_logged_in' => true,
        ]);

        return redirect()->to(site_url('admin/dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    private function getCacheKeys(string $username): array
    {
        $ip = $this->request->getIPAddress();
        $hash = hash('sha256', strtolower($username) . '_' . $ip);

        return [
            'attempts' => 'login_att_' . $hash,
            'lock'     => 'login_lock_' . $hash,
        ];
    }

    private function isRateLimited(string $username, int $maxAttempts, int $lockMinutes): bool
    {
        $keys = $this->getCacheKeys($username);
        $cache = cache();

        $lockUntil = $cache->get($keys['lock']);
        if ($lockUntil && time() < (int) $lockUntil) {
            return true;
        }

        $attempts = (int) $cache->get($keys['attempts']);
        if ($attempts >= $maxAttempts) {
            $cache->save($keys['lock'], time() + ($lockMinutes * 60), $lockMinutes * 60);
            return true;
        }

        return false;
    }

    private function recordFailedAttempt(string $username, int $maxAttempts, int $lockMinutes): void
    {
        $keys = $this->getCacheKeys($username);
        $cache = cache();
        $ttl = $lockMinutes * 60;

        $attempts = (int) $cache->get($keys['attempts']);
        $attempts++;

        $cache->save($keys['attempts'], $attempts, $ttl);

        if ($attempts >= $maxAttempts) {
            $cache->save($keys['lock'], time() + $ttl, $ttl);
        }
    }

    private function clearFailedAttempts(string $username): void
    {
        $keys = $this->getCacheKeys($username);
        $cache = cache();

        $cache->delete($keys['attempts']);
        $cache->delete($keys['lock']);
    }
}
