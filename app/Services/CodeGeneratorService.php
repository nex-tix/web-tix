<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\TicketModel;

class CodeGeneratorService
{
    protected TransactionModel $transactionModel;
    protected TicketModel $ticketModel;
    protected int $maxRetries = 10;
    protected string $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    public function __construct()
    {
        $this->transactionModel = new \App\Models\TransactionModel();
        $this->ticketModel = new \App\Models\TicketModel();
    }

    public function generateInvoiceCode(): string
    {
        $date = date('Ymd');
        $prefix = "INV-{$date}-";

        for ($i = 0; $i < $this->maxRetries; $i++) {
            $code = $prefix . $this->generateRandomString(6);

            if (!$this->transactionModel->where('invoice_code', $code)->first()) {
                return $code;
            }
        }

        throw new \RuntimeException("Gagal generate invoice_code setelah {$this->maxRetries} percobaan");
    }

    public function generateTicketCode(string $category = 'individual'): string
    {
        $prefix = $category === 'group' ? 'GRP-' : 'TKT-';

        for ($i = 0; $i < $this->maxRetries; $i++) {
            $segments = [];
            for ($j = 0; $j < 4; $j++) {
                $segments[] = $this->generateRandomString(4);
            }
            $code = $prefix . implode('-', $segments);

            if (!$this->ticketModel->where('ticket_code', $code)->first()) {
                return $code;
            }
        }

        throw new \RuntimeException("Gagal generate ticket_code setelah {$this->maxRetries} percobaan");
    }

    protected function generateRandomString(int $length): string
    {
        $bytes = random_bytes($length);
        $result = '';
        $charsLength = strlen($this->chars);

        for ($i = 0; $i < $length; $i++) {
            $result .= $this->chars[ord($bytes[$i]) % $charsLength];
        }

        return $result;
    }
}
