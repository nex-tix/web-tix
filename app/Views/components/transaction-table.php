<?php

?>
<div class="table-card">
  <div class="card-header-custom">
    <div>
      <h6 style="color: #2c3136"><?= esc($title ?? 'Transaksi terbaru') ?></h6>
      <p><?= esc($subtitle ?? 'Lima nota terakhir di loket ini') ?></p>
    </div>
    <?php if (isset($link_url)): ?>
      <a href="<?= esc($link_url) ?>" class="link-semua">
        <?= esc($link_text ?? 'Semua riwayat') ?> <i class="bi bi-chevron-right"></i>
      </a>
    <?php endif; ?>
  </div>
  <div class="table-responsive">
    <table
      id="<?= esc($table_id ?? 'transactionTable') ?>"
      class="table table-hover"
      style="width: 100%">
      <thead>
        <tr style="background: #1a1d20">
          <?php if (isset($headers) && is_array($headers)): ?>
            <?php foreach ($headers as $header): ?>
              <th><?= esc($header ?? '') ?></th>
            <?php endforeach; ?>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($rows) && is_array($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <?php if (is_array($row)): ?>
                <?php foreach ($row as $cell): ?>
                  <td><?= $cell ?></td>
                <?php endforeach; ?>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
