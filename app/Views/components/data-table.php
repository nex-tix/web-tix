<div class="card border-0 shadow-sm bg-white">
  <?php if (isset($title) || isset($create_url) || isset($create_modal_id)): ?>
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
      <div>
        <?php if (isset($title)): ?>
          <h5 class="mb-1 text-dark fw-bold"><?= esc($title) ?></h5>
        <?php endif; ?>
        <?php if (isset($subtitle)): ?>
          <p class="text-dark small mb-0"><?= esc($subtitle) ?></p>
        <?php endif; ?>
      </div>
      <?php if (isset($create_modal_id)): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="background-color: #e24a3b; border-color: #e24a3b;" data-bs-toggle="modal" data-bs-target="#<?= esc($create_modal_id) ?>">
          <i class="bi bi-plus-lg"></i> <?= esc($create_text ?? 'Tambah Data') ?>
        </button>
      <?php elseif (isset($create_url)): ?>
        <a href="<?= esc($create_url) ?>" class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="background-color: #e24a3b; border-color: #e24a3b;">
          <i class="bi bi-plus-lg"></i> <?= esc($create_text ?? 'Tambah Data') ?>
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead >
          <tr>
            <?php foreach ($headers as $index => $header): ?>
              <th class="<?= (is_array($header) && isset($header['class'])) ? $header['class'] : ($index === count($headers) - 1 ? 'text-end' : '') ?>">
                <?= is_array($header) ? esc($header['text']) : esc($header) ?>
              </th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody class="table-light">
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="<?= count($headers) ?>" class="text-center py-4 text-muted">
                <?= esc($empty_message ?? 'Belum ada data.') ?>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <?php foreach ($row as $index => $cell): ?>
                  <td class="<?= $index === count($row) - 1 ? 'text-end' : '' ?>">
                    <?= $cell ?>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>