<div class="card border-0 shadow-sm mb-3 bg-white">
  <div class="card-body p-3">
    <div class="custom-progress">
      <div class="bar-dark" style="width: <?= esc($cash_rate ?? '') ?>%"></div>
      <div class="bar-red" style="width: <?= esc($debit_rate ?? '') ?>%"></div>
    </div>
    <div class="progress-legend">
      <div class="d-flex gap-3 flex-wrap">
        <div class="legend-item">
          <span class="legend-color"></span>
          Tunai <?= esc($cash_amount ?? '') ?> <?= isset($cash_notes) ? '(' . esc($cash_notes) . ')' : '' ?>
        </div>
        <div class="legend-item">
          <span class="legend-color" style="background-color: #e74c3c"></span>
          Debit <?= esc($debit_amount ?? '') ?> <?= isset($debit_notes) ? '(' . esc($debit_notes) . ')' : '' ?>
        </div>
      </div>
      <div class="fw-semibold">
        Total diterima <?= esc($total_amount ?? (($cash_amount ?? '') . ' + ' . ($debit_amount ?? ''))) ?>
      </div>
    </div>
  </div>
</div>
