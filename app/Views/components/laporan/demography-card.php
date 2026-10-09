<div class="cash-card d-flex flex-column" style="height:100%">
  <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
    <div>
      <h6 style="color:#2c3136" class="mb-1"><?= esc($title ?? 'Rekap Demografi Pengunjung') ?></h6>
      <p class="sub mb-0" style="margin-bottom:0"><?= esc($subtitle ?? 'Manifest tiket pengunjung hari berjalan') ?></p>
    </div>
    <span class="badge bg-success-subtle border text-success fw-semibold" style="font-size:.7rem;white-space:nowrap;background:#e8f5e9!important;color:#2e7d32!important;border-color:#c8e6c9!important"><?= esc($total_label ?? 'Total 41 Orang') ?></span>
  </div>
  <div class="p-3 rounded-3 mb-3" style="background:#f8f9fa;border:1px solid #e9ecef">
    <div class="d-flex justify-content-between" style="font-size:.75rem;font-weight:700">
      <span style="color:#2c3136">Domestik: <?= esc($domestic_percent ?? '65%') ?> (<?= esc($domestic_count ?? '27 orang') ?>)</span>
      <span style="color:#e74c3c">Mancanegara: <?= esc($mancanegara_percent ?? '35%') ?> (<?= esc($mancanegara_count ?? '14 orang') ?>)</span>
    </div>
    <div class="custom-progress mt-2" style="height:8px">
      <div class="bar-dark" style="width:<?= esc($domestic_rate ?? '65') ?>%"></div>
      <div class="bar-red" style="width:<?= esc($mancanegara_rate ?? '35') ?>%"></div>
    </div>
  </div>
  <div class="small text-black fw-bold mb-2" style="font-size:.65rem;letter-spacing:.5px;text-transform:uppercase">Asal Pengunjung Terbanyak</div>
  <?php
  $origins = $origins ?? [
    ['label' => 'Indonesia (Jakarta, Surabaya, Bali)', 'flag' => 'ID', 'percent' => 65, 'count' => 27, 'color' => '#2c3136'],
    ['label' => 'Australia (Sydney, Melbourne)', 'flag' => 'AU', 'percent' => 18, 'count' => 7, 'color' => '#e74c3c'],
    ['label' => 'Inggris & Eropa', 'flag' => 'GB', 'percent' => 10, 'count' => 4, 'color' => '#f1c40f'],
    ['label' => 'Negara Lainnya', 'flag' => 'GL', 'percent' => 7, 'count' => 3, 'color' => '#95a5a6'],
  ];
  foreach ($origins as $o):
  ?>
    <div class="mb-2">
      <div class="d-flex justify-content-between" style="font-size:.78rem;font-weight:600;color:#2c3136">
        <span><?= esc(($o['flag'] ?? '') . ' ' . ($o['label'] ?? '')) ?></span>
        <span><?= esc($o['percent']) ?>% (<?= esc($o['count']) ?>)</span>
      </div>
      <div class="progress mt-1" style="height:6px;background:#f0f0f0">
        <div class="progress-bar" role="progressbar" style="width:<?= esc($o['percent']) ?>%;background:<?= esc($o['color']) ?>" aria-valuenow="<?= esc($o['percent']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>
  <?php endforeach; ?>
  <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top mt-3" style="font-size:.78rem;color:var(--text-muted)">
    <span class="d-flex align-items-center gap-1"><span class="dot-yellow" style="background:#27ae60"></span> Individual: <strong style="color:#2c3136"><?= esc($individual_label ?? '35 tiket') ?></strong></span>
    <span class="d-flex align-items-center gap-1"><span class="dot-yellow" style="background:#6c5ce7"></span> Rombongan: <strong style="color:#2c3136"><?= esc($group_label ?? '8 nota (59 orang)') ?></strong></span>
  </div>
</div>
