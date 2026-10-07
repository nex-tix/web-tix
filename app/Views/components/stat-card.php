<div class="stat-card" style="background-color: <?= esc($bg ?? '#fff') ?>">
  <div class="stat-label">
    <?php if (isset($dot_class)): ?>
      <span class="<?= esc($dot_class) ?>"></span>
    <?php endif; ?>
    <?= esc($label ?? '') ?>
  </div>
  <div class="stat-value"><?= $value ?? '' ?></div>
  <div class="stat-sub <?= esc($sub_class ?? '') ?>">
    <?= esc($sub ?? '') ?>
  </div>
</div>
