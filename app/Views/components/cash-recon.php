<div class="cash-card">
  <h6 style="color: #2c3136"><?= esc($title ?? 'Cocokkan kas & EDC') ?></h6>
  <p class="sub"><?= esc($subtitle ?? 'Yang harus sama saat tutup loket') ?></p>
  
  <div class="cash-row">
    <span class="label"> Setoran tunai </span>
    <div class="bar-container">
      <div class="bar-fill dark" style="width: <?= esc($cash_rate ?? '') ?>%"></div>
    </div>
    <span class="value"> <?= esc($cash_amount ?? '') ?> </span>
  </div>
  
  <div class="cash-row">
    <span class="label"> Mesin EDC </span>
    <div class="bar-container">
      <div class="bar-fill red" style="width: <?= esc($edc_rate ?? '') ?>%"></div>
    </div>
    <span class="value"> <?= esc($edc_amount ?? '') ?> </span>
  </div>
  
  <?php if (isset($total_notes)): ?>
    <div class="cash-row sub-row">
      <span class="label"> Jumlah nota </span>
      <span class="value"> <?= esc($total_notes ?? '') ?> </span>
    </div>
  <?php endif; ?>
  
  <?php if (isset($discount)): ?>
    <div class="cash-row">
      <span class="label"> Diskon diberikan </span>
      <span class="value" style="color: #e74c3c">
        <?= esc($discount ?? '') ?>
      </span>
    </div>
  <?php endif; ?>
  
  <?php if (isset($void_info)): ?>
    <div class="cash-row">
      <span class="label"> Nota di-void </span>
      <span class="value"> <?= esc($void_info ?? '') ?> </span>
    </div>
  <?php endif; ?>
  
  <?php if (isset($notes) && is_array($notes)): ?>
    <?php foreach ($notes as $note): ?>
      <div class="cash-row <?= esc($note['class'] ?? '') ?>">
        <span class="label"> <?= esc($note['label'] ?? '') ?> </span>
        <span class="value"> <?= esc($note['value'] ?? '') ?> </span>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <div class="info-box">
    <i class="bi bi-info-circle-fill"></i>
    <span>
      <?= esc($info_text ?? 'Angka tunai dicocokkan dengan isi laci, angka EDC dengan slip mesin. Void bukan refund.') ?>
    </span>
  </div>
</div>
