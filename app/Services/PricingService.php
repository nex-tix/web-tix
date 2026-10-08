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
        $this->ticketTypeModel = new TicketTypeModel();
        $this->settingModel = new SettingModel();
    }

    public function calculateTicketDetails(array $ticketItems): array
    {
        $ticketItems = $this->normalizeItems($ticketItems);

        $details = [];
        $subtotalGross = 0;
        $totalDiscountAmount = 0;
        $totalVisitors = 0;

        foreach ($ticketItems as $item) {
            $ticketType = $this->ticketTypeModel->find($item['ticket_type_id']);

            if (!$ticketType) {
                throw new \InvalidArgumentException("Jenis tiket dengan ID {$item['ticket_type_id']} tidak ditemukan");
            }

            if (!$ticketType['is_active']) {
                throw new \InvalidArgumentException("Jenis tiket {$ticketType['name']} tidak aktif");
            }

            $qty             = $item['qty'];
            $unitPrice       = (float) $ticketType['price'];
            $discountPercent = (float) $ticketType['discount_percent'];

            // Diskon dibulatkan per unit lalu dikali qty,
            // supaya total transaksi == jumlah ticket_details (individual maupun group).
            $unitDiscount = $this->calculateUnitDiscount($unitPrice, $discountPercent);
            $lineGross    = $qty * $unitPrice;
            $lineDiscount = $qty * $unitDiscount;

            $details[] = [
                'ticket_type_id'   => $ticketType['id'],
                'ticket_type_name' => $ticketType['name'],
                'ticket_category'  => $ticketType['ticket_category'],
                'qty'              => $qty,
                'unit_price'       => $unitPrice,
                'discount_percent' => $discountPercent,
                'unit_discount'    => $unitDiscount,
                'discount_amount'  => $lineDiscount,
                'subtotal'         => $lineGross - $lineDiscount,
            ];

            $subtotalGross       += $lineGross;
            $totalDiscountAmount += $lineDiscount;
            $totalVisitors       += $qty;
        }

        return [
            'subtotal'        => $subtotalGross,
            'discount_amount' => $totalDiscountAmount,
            'total_amount'    => $subtotalGross - $totalDiscountAmount,
            'total_visitors'  => $totalVisitors,
            'details'         => $details,
        ];
    }

    protected function calculateUnitDiscount(float $unitPrice, float $discountPercent): float
    {
        if ($discountPercent <= 0) {
            return 0.00;
        }

        return round($unitPrice * $discountPercent / 100, 0);
    }

    public function validateTicketLimits(array $ticketItems, string $category): array
    {
        try {
            $ticketItems = $this->normalizeItems($ticketItems);
        } catch (\InvalidArgumentException $e) {
            return ['valid' => false, 'message' => $e->getMessage()];
        }

        $totalVisitors = 0;
        $categoryFromItems = null;

        foreach ($ticketItems as $item) {
            $ticketType = $this->ticketTypeModel->find($item['ticket_type_id']);

            if (!$ticketType) {
                return [
                    'valid'   => false,
                    'message' => "Jenis tiket dengan ID {$item['ticket_type_id']} tidak ditemukan",
                ];
            }

            if ($categoryFromItems === null) {
                $categoryFromItems = $ticketType['ticket_category'];
            } elseif ($categoryFromItems !== $ticketType['ticket_category']) {
                return [
                    'valid'   => false,
                    'message' => 'Tidak boleh mencampur jenis tiket individual dan rombongan dalam satu transaksi',
                ];
            }

            $totalVisitors += $item['qty'];
        }

        if ($categoryFromItems !== $category) {
            return [
                'valid'   => false,
                'message' => 'Kategori tiket tidak sesuai dengan mode penjualan',
            ];
        }

        if ($category === 'individual') {
            $maxIndividual = (int) $this->settingModel->getSetting('max_individual_per_tx', 4);

            if ($totalVisitors > $maxIndividual) {
                return [
                    'valid'   => false,
                    'message' => "Maksimal {$maxIndividual} tiket individual per transaksi. Untuk {$totalVisitors} orang, gunakan tiket rombongan.",
                ];
            }
        }

        if ($category === 'group') {
            $minGroupSize    = (int) $this->settingModel->getSetting('min_group_size', 5);
            $maxGroupMembers = (int) $this->settingModel->getSetting('max_group_members', 15);

            if ($totalVisitors < $minGroupSize) {
                return [
                    'valid'   => false,
                    'message' => "Minimal {$minGroupSize} orang untuk tiket rombongan. Untuk {$totalVisitors} orang, gunakan tiket individual.",
                ];
            }

            if ($totalVisitors > $maxGroupMembers) {
                return [
                    'valid'   => false,
                    'message' => "Maksimal {$maxGroupMembers} orang per tiket rombongan. Silakan buat transaksi terpisah.",
                ];
            }
        }

        return ['valid' => true, 'message' => 'Validasi berhasil'];
    }

    /**
     * Validasi input, cast qty ke int dulu baru cek > 0,
     * dan gabungkan item dengan ticket_type_id yang sama.
     *
     * @return array<int, array{ticket_type_id:int, qty:int}>
     */
    protected function normalizeItems(array $ticketItems): array
    {
        if (empty($ticketItems)) {
            throw new \InvalidArgumentException('Ticket items tidak boleh kosong');
        }

        $merged = [];

        foreach ($ticketItems as $item) {
            if (!isset($item['ticket_type_id']) || !isset($item['qty'])) {
                throw new \InvalidArgumentException('Setiap item harus memiliki ticket_type_id dan qty');
            }

            if (!is_numeric($item['qty'])) {
                throw new \InvalidArgumentException('Qty harus berupa angka');
            }

            $qty = (int) $item['qty'];
            if ($qty <= 0) {
                throw new \InvalidArgumentException('Qty harus lebih besar dari 0');
            }

            $typeId = (int) $item['ticket_type_id'];
            $merged[$typeId] = ($merged[$typeId] ?? 0) + $qty;
        }

        $result = [];
        foreach ($merged as $typeId => $qty) {
            $result[] = ['ticket_type_id' => $typeId, 'qty' => $qty];
        }

        return $result;
    }
}