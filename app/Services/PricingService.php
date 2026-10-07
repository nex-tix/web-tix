<?php

namespace App\Services;

use App\Models\TicketTypeModel;
use App\Models\SettingModel;

class PricingService
{
    protected TicketTypeModel $ticketTypeModel;
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->ticketTypeModel = new \App\Models\TicketTypeModel();
        $this->settingModel = new \App\Models\SettingModel();
    }

    public function calculateTicketDetails(array $ticketItems): array
    {
        if (empty($ticketItems)) {
            throw new \InvalidArgumentException('Ticket items tidak boleh kosong');
        }

        $details = [];
        $subtotalGross = 0;
        $totalDiscountAmount = 0;
        $totalVisitors = 0;

        foreach ($ticketItems as $item) {
            if (!isset($item['ticket_type_id']) || !isset($item['qty'])) {
                throw new \InvalidArgumentException('Setiap item harus memiliki ticket_type_id dan qty');
            }

            if ($item['qty'] <= 0) {
                throw new \InvalidArgumentException('Qty harus lebih besar dari 0');
            }

            $ticketType = $this->ticketTypeModel->find($item['ticket_type_id']);

            if (!$ticketType) {
                throw new \InvalidArgumentException("Jenis tiket dengan ID {$item['ticket_type_id']} tidak ditemukan");
            }

            if (!$ticketType['is_active']) {
                throw new \InvalidArgumentException("Jenis tiket {$ticketType['name']} tidak aktif");
            }

            $qty = (int)$item['qty'];
            $unitPrice = (float)$ticketType['price'];
            $discountPercent = (float)$ticketType['discount_percent'];

            $lineGross = $qty * $unitPrice;
            $lineDiscount = $this->calculateLineDiscount($lineGross, $discountPercent);
            $lineSubtotal = $lineGross - $lineDiscount;

            $details[] = [
                'ticket_type_id' => $ticketType['id'],
                'ticket_type_name' => $ticketType['name'],
                'ticket_category' => $ticketType['ticket_category'],
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'discount_percent' => $discountPercent,
                'discount_amount' => $lineDiscount,
                'subtotal' => $lineSubtotal,
            ];

            $subtotalGross += $lineGross;
            $totalDiscountAmount += $lineDiscount;
            $totalVisitors += $qty;
        }

        $totalAmount = $this->calculateTotalAmount($subtotalGross, $totalDiscountAmount);

        return [
            'subtotal' => $subtotalGross,
            'discount_amount' => $totalDiscountAmount,
            'total_amount' => $totalAmount,
            'total_visitors' => $totalVisitors,
            'details' => $details,
        ];
    }

    protected function calculateLineDiscount(float $lineGross, float $discountPercent): float
    {
        if ($discountPercent <= 0) {
            return 0.00;
        }

        return round($lineGross * $discountPercent / 100, 0);
    }

    protected function calculateTotalAmount(float $subtotal, float $discountAmount): float
    {
        return $subtotal - $discountAmount;
    }

    public function validateTicketLimits(array $ticketItems, string $category): array
    {
        $totalVisitors = 0;
        $categoryFromItems = null;

        foreach ($ticketItems as $item) {
            $ticketType = $this->ticketTypeModel->find($item['ticket_type_id']);

            if (!$ticketType) {
                return [
                    'valid' => false,
                    'message' => "Jenis tiket dengan ID {$item['ticket_type_id']} tidak ditemukan"
                ];
            }

            if ($categoryFromItems === null) {
                $categoryFromItems = $ticketType['ticket_category'];
            } elseif ($categoryFromItems !== $ticketType['ticket_category']) {
                return [
                    'valid' => false,
                    'message' => 'Tidak boleh mencampur jenis tiket individual dan rombongan dalam satu transaksi'
                ];
            }

            $totalVisitors += (int)$item['qty'];
        }

        if ($categoryFromItems !== $category) {
            return [
                'valid' => false,
                'message' => 'Kategori tiket tidak sesuai dengan mode penjualan'
            ];
        }

        if ($category === 'individual') {
            $maxIndividual = (int)$this->settingModel->getSetting('max_individual_per_tx', 4);

            if ($totalVisitors > $maxIndividual) {
                return [
                    'valid' => false,
                    'message' => "Maksimal {$maxIndividual} tiket individual per transaksi. Untuk {$totalVisitors} orang, gunakan tiket rombongan."
                ];
            }
        }

        if ($category === 'group') {
            $minGroupSize = (int)$this->settingModel->getSetting('min_group_size', 5);
            $maxGroupMembers = (int)$this->settingModel->getSetting('max_group_members', 15);

            if ($totalVisitors < $minGroupSize) {
                return [
                    'valid' => false,
                    'message' => "Minimal {$minGroupSize} orang untuk tiket rombongan. Untuk {$totalVisitors} orang, gunakan tiket individual."
                ];
            }

            if ($totalVisitors > $maxGroupMembers) {
                return [
                    'valid' => false,
                    'message' => "Maksimal {$maxGroupMembers} orang per tiket rombongan. Silakan buat transaksi terpisah."
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Validasi berhasil'
        ];
    }
}
