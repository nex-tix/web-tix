<div class="chart-card">
  <div class="card-header-custom">
    <div>
      <h6 style="color: #2c3136"><?= esc($title ?? '') ?></h6>
      <p><?= esc($subtitle ?? '') ?></p>
    </div>
    <?php if (isset($badge_text)): ?>
      <span class="badge-hari-ini"> <?= esc($badge_text ?? '') ?> </span>
    <?php endif; ?>
  </div>
  <div style="height: <?= esc($height ?? '') ?>px">
    <canvas id="<?= esc($chart_id ?? '') ?>"></canvas>
  </div>
  <?php if (isset($footer_stats) && is_array($footer_stats)): ?>
    <div
      class="d-flex justify-content-between mt-3 flex-wrap gap-2"
      style="font-size: 0.8rem; color: var(--text-muted)">
      <?php foreach ($footer_stats as $stat): ?>
        <span>
          <?= esc($stat['label'] ?? '') ?>
          <strong style="color: var(--text-dark)">
            <?= esc($stat['value'] ?? '') ?>
          </strong>
        </span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
