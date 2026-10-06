<?= $this->include('layouts/head.php') ?>
  <body>
<?= $this->include('layouts/symbol.php') ?>


    <!-- ============================ LAYAR LOGIN ============================ -->
     <div id="login">
    <div class="login" id="screen-login">
      <div class="login__box">
        <div class="login__venue">
          <strong>Pertunjukan Kecak Uluwatu</strong>
          <span
            >Pecatu, Kuta Selatan, Badung &middot; Web admin loket &amp;
            gate</span
          >
        </div>

        <div class="login__card">
          <div class="login__brand">
            <span class="login__mark" aria-hidden="true">
              <svg
                width="22"
                height="22"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#F5D061"
                stroke-width="1.9"
                stroke-linecap="round"
              >
                <path d="M12 20.2a6.2 6.2 0 0 1 0-12.4" />
                <path d="M12 12.2a2.4 2.4 0 0 1 0-4.8" />
                <path d="M12 3.8v2.2" />
                <path d="M4.6 9.6l2 1.1" />
                <path d="M19.4 9.6l-2 1.1" />
              </svg>
            </span>
            <div>
              <h1 class="login__title">Masuk ke loket</h1>
              <p class="caption">Akun internal &ndash; satu akun per petugas</p>
            </div>
          </div>

          <form id="login-form" novalidate>
            <div class="field" id="login-user-field">
              <label class="label" for="login-user">Username</label>
              <input
                class="input"
                id="login-user"
                name="username"
                autocomplete="username"
                placeholder="mis. admin"
                required
              />
            </div>
            <div class="field" id="login-pass-field">
              <label class="label" for="login-pass">Password</label>
              <input
                class="input"
                id="login-pass"
                name="password"
                type="password"
                autocomplete="current-password"
                placeholder="••••••••"
                required
              />
            </div>
            <p class="msg msg--err" id="login-error" hidden>
              <svg class="ico ico--sm"><use href="#i-alert"></use></svg>
              <span>Username atau password tidak sesuai.</span>
            </p>
            <button class="btn btn--primary btn--lg btn--block" type="submit">
              <svg class="ico"><use href="#i-lock"></use></svg> Masuk
            </button>
          </form>

          <p class="caption">
            Setelah <b id="login-attempts">5</b> kali gagal, akun terkunci
            <b id="login-lock">15</b> menit. Lupa password? Minta Administrator
            meresetnya.
          </p>
        </div>

        <div class="login__clock">
          <div class="dayclock">
            <svg class="ico ico--lg dayclock__ico">
              <use href="#i-clock"></use>
            </svg>
            <div style="flex: 1">
              <div class="dayclock__date" id="day-date-login">Hari dagang</div>
              <div class="dayclock__row">
                <span class="dayclock__left" id="day-left-login">--j --m</span>
                <span class="dayclock__cap">hingga 24.00 WITA</span>
              </div>
              <div class="dayclock__bar">
                <div class="dayclock__fill" id="day-bar-login"></div>
              </div>
            </div>
          </div>
        </div>

        <p class="login__foot">
          Mockup statis untuk peninjauan desain. Seluruh angka, nama, dan
          transaksi adalah data sintetis.
        </p>
      </div>
    </div>
    </div>

    <!-- ============================ APLIKASI ============================ -->
    <div class="app" id="app">

    <!-- ============================ SIDEBAR ============================ -->
<?= $this->include('partials/sidebar.php') ?>

    <!-- ============================ SIDEBAR ============================ -->



      <div class="main">
    <!-- ============================ HEADER ============================ -->
<?= $this->include('partials/header.php') ?>

    <!-- ============================ HEADER ============================ -->


        <div class="views">
          <!-- ==================== DASHBOARD ==================== -->
          <section
            class="view"
            id="view-dashboard"
            aria-labelledby="t-dashboard"
          >
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-dashboard">Papan hari ini</h1>
                  <p class="page__sub">
                    Ringkasan penjualan dan kunjungan seluruh gate pada hari
                    dagang berjalan.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--ghost" data-goto="laporan">
                    <svg class="ico"><use href="#i-bars"></use></svg> Laporan
                  </button>
                  <button class="btn btn--primary" data-goto="pos">
                    <svg class="ico"><use href="#i-plus"></use></svg> Transaksi
                    baru
                  </button>
                </div>
              </div>

              <div class="stack">
                <section class="panel" aria-label="Angka hari ini">
                  <div class="board__cells" id="board-cells"></div>
                  <div class="board__bar">
                    <div class="paybar">
                      <div
                        class="paybar__track"
                        id="paybar-track"
                        role="img"
                        aria-label="Komposisi pembayaran tunai dan debit"
                      ></div>
                      <div class="paybar__legend" id="paybar-legend"></div>
                    </div>
                  </div>
                </section>

                <div class="grid-board">
                  <section class="panel">
                    <div class="panel__head">
                      <div>
                        <div class="panel__title">Penjualan tujuh hari</div>
                        <div class="panel__sub">
                          Total dibayar per hari dagang (WITA), termasuk void
                        </div>
                      </div>
                      <div class="panel__tools">
                        <span
                          class="badge badge--neutral"
                          id="chart-today-badge"
                          >hari ini</span
                        >
                      </div>
                    </div>
                    <div class="panel__body">
                      <svg
                        class="chart"
                        id="chart"
                        role="img"
                        aria-label="Grafik penjualan tujuh hari terakhir"
                      ></svg>
                      <div
                        class="legend"
                        id="chart-legend"
                        style="margin-top: 12px"
                      ></div>
                    </div>
                  </section>

                  <section class="panel">
                    <div class="panel__head">
                      <div>
                        <div class="panel__title">Cocokkan kas &amp; EDC</div>
                        <div class="panel__sub">
                          Yang harus sama saat tutup loket
                        </div>
                      </div>
                    </div>
                    <div class="panel__body" id="recon-body"></div>
                  </section>
                </div>

                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Transaksi terbaru</div>
                      <div class="panel__sub">
                        Lima nota terakhir di loket ini
                      </div>
                    </div>
                    <div class="panel__tools">
                      <button
                        class="btn btn--ghost btn--sm"
                        data-goto="riwayat"
                      >
                        Semua riwayat
                        <svg class="ico ico--sm">
                          <use href="#i-chevron-right"></use>
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="tbl-scroll">
                    <table class="tbl">
                      <thead>
                        <tr>
                          <th>Waktu</th>
                          <th>Nota</th>
                          <th>Pengunjung</th>
                          <th>Jenis</th>
                          <th>Bayar</th>
                          <th class="r">Total</th>
                          <th>Status tiket</th>
                          <th class="r">Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="recent-body"></tbody>
                    </table>
                  </div>
                </section>
              </div>
            </div>
          </section>

          <!-- ==================== POS LOKET ==================== -->
          <section class="view" id="view-pos" aria-labelledby="t-pos">
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-pos">POS Loket</h1>
                  <p class="page__sub">
                    Satu transaksi, satu tiket. Waktu lokal WITA.
                  </p>
                </div>
                <div class="page__actions">
                  <span class="chip">
                    <svg class="ico ico--sm">
                      <use href="#i-calendar"></use>
                    </svg>
                    Berlaku: <b id="pos-valid-date">-</b>
                  </span>
                  <button class="btn btn--ghost" id="pos-reset">
                    <svg class="ico"><use href="#i-refresh"></use></svg>
                    Kosongkan
                  </button>
                </div>
              </div>

              <div class="pos">
                <div class="pos__form">
                  <section class="panel">
                    <ol class="stepper" id="pos-stepper"></ol>

                    <!-- Langkah 1 -->
                    <div class="panel-step is-on" data-step="1">
                      <div class="panel__body">
                        <div class="form-grid">
                          <div class="field col-2">
                            <label class="label" for="pos-name">
                              Nama lengkap pengunjung
                              <span class="req" title="wajib">*</span></label
                            >
                            <input
                              class="input"
                              id="pos-name"
                              placeholder="Untuk rombongan: nama penanggung jawab"
                            />
                            <span class="hint"
                              >Nama ini tercetak pada struk dan dipakai gate
                              saat mencari pemilik tiket.</span
                            >
                          </div>

                          <div class="field col-2">
                            <span class="label"
                              >Asal pengunjung
                              <span class="req" title="wajib">*</span></span
                            >
                            <div class="opts" id="pos-origin-opts">
                              <label class="opt">
                                <input
                                  type="radio"
                                  name="origin"
                                  value="domestic"
                                  checked
                                />
                                <span
                                  class="opt__mark"
                                  aria-hidden="true"
                                ></span>
                                <span class="opt__title">Domestik</span>
                                <span class="opt__sub"
                                  >WNI &ndash; pilih kota/kabupaten</span
                                >
                              </label>
                              <label class="opt">
                                <input
                                  type="radio"
                                  name="origin"
                                  value="foreign"
                                />
                                <span
                                  class="opt__mark"
                                  aria-hidden="true"
                                ></span>
                                <span class="opt__title">Mancanegara</span>
                                <span class="opt__sub"
                                  >WNA &ndash; pilih negara</span
                                >
                              </label>
                            </div>
                          </div>

                          <div class="field" id="pos-region-field">
                            <label
                              class="label"
                              for="pos-region"
                              id="pos-region-label"
                              >Kota / kabupaten</label
                            >
                            <select class="select" id="pos-region"></select>
                          </div>

                          <div class="field">
                            <label class="label" for="pos-phone"
                              >Nomor HP
                              <span class="opt">(opsional)</span></label
                            >
                            <input
                              class="input num"
                              id="pos-phone"
                              inputmode="tel"
                              placeholder="08xx xxxx xxxx"
                            />
                          </div>

                          <div class="field col-2">
                            <label class="label" for="pos-address"
                              >Alamat singkat
                              <span class="opt">(opsional)</span></label
                            >
                            <textarea
                              class="textarea"
                              id="pos-address"
                              rows="2"
                              placeholder="mis. Jl. Raya Uluwatu No. 12, Jimbaran"
                            ></textarea>
                            <span class="hint"
                              >Alamat dan nomor HP boleh kosong. Data pribadi
                              dianonimkan otomatis setelah 12 bulan.</span
                            >
                          </div>

                          <div class="field col-2">
                            <span class="label"
                              >Jenis penjualan
                              <span class="req" title="wajib">*</span></span
                            >
                            <div class="opts" id="pos-mode-opts">
                              <label class="opt">
                                <input
                                  type="radio"
                                  name="mode"
                                  value="individual"
                                  checked
                                />
                                <span
                                  class="opt__mark"
                                  aria-hidden="true"
                                ></span>
                                <span class="opt__title">Individual</span>
                                <span class="opt__sub"
                                  >1 orang = 1 tiket = 1 QR &middot; maks 4
                                  tiket</span
                                >
                              </label>
                              <label class="opt">
                                <input type="radio" name="mode" value="group" />
                                <span
                                  class="opt__mark"
                                  aria-hidden="true"
                                ></span>
                                <span class="opt__title">Rombongan</span>
                                <span class="opt__sub"
                                  >1 tiket group = 1 QR &middot; 5&ndash;15
                                  orang</span
                                >
                              </label>
                            </div>
                            <p class="msg" id="pos-mode-msg">
                              <svg class="ico ico--sm">
                                <use href="#i-info"></use>
                              </svg>
                              <span
                                >Pembelian di bawah 5 orang wajib dibeli sebagai
                                tiket individual.</span
                              >
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="panel-step" data-step="2">
                      <div class="panel__body" id="pos-types"></div>
                      <div class="summary" id="pos-summary-2"></div>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="panel-step" data-step="3">
                      <div class="panel__body">
                        <span class="label" style="margin-bottom: 12px"
                          >Metode pembayaran <span class="req">*</span></span
                        >
                        <div class="opts" id="pos-pay-opts">
                          <label class="opt">
                            <input
                              type="radio"
                              name="pay"
                              value="cash"
                              checked
                            />
                            <span class="opt__mark" aria-hidden="true"></span>
                            <span class="opt__title">Tunai (Cash)</span>
                            <span class="opt__sub"
                              >Sistem menghitung kembalian</span
                            >
                          </label>
                          <label class="opt">
                            <input type="radio" name="pay" value="debit" />
                            <span class="opt__mark" aria-hidden="true"></span>
                            <span class="opt__title">Debit (EDC)</span>
                            <span class="opt__sub"
                              >Wajib nomor referensi EDC</span
                            >
                          </label>
                        </div>

                        <div class="pay-row" style="margin-top: 18px">
                          <div class="field" id="pos-cash-field">
                            <label class="label" for="pos-cash"
                              >Uang diterima <span class="req">*</span></label
                            >
                            <input
                              class="input num"
                              id="pos-cash"
                              inputmode="numeric"
                              placeholder="0"
                            />
                            <span class="msg" id="pos-cash-msg">
                              <svg class="ico ico--sm">
                                <use href="#i-info"></use>
                              </svg>
                              <span>Tidak boleh kurang dari total bayar.</span>
                            </span>
                          </div>
                          <div class="field" id="pos-edc-field" hidden>
                            <label class="label" for="pos-edc"
                              >Nomor referensi EDC
                              <span class="req">*</span></label
                            >
                            <input
                              class="input mono"
                              id="pos-edc"
                              placeholder="mis. EDC-882134"
                            />
                          </div>
                          <div class="field" style="justify-items: end">
                            <span
                              class="label"
                              style="justify-content: flex-end"
                              >Kembalian</span
                            >
                            <div
                              class="cell__value num"
                              id="pos-change"
                              style="font-size: 26px; margin-top: 2px"
                            >
                              Rp 0
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="summary" id="pos-summary-3"></div>
                    </div>

                    <!-- Langkah 4 -->
                    <div class="panel-step" data-step="4">
                      <div class="panel__body" id="pos-confirm-body"></div>
                    </div>

                    <div class="panel__foot">
                      <button class="btn btn--ghost" id="pos-back">
                        <svg class="ico">
                          <use href="#i-chevron-left"></use>
                        </svg>
                        Kembali
                      </button>
                      <span class="msg" id="pos-step-msg"><span></span></span>
                      <div style="margin-left: auto; display: flex; gap: 10px">
                        <button class="btn btn--outline" id="pos-save" hidden>
                          <svg class="ico"><use href="#i-check"></use></svg>
                          Simpan tanpa cetak
                        </button>
                        <button class="btn btn--primary" id="pos-next">
                          Lanjut
                          <svg class="ico">
                            <use href="#i-arrow-right"></use>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </section>
                </div>

                <aside class="pos__side">
                  <div class="side-head">
                    <svg class="ico ico--sm"><use href="#i-printer"></use></svg>
                    <span
                      >Pratinjau struk thermal <strong>80&nbsp;mm</strong></span
                    >
                  </div>
                  <div class="receipt-wrap">
                    <div class="receipt" id="receipt"></div>
                    <div class="receipt-nav" id="receipt-nav"></div>
                  </div>
                  <p class="caption" style="text-align: center">
                    Pratinjau hidup. Tombol cetak membuka dialog cetak browser
                    dengan CSS
                    <span class="mono">@media print</span> ukuran 80&nbsp;mm.
                  </p>
                </aside>
              </div>
            </div>
          </section>

          <!-- ==================== RIWAYAT PENJUALAN ==================== -->
          <section class="view" id="view-riwayat" aria-labelledby="t-riwayat">
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-riwayat">Riwayat penjualan</h1>
                  <p class="page__sub">
                    Cetak ulang hanya untuk tiket aktif hari ini; void hanya
                    selama belum ada tiket yang masuk.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--primary" data-goto="pos">
                    <svg class="ico"><use href="#i-plus"></use></svg> Transaksi
                    baru
                  </button>
                </div>
              </div>

              <div class="stack">
                <section class="panel">
                  <div class="panel__body">
                    <div class="filters">
                      <div class="field">
                        <label class="label" for="f-tanggal"
                          >Tanggal transaksi</label
                        >
                        <input class="input" type="date" id="f-tanggal" />
                      </div>
                      <div class="field">
                        <label class="label" for="f-petugas">Petugas</label>
                        <select class="select" id="f-petugas"></select>
                      </div>
                      <div class="field">
                        <label class="label" for="f-bayar">Metode bayar</label>
                        <select class="select" id="f-bayar">
                          <option value="">Semua metode</option>
                          <option value="cash">Tunai</option>
                          <option value="debit">Debit</option>
                        </select>
                      </div>
                      <div class="field">
                        <label class="label" for="f-status">Status tiket</label>
                        <select class="select" id="f-status">
                          <option value="">Semua status</option>
                          <option value="active">Aktif (belum masuk)</option>
                          <option value="used">Sudah masuk</option>
                          <option value="cancelled">Dibatalkan</option>
                        </select>
                      </div>
                      <div class="field filters__wide">
                        <label class="label" for="f-cari">Cari</label>
                        <input
                          class="input"
                          id="f-cari"
                          placeholder="Nomor nota, nama pengunjung, atau kode tiket"
                        />
                      </div>
                      <div class="filters__actions">
                        <button class="btn btn--ghost" id="f-reset">
                          Reset
                        </button>
                        <button class="btn btn--primary" id="f-apply">
                          <svg class="ico"><use href="#i-search"></use></svg>
                          Terapkan
                        </button>
                      </div>
                    </div>
                  </div>
                </section>

                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Daftar nota</div>
                      <div class="panel__sub" id="riwayat-count">&ndash;</div>
                    </div>
                    <div class="panel__tools" id="riwayat-tools"></div>
                  </div>
                  <div class="tbl-scroll">
                    <table class="tbl tbl--wide">
                      <thead>
                        <tr>
                          <th>Waktu</th>
                          <th>Nota</th>
                          <th>Petugas</th>
                          <th>Pengunjung</th>
                          <th>Asal</th>
                          <th>Bayar</th>
                          <th class="r">Total</th>
                          <th>Tiket</th>
                          <th class="r">Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="riwayat-body"></tbody>
                    </table>
                  </div>
                  <div class="panel__foot">
                    <span class="caption" id="riwayat-range">&ndash;</span>
                    <div
                      class="pager"
                      id="riwayat-pager"
                      style="margin-left: auto"
                    ></div>
                  </div>
                </section>
              </div>
            </div>
          </section>

          <!-- ==================== LAPORAN ==================== -->
          <section class="view" id="view-laporan" aria-labelledby="t-laporan">
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-laporan">Laporan</h1>
                  <p class="page__sub">
                    Rekap per hari dagang, per petugas, dan per gate. Angka dari
                    data tersimpan, bukan perkiraan.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--secondary" data-export="pdf">
                    <svg class="ico"><use href="#i-download"></use></svg> Ekspor
                    PDF
                  </button>
                  <button class="btn btn--outline" data-export="excel">
                    <svg class="ico"><use href="#i-download"></use></svg> Ekspor
                    Excel
                  </button>
                </div>
              </div>

              <div class="stack">
                <section class="panel">
                  <div class="panel__body">
                    <div class="filters">
                      <div class="field">
                        <label class="label" for="l-dari">Dari tanggal</label>
                        <input class="input" type="date" id="l-dari" />
                      </div>
                      <div class="field">
                        <label class="label" for="l-sampai"
                          >Sampai tanggal</label
                        >
                        <input class="input" type="date" id="l-sampai" />
                      </div>
                      <div class="field">
                        <label class="label" for="l-petugas">Petugas</label>
                        <select class="select" id="l-petugas"></select>
                      </div>
                      <div class="filters__actions">
                        <button class="btn btn--ghost" id="l-reset">
                          Reset
                        </button>
                        <button class="btn btn--primary" id="l-apply">
                          <svg class="ico"><use href="#i-search"></use></svg>
                          Terapkan
                        </button>
                      </div>
                    </div>
                  </div>
                </section>

                <section class="panel">
                  <div class="tabs" role="tablist" id="lap-tabs"></div>
                  <div class="panel__body" id="lap-body"></div>
                </section>
              </div>
            </div>
          </section>

          <!-- ==================== JENIS TIKET ==================== -->
          <section class="view" id="view-tiket" aria-labelledby="t-tiket">
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-tiket">Jenis tiket</h1>
                  <p class="page__sub">
                    Harga dan diskon satu-satunya tempat diatur di sini. POS
                    selalu mengambil nilai terbaru dari database.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--primary" id="tt-add">
                    <svg class="ico"><use href="#i-plus"></use></svg> Tambah
                    jenis tiket
                  </button>
                </div>
              </div>

              <div class="stack">
                <div id="tt-warning"></div>
                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Daftar jenis tiket</div>
                      <div class="panel__sub" id="tt-count">&ndash;</div>
                    </div>
                  </div>
                  <div class="tbl-scroll">
                    <table class="tbl">
                      <thead>
                        <tr>
                          <th>Nama jenis</th>
                          <th>Kategori</th>
                          <th>Kode POS</th>
                          <th class="r">Harga satuan</th>
                          <th class="r">Diskon</th>
                          <th>Status</th>
                          <th class="r">Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="tt-body"></tbody>
                    </table>
                  </div>
                </section>
                <div class="callout callout--info">
                  <svg class="ico"><use href="#i-info"></use></svg>
                  <div>
                    <b>Diskon promo/event</b> dipasang pada jenis individual
                    atau rombongan lewat kolom diskon di atas, bukan di halaman
                    Pengaturan. Perubahan hanya berlaku untuk transaksi
                    berikutnya; nota lama tetap memakai harga dan diskon saat
                    nota itu dibuat.
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ==================== PENGGUNA ==================== -->
          <section class="view" id="view-pengguna" aria-labelledby="t-pengguna">
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-pengguna">Pengguna</h1>
                  <p class="page__sub">
                    Akun Admin untuk web loket, akun Gate untuk aplikasi
                    pemindai. Satu akun per orang.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--primary" id="pu-add">
                    <svg class="ico"><use href="#i-plus"></use></svg> Tambah
                    pengguna
                  </button>
                </div>
              </div>

              <section class="panel">
                <div class="tabs" role="tablist" id="pu-tabs"></div>
                <div class="tbl-scroll">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Aktif terakhir</th>
                        <th class="r">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="pu-body"></tbody>
                  </table>
                </div>
              </section>
            </div>
          </section>

          <!-- ==================== CHECKPOINT ==================== -->
          <section
            class="view"
            id="view-checkpoint"
            aria-labelledby="t-checkpoint"
          >
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-checkpoint">Checkpoint gate</h1>
                  <p class="page__sub">
                    Setiap pemindaian di gate terikat pada satu checkpoint
                    aktif.
                  </p>
                </div>
                <div class="page__actions">
                  <button class="btn btn--primary" id="cp-add">
                    <svg class="ico"><use href="#i-plus"></use></svg> Tambah
                    checkpoint
                  </button>
                </div>
              </div>

              <section class="panel">
                <div class="panel__head">
                  <div>
                    <div class="panel__title">Daftar checkpoint</div>
                    <div class="panel__sub" id="cp-count">&ndash;</div>
                  </div>
                </div>
                <div class="tbl-scroll">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th>Nama checkpoint</th>
                        <th>Status</th>
                        <th class="r">Scan hari ini</th>
                        <th class="r">Ditolak hari ini</th>
                        <th>Dibuat</th>
                        <th class="r">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="cp-body"></tbody>
                  </table>
                </div>
              </section>
            </div>
          </section>

          <!-- ==================== PENGATURAN ==================== -->
          <section
            class="view"
            id="view-pengaturan"
            aria-labelledby="t-pengaturan"
          >
            <div class="page">
              <div class="page__head">
                <div>
                  <h1 class="page__title" id="t-pengaturan">Pengaturan</h1>
                  <p class="page__sub">
                    Parameter operasional loket dan gate. Perubahan tidak
                    mengubah transaksi yang sudah tersimpan.
                  </p>
                </div>
                <div class="page__actions">
                  <span class="chip" id="st-stamp">
                    <svg class="ico ico--sm"><use href="#i-clock"></use></svg>
                    &ndash;</span
                  >
                </div>
              </div>

              <div class="stack">
                <div id="st-warning"></div>

                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Identitas &amp; struk</div>
                      <div class="panel__sub">
                        Tercetak pada header dan footer setiap potongan tiket
                      </div>
                    </div>
                  </div>
                  <div class="panel__body">
                    <div
                      class="grid-board"
                      style="
                        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
                      "
                    >
                      <div class="form-grid">
                        <div class="field col-2">
                          <label class="label" for="s-business-name"
                            >Nama tempat</label
                          >
                          <input class="input" id="s-business-name" />
                        </div>
                        <div class="field col-2">
                          <label class="label" for="s-business-address"
                            >Alamat</label
                          >
                          <input class="input" id="s-business-address" />
                        </div>
                        <div class="field col-2">
                          <label class="label" for="s-receipt-footer"
                            >Teks penutup struk</label
                          >
                          <textarea
                            class="textarea"
                            id="s-receipt-footer"
                            rows="2"
                          ></textarea>
                        </div>
                      </div>
                      <div class="receipt-wrap">
                        <div
                          class="receipt receipt--bare"
                          id="st-receipt"
                        ></div>
                      </div>
                    </div>
                  </div>
                </section>

                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Batas penjualan</div>
                      <div class="panel__sub">
                        Ditegakkan sistem saat menyimpan transaksi
                      </div>
                    </div>
                  </div>
                  <div class="panel__body">
                    <div class="form-grid">
                      <div class="field">
                        <label class="label" for="s-min-group"
                          >Minimum anggota rombongan</label
                        >
                        <input
                          class="input num"
                          id="s-min-group"
                          inputmode="numeric"
                        />
                        <span class="hint"
                          >Bilangan bulat &ge; 2. Rombongan di bawah angka ini
                          wajib dibeli individual.</span
                        >
                      </div>
                      <div class="field">
                        <label class="label" for="s-max-individual"
                          >Maksimum tiket individual per transaksi</label
                        >
                        <input
                          class="input num"
                          id="s-max-individual"
                          inputmode="numeric"
                        />
                        <span class="hint"
                          >Disarankan sama dengan minimum rombongan dikurangi
                          satu.</span
                        >
                      </div>
                      <div class="field">
                        <label class="label" for="s-max-group"
                          >Maksimum anggota per tiket rombongan</label
                        >
                        <input
                          class="input num"
                          id="s-max-group"
                          inputmode="numeric"
                        />
                        <span class="hint"
                          >Rombongan lebih besar dibuat sebagai rombongan
                          terpisah.</span
                        >
                      </div>
                      <div class="field">
                        <label class="label" for="s-retention"
                          >Retensi data pribadi (bulan)</label
                        >
                        <input
                          class="input num"
                          id="s-retention"
                          inputmode="numeric"
                        />
                        <span class="hint"
                          >Setelah masa ini nama, HP, dan alamat dianonimkan
                          otomatis.</span
                        >
                      </div>
                    </div>
                  </div>
                </section>

                <section class="panel">
                  <div class="panel__head">
                    <div>
                      <div class="panel__title">Keamanan &amp; sesi</div>
                      <div class="panel__sub">
                        Pembatasan login web dan masa berlaku token aplikasi
                        gate
                      </div>
                    </div>
                  </div>
                  <div class="panel__body">
                    <div class="form-grid">
                      <div class="field">
                        <label class="label" for="s-login-attempts"
                          >Maksimum login gagal</label
                        >
                        <input
                          class="input num"
                          id="s-login-attempts"
                          inputmode="numeric"
                        />
                      </div>
                      <div class="field">
                        <label class="label" for="s-lock-minutes"
                          >Durasi kunci akun (menit)</label
                        >
                        <input
                          class="input num"
                          id="s-lock-minutes"
                          inputmode="numeric"
                        />
                      </div>
                      <div class="field">
                        <label class="label" for="s-token-hours"
                          >Masa berlaku token gate (jam)</label
                        >
                        <input
                          class="input num"
                          id="s-token-hours"
                          inputmode="numeric"
                        />
                        <span class="hint"
                          >Berlaku pada login gate berikutnya; token lama tidak
                          diperpanjang.</span
                        >
                      </div>
                    </div>
                  </div>
                  <div class="panel__foot">
                    <span class="msg" id="st-msg"><span></span></span>
                    <div style="margin-left: auto; display: flex; gap: 10px">
                      <button class="btn btn--ghost" id="st-reset">
                        Batalkan perubahan
                      </button>
                      <button class="btn btn--primary" id="st-save">
                        <svg class="ico"><use href="#i-check"></use></svg>
                        Simpan pengaturan
                      </button>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>

    <div
      class="modal-root"
      id="modal-root"
      role="dialog"
      aria-modal="true"
    ></div>
    <div
      class="toasts"
      id="toasts"
      aria-live="polite"
      aria-atomic="false"
    ></div>
    <div id="print-area" aria-hidden="true"></div>

    <script src="public/js/qrcode.js"></script>
    <script>
      /* ============================================================================
   MOCKUP LOKET KECAK ULUWATU - lapisan data & tampilan
   Seluruh angka, nama, dan transaksi di bawah ini adalah DATA SINTETIS buatan
   untuk keperluan peninjauan desain. Format kode, aturan batas, dan struktur
   struk mengikuti PRD.md v2.5.
   ========================================================================== */
      (function () {
        "use strict";

        /* ---------------------------------------------------------------- utilitas */
        var $ = function (s, r) {
          return (r || document).querySelector(s);
        };
        var $$ = function (s, r) {
          return Array.prototype.slice.call(
            (r || document).querySelectorAll(s),
          );
        };
        var el = function (tag, cls, html) {
          var n = document.createElement(tag);
          if (cls) n.className = cls;
          if (html != null) n.innerHTML = html;
          return n;
        };
        var esc = function (s) {
          return String(s == null ? "" : s).replace(/[&<>"']/g, function (c) {
            return {
              "&": "&amp;",
              "<": "&lt;",
              ">": "&gt;",
              '"': "&quot;",
              "'": "&#39;",
            }[c];
          });
        };
        var rp = function (n) {
          return "Rp " + Math.round(n).toLocaleString("id-ID");
        };
        var rpShort = function (n) {
          if (n >= 1000000)
            return "Rp " + (n / 1000000).toFixed(1).replace(".", ",") + " jt";
          if (n >= 1000) return "Rp " + Math.round(n / 1000) + " rb";
          return "Rp " + n;
        };
        var WITA = 8;
        var nowWita = function () {
          var d = new Date();
          return new Date(
            d.getTime() + d.getTimezoneOffset() * 60000 + WITA * 3600000,
          );
        };
        var pad = function (n) {
          return n < 10 ? "0" + n : "" + n;
        };
        var isoOf = function (d) {
          return (
            d.getFullYear() +
            "-" +
            pad(d.getMonth() + 1) +
            "-" +
            pad(d.getDate())
          );
        };
        var dayShift = function (n) {
          var d = nowWita();
          d.setDate(d.getDate() - n);
          return d;
        };
        var MONTHS = [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ];
        var DAYS = [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ];
        var DAYS_S = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        var humanShort = function (d) {
          return (
            DAYS_S[d.getDay()] +
            ", " +
            d.getDate() +
            " " +
            MONTHS[d.getMonth()].slice(0, 3) +
            " " +
            d.getFullYear()
          );
        };
        var humanLong = function (d) {
          return (
            DAYS[d.getDay()] +
            ", " +
            d.getDate() +
            " " +
            MONTHS[d.getMonth()] +
            " " +
            d.getFullYear()
          );
        };
        var humanStamp = function (d) {
          return (
            d.getDate() +
            " " +
            MONTHS[d.getMonth()].slice(0, 3) +
            " " +
            d.getFullYear() +
            " " +
            pad(d.getHours()) +
            ":" +
            pad(d.getMinutes())
          );
        };
        var ALPH = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        var rnd = function (n) {
          var s = "";
          for (var i = 0; i < n; i++)
            s += ALPH[Math.floor(Math.random() * ALPH.length)];
          return s;
        };
        var ticketCode = function (prefix) {
          return (
            prefix + "-" + rnd(4) + "-" + rnd(4) + "-" + rnd(4) + "-" + rnd(4)
          );
        };
        var notaCode = function (d) {
          return (
            "INV-" +
            d.getFullYear() +
            pad(d.getMonth() + 1) +
            pad(d.getDate()) +
            "-" +
            rnd(6)
          );
        };

        /* ------------------------------------------------------------------- data */
        var TYPES = [
          {
            id: 1,
            name: "Adult",
            category: "individual",
            price: 100000,
            discount: 0,
            active: true,
            created: "12 Sep 2026",
          },
          {
            id: 2,
            name: "Child",
            category: "individual",
            price: 60000,
            discount: 0,
            active: true,
            created: "12 Sep 2026",
          },
          {
            id: 3,
            name: "Adult Rombongan",
            category: "group",
            price: 100000,
            discount: 5,
            active: true,
            created: "12 Sep 2026",
          },
          {
            id: 4,
            name: "Child Rombongan",
            category: "group",
            price: 60000,
            discount: 5,
            active: true,
            created: "12 Sep 2026",
          },
        ];

        var SETTINGS = {
          business_name: "Pertunjukan Kecak Uluwatu",
          business_address: "Pecatu, Kuta Selatan, Badung",
          receipt_footer: "Tidak dapat di-refund",
          min_group_size: 5,
          max_individual_per_tx: 4,
          max_group_members: 15,
          retention_months: 12,
          login_max_attempts: 5,
          login_lock_minutes: 15,
          api_token_hours: 12,
        };
        var SETTINGS_STAMP = { by: "Admin Loket 1", at: "5 Okt 2026 14:12" };

        var CHECKPOINTS = [
          {
            id: 1,
            name: "Gerbang Utama",
            active: true,
            created: "12 Sep 2026",
          },
          {
            id: 2,
            name: "Gerbang Timur",
            active: true,
            created: "20 Sep 2026",
          },
          {
            id: 3,
            name: "Gerbang Pantai",
            active: false,
            created: "1 Okt 2026",
          },
        ];

        var ADMINS = [
          {
            name: "Admin Loket 1",
            username: "admin",
            role: "Admin",
            active: true,
            last: "Hari ini 15:20",
          },
          {
            name: "Admin Loket 2",
            username: "admin2",
            role: "Admin",
            active: true,
            last: "Hari ini 14:02",
          },
          {
            name: "Ni Kadek Ratih",
            username: "kadek",
            role: "Admin",
            active: false,
            last: "28 Sep 2026 20:11",
          },
        ];
        var GATE_USERS = [
          {
            name: "Made Suartana",
            username: "gate1",
            role: "Gate",
            active: true,
            last: "Hari ini 15:41",
          },
          {
            name: "Komang Adnyana",
            username: "gate2",
            role: "Gate",
            active: true,
            last: "Hari ini 15:38",
          },
          {
            name: "Sri Wulandari",
            username: "gate3",
            role: "Gate",
            active: true,
            last: "Hari ini 15:22",
          },
          {
            name: "Wayan Kariasa",
            username: "gate4",
            role: "Gate",
            active: false,
            last: "2 Okt 2026 18:04",
          },
        ];

        /* Transaksi hari ini.
     o = asal ('Domestik'|'Mancanegara'), it = [jenisId, jumlah],
     u = berapa tiket individual yang sudah masuk (grup: true/false) */
        var TX = [
          {
            t: "08:41",
            n: "Lukman Hakim",
            o: "Domestik",
            r: "Denpasar",
            m: "group",
            it: [
              [3, 4],
              [4, 2],
            ],
            p: "cash",
            got: 500000,
            u: true,
            by: "Admin Loket 1",
          },
          {
            t: "08:58",
            n: "Sarah Whitfield",
            o: "Mancanegara",
            r: "Australia",
            m: "individual",
            it: [[1, 2]],
            p: "debit",
            edc: "EDC-4471201",
            u: 2,
            by: "Admin Loket 1",
          },
          {
            t: "09:14",
            n: "I Gede Putra Wiguna",
            o: "Domestik",
            r: "Gianyar",
            m: "individual",
            it: [
              [1, 1],
              [2, 2],
            ],
            p: "cash",
            got: 250000,
            u: 3,
            by: "Admin Loket 1",
          },
          {
            t: "09:26",
            n: "Chen Wei",
            o: "Mancanegara",
            r: "Tiongkok",
            m: "individual",
            it: [[1, 4]],
            p: "debit",
            edc: "EDC-4471288",
            u: 2,
            by: "Admin Loket 2",
          },
          {
            t: "09:47",
            n: "Ni Made Ariani",
            o: "Domestik",
            r: "Badung",
            m: "individual",
            it: [[2, 2]],
            p: "cash",
            got: 150000,
            u: 0,
            by: "Admin Loket 2",
          },
          {
            t: "10:05",
            n: "Julia Berger",
            o: "Mancanegara",
            r: "Jerman",
            m: "group",
            it: [[3, 5]],
            p: "debit",
            edc: "EDC-4471320",
            u: true,
            by: "Admin Loket 1",
          },
          {
            t: "10:22",
            n: "Wayan Sudira",
            o: "Domestik",
            r: "Tabanan",
            m: "individual",
            it: [
              [1, 2],
              [2, 1],
            ],
            p: "cash",
            got: 300000,
            u: 1,
            by: "Admin Loket 1",
          },
          {
            t: "10:48",
            n: "Robert Clarke",
            o: "Mancanegara",
            r: "Inggris",
            m: "group",
            it: [
              [3, 6],
              [4, 1],
            ],
            p: "cash",
            got: 700000,
            u: false,
            by: "Admin Loket 2",
          },
          {
            t: "11:10",
            n: "Dewa Ayu Lestari",
            o: "Domestik",
            r: "Denpasar",
            m: "individual",
            it: [[1, 3]],
            p: "cash",
            got: 400000,
            u: 3,
            by: "Admin Loket 1",
          },
          {
            t: "11:33",
            n: "Hiroshi Tanaka",
            o: "Mancanegara",
            r: "Jepang",
            m: "group",
            it: [
              [3, 5],
              [4, 3],
            ],
            p: "debit",
            edc: "EDC-4471499",
            u: true,
            by: "Admin Loket 2",
          },
          {
            t: "11:58",
            n: "Bagus Mahendra",
            o: "Domestik",
            r: "Buleleng",
            m: "individual",
            it: [[2, 3]],
            p: "cash",
            got: 200000,
            u: 2,
            by: "Admin Loket 1",
          },
          {
            t: "12:19",
            n: "Marie Dubois",
            o: "Mancanegara",
            r: "Prancis",
            m: "group",
            it: [[3, 10]],
            p: "cash",
            got: 1000000,
            u: false,
            by: "Admin Loket 1",
          },
          {
            t: "12:44",
            n: "Kadek Sumerta",
            o: "Domestik",
            r: "Klungkung",
            m: "individual",
            it: [[1, 1]],
            p: "cash",
            got: 100000,
            u: 1,
            by: "Admin Loket 2",
          },
          {
            t: "13:02",
            n: "Anna Kowalski",
            o: "Mancanegara",
            r: "Polandia",
            m: "individual",
            it: [
              [1, 2],
              [2, 2],
            ],
            p: "debit",
            edc: "EDC-4471663",
            u: 2,
            by: "Admin Loket 2",
          },
          {
            t: "13:30",
            n: "Rizky Ananda",
            o: "Domestik",
            r: "Surabaya",
            m: "group",
            it: [
              [3, 7],
              [4, 2],
            ],
            p: "cash",
            got: 800000,
            u: 0,
            by: "Admin Loket 1",
          },
          {
            t: "13:57",
            n: "Peter van Dijk",
            o: "Mancanegara",
            r: "Belanda",
            m: "individual",
            it: [
              [1, 3],
              [2, 1],
            ],
            p: "cash",
            got: 500000,
            u: 4,
            by: "Admin Loket 1",
          },
          {
            t: "14:20",
            n: "Ni Luh Sari",
            o: "Domestik",
            r: "Denpasar",
            m: "group",
            it: [
              [3, 5],
              [4, 4],
            ],
            p: "debit",
            edc: "EDC-4471808",
            u: false,
            by: "Admin Loket 2",
          },
          {
            t: "14:45",
            n: "Michael O'Brien",
            o: "Mancanegara",
            r: "Amerika Serikat",
            m: "individual",
            it: [[1, 2]],
            p: "cash",
            got: 200000,
            u: 2,
            by: "Admin Loket 2",
          },
          {
            t: "15:02",
            n: "Agus Pratama",
            o: "Domestik",
            r: "Bandung",
            m: "individual",
            it: [[1, 4]],
            p: "cash",
            got: 400000,
            u: 0,
            by: "Admin Loket 1",
          },
          {
            t: "15:20",
            n: "Yuki Sato",
            o: "Mancanegara",
            r: "Jepang",
            m: "group",
            it: [[3, 5]],
            p: "cash",
            got: 500000,
            u: false,
            by: "Admin Loket 2",
          },
          {
            t: "15:32",
            n: "Franky Salim",
            o: "Domestik",
            r: "Medan",
            m: "individual",
            it: [[1, 2]],
            p: "cash",
            got: 200000,
            u: 0,
            by: "Admin Loket 1",
            void: true,
            voidReason: "Salah jumlah, tamu minta 2 tiket Dewasa saja",
            voidAt: "15:34",
          },
        ];

        var REJECTED = [
          {
            t: "09:03",
            code: "IND-K3P9-7M2X-QW8N-4RTZ",
            res: "ALREADY_USED",
            cp: "Gerbang Utama",
            by: "Gate 1 · Made",
            note: "Sudah dipakai 08:52 di Gerbang Utama",
          },
          {
            t: "09:41",
            code: "GRP-9F2K-4QW7-M3XT-8ZPL",
            res: "EXPIRED",
            cp: "Gerbang Utama",
            by: "Gate 1 · Made",
            note: "Berlaku 5 Okt 2026",
          },
          {
            t: "10:17",
            code: "IND-XX4M-9K2P-7QW3-5RTZ",
            res: "NOT_FOUND",
            cp: "Gerbang Utama",
            by: "Gate 2 · Komang",
            note: "Kode tidak terdaftar",
          },
          {
            t: "11:26",
            code: "GRP-2QW8-5RTZ-9M3K-7XPL",
            res: "CANCELLED",
            cp: "Gerbang Utama",
            by: "Gate 1 · Made",
            note: "Nota di-void 11:24",
          },
          {
            t: "12:38",
            code: "IND-7M3K-9XPL-2QW8-5RTZ",
            res: "ALREADY_USED",
            cp: "Gerbang Timur",
            by: "Gate 3 · Sri",
            note: "Sudah dipakai 12:31 di Gerbang Timur",
          },
          {
            t: "13:52",
            code: "IND-5RTZ-2QW8-7M3K-9XPL",
            res: "NOT_FOUND",
            cp: "Gerbang Utama",
            by: "Gate 2 · Komang",
            note: "QR lembap, dibaca manual",
          },
          {
            t: "14:33",
            code: "IND-9XPL-7M3K-5RTZ-2QW8",
            res: "EXPIRED",
            cp: "Gerbang Timur",
            by: "Gate 3 · Sri",
            note: "Berlaku 4 Okt 2026",
          },
        ];

        /* Rekap enam hari sebelumnya (tidak dirinci per nota di mockup ini) */
        var HISTORY = [
          {
            off: 6,
            notes: 14,
            cash: 3120000,
            debit: 1480000,
            visitors: 78,
            tickets: 36,
            checkedIn: 72,
            discInd: 0,
            discGroup: 246000,
            domestic: 52,
            foreign: 26,
          },
          {
            off: 5,
            notes: 17,
            cash: 4280000,
            debit: 1960000,
            visitors: 104,
            tickets: 48,
            checkedIn: 101,
            discInd: 0,
            discGroup: 318000,
            domestic: 63,
            foreign: 41,
          },
          {
            off: 4,
            notes: 12,
            cash: 2540000,
            debit: 1120000,
            visitors: 61,
            tickets: 29,
            checkedIn: 58,
            discInd: 0,
            discGroup: 192000,
            domestic: 44,
            foreign: 17,
          },
          {
            off: 3,
            notes: 19,
            cash: 5120000,
            debit: 2340000,
            visitors: 122,
            tickets: 55,
            checkedIn: 118,
            discInd: 88000,
            discGroup: 372000,
            domestic: 71,
            foreign: 51,
          },
          {
            off: 2,
            notes: 16,
            cash: 3960000,
            debit: 1740000,
            visitors: 92,
            tickets: 42,
            checkedIn: 90,
            discInd: 0,
            discGroup: 281000,
            domestic: 58,
            foreign: 34,
          },
          {
            off: 1,
            notes: 18,
            cash: 4520000,
            debit: 2080000,
            visitors: 112,
            tickets: 51,
            checkedIn: 109,
            discInd: 40000,
            discGroup: 334000,
            domestic: 66,
            foreign: 46,
          },
        ];

        /* ------------------------------------------------------- perhitungan nota */
        function buildTx() {
          return TX.map(function (x, i) {
            var items = x.it
              .map(function (p) {
                var ty = TYPES.filter(function (t) {
                  return t.id === p[0];
                })[0];
                var qty = p[1];
                var gross = ty.price * qty;
                var disc = Math.round((gross * ty.discount) / 100);
                return {
                  type: ty,
                  qty: qty,
                  gross: gross,
                  disc: disc,
                  net: gross - disc,
                };
              })
              .filter(function (it) {
                return it.qty > 0;
              });

            var subtotal = items.reduce(function (a, b) {
              return a + b.gross;
            }, 0);
            var discount = items.reduce(function (a, b) {
              return a + b.disc;
            }, 0);
            var total = items.reduce(function (a, b) {
              return a + b.net;
            }, 0);
            var visitors = items.reduce(function (a, b) {
              return a + b.qty;
            }, 0);

            var tickets = [];
            if (x.m === "group") {
              tickets = [
                {
                  code: ticketCode("GRP"),
                  kind: "group",
                  visitors: visitors,
                  status: x.void ? "cancelled" : x.u ? "used" : "active",
                  usedAt: x.u ? x.t : null,
                  usedGate: x.u ? "Gerbang Utama" : null,
                  usedBy: x.u ? "Gate 1 · Made" : null,
                },
              ];
            } else {
              for (var k = 0; k < visitors; k++) {
                var used = !x.void && k < (x.u || 0);
                tickets.push({
                  code: ticketCode("IND"),
                  kind: "individual",
                  visitors: 1,
                  status: x.void ? "cancelled" : used ? "used" : "active",
                  usedAt: used ? x.t : null,
                  usedGate: used ? "Gerbang Utama" : null,
                  usedBy: used ? "Gate 1 · Made" : null,
                });
              }
            }

            var d = nowWita();
            return {
              id: i + 1,
              time: x.t,
              nota: "INV-" + isoOf(d).replace(/-/g, "") + "-" + rnd(6),
              date: isoOf(d),
              cashier: x.by,
              name: x.n,
              origin: x.o,
              region: x.r,
              mode: x.m,
              items: items,
              subtotal: subtotal,
              discount: discount,
              total: total,
              visitors: visitors,
              payment: x.p,
              edc: x.edc || null,
              got: x.got || null,
              change: x.got ? x.got - total : null,
              usedCount:
                x.m === "group" ? (x.u ? 1 : 0) : x.void ? 0 : x.u || 0,
              tickets: tickets,
              void: !!x.void,
              voidReason: x.voidReason || null,
              voidAt: x.voidAt || null,
            };
          });
        }
        var TXS = buildTx();
        var LIVE = TXS.filter(function (t) {
          return !t.void;
        });

        function ticketState(tx) {
          var used = tx.tickets.filter(function (t) {
            return t.status === "used";
          }).length;
          var active = tx.tickets.filter(function (t) {
            return t.status === "active";
          }).length;
          if (
            tx.void ||
            tx.tickets.every(function (t) {
              return t.status === "cancelled";
            })
          )
            return "cancelled";
          if (used && active) return "partial";
          if (used) return "used";
          return "active";
        }
        var TICKET_LABEL = {
          active: { txt: "Aktif", cls: "badge--ok" },
          used: { txt: "Sudah masuk", cls: "badge--neutral" },
          partial: { txt: "Sebagian masuk", cls: "badge--info" },
          cancelled: { txt: "Dibatalkan", cls: "badge--err" },
        };
        var SCAN_LABEL = {
          VALID: { txt: "VALID", cls: "badge--ok" },
          ALREADY_USED: { txt: "ALREADY_USED", cls: "badge--err" },
          EXPIRED: { txt: "EXPIRED", cls: "badge--warn" },
          NOT_FOUND: { txt: "NOT_FOUND", cls: "badge--neutral" },
          CANCELLED: { txt: "CANCELLED", cls: "badge--err" },
        };

        /* Rekap hari ini */
        function todayStats() {
          var s = {
            sales: 0,
            cash: 0,
            debit: 0,
            cashN: 0,
            debitN: 0,
            tickets: 0,
            visitors: 0,
            checked: 0,
            checkedVisitors: 0,
            active: 0,
            activeVisitors: 0,
            discInd: 0,
            discGroup: 0,
            notes: LIVE.length,
            voidCount: 0,
            voidValue: 0,
            domestic: 0,
            foreign: 0,
            cities: {},
            countries: {},
          };
          TXS.forEach(function (tx) {
            if (tx.void) {
              s.voidCount++;
              s.voidValue += tx.total;
              return;
            }
            s.sales += tx.total;
            s.tickets += tx.tickets.length;
            s.visitors += tx.visitors;
            if (tx.payment === "cash") {
              s.cash += tx.total;
              s.cashN++;
            } else {
              s.debit += tx.total;
              s.debitN++;
            }
            tx.tickets.forEach(function (t) {
              if (t.status === "used") {
                s.checked++;
                s.checkedVisitors += t.visitors;
              }
              if (t.status === "active") {
                s.active++;
                s.activeVisitors += t.visitors;
              }
            });
            tx.items.forEach(function (it) {
              if (it.type.category === "group") s.discGroup += it.disc;
              else s.discInd += it.disc;
            });
            if (tx.origin === "Domestik") {
              s.domestic += tx.visitors;
              s.cities[tx.region] = (s.cities[tx.region] || 0) + tx.visitors;
            } else {
              s.foreign += tx.visitors;
              s.countries[tx.region] =
                (s.countries[tx.region] || 0) + tx.visitors;
            }
          });
          return s;
        }
        var TODAYS = todayStats();
        var REJ = REJECTED.length;

        /* ---------------------------------------------------------------- route/ui */
        var VIEWS = [
          "dashboard",
          "pos",
          "riwayat",
          "laporan",
          "tiket",
          "pengguna",
          "checkpoint",
          "pengaturan",
        ];
        function go(view) {
          if (view === "login") {
            showLogin();
            return;
          }
          if (VIEWS.indexOf(view) < 0) view = "dashboard";
          $("#screen-login").classList.remove("is-on");
          $("#app").classList.add("is-on");
          $$(".nav__item").forEach(function (a) {
            a.classList.toggle(
              "is-current",
              a.getAttribute("data-view") === view,
            );
          });
          // matikan animasi masuk pada view yang sudah tampil agar tidak berulang saat render ulang
          $$(".view").forEach(function (v) {
            var on = v.id === "view-" + view;
            if (!on) v.classList.remove("is-on");
            else if (!v.classList.contains("is-on")) {
              v.classList.add("is-on");
            }
          });
          if (location.hash !== "#" + view)
            history.replaceState(null, "", "#" + view);
          window.scrollTo(0, 0);
          if (view === "laporan") renderLaporan();
          if (view === "riwayat") renderRiwayat();
        }
        function showLogin() {
          $("#app").classList.remove("is-on");
          $("#screen-login").classList.add("is-on");
          history.replaceState(null, "", "#login");
          $("#login-user").focus();
        }

        /* -------------------------------------------------------------- jam dagang */
        function tickClock() {
          var d = nowWita();
          var secs = d.getHours() * 3600 + d.getMinutes() * 60 + d.getSeconds();
          var left = 86400 - secs;
          var h = Math.floor(left / 3600),
            m = Math.floor((left % 3600) / 60);
          var txt = h > 0 ? h + "j " + pad(m) + "m" : m + "m";
          var pct = ((secs / 86400) * 100).toFixed(1);
          ["#day-date", "#day-date-login"].forEach(function (s) {
            var n = $(s);
            if (n) n.textContent = "Hari dagang · " + humanShort(d);
          });
          ["#day-left", "#day-left-login"].forEach(function (s) {
            var n = $(s);
            if (n) n.textContent = "Sisa " + txt;
          });
          ["#day-bar", "#day-bar-login"].forEach(function (s) {
            var n = $(s);
            if (n) n.style.transform = "scaleX(" + pct / 100 + ")";
          });
          var vd = $("#pos-valid-date");
          if (vd) vd.textContent = humanLong(d);
        }

        /* ------------------------------------------------------------------- toast */
        function toast(kind, title, text) {
          var wrap = $("#toasts");
          var map = {
            ok: "toast--ok",
            err: "toast--err",
            warn: "toast--warn",
            info: "toast--info",
          };
          var ic = {
            ok: "i-check",
            err: "i-alert",
            warn: "i-alert",
            info: "i-info",
          };
          // pesan yang sama tidak menumpuk: yang lama dibuang lebih dulu
          $$(".toast", wrap).forEach(function (n) {
            if (n.getAttribute("data-msg") === title + "|" + (text || ""))
              n.remove();
          });
          while (wrap.children.length > 2) wrap.removeChild(wrap.firstChild);
          var t = el("div", "toast " + map[kind]);
          t.setAttribute("data-msg", title + "|" + (text || ""));
          t.innerHTML =
            '<svg class="ico"><use href="#' +
            ic[kind] +
            '"></use></svg><div style="flex:1">' +
            '<div class="toast__title">' +
            esc(title) +
            "</div>" +
            (text ? '<div class="toast__text">' + esc(text) + "</div>" : "") +
            "</div>";
          wrap.appendChild(t);
          setTimeout(function () {
            if (!t.isConnected) return;
            t.classList.add("is-out");
            setTimeout(function () {
              t.remove();
            }, 280);
          }, 3400);
        }

        /* ------------------------------------------------------------------- modal */
        var modalClose = null;
        function openModal(opt) {
          var root = $("#modal-root");
          root.innerHTML = "";
          var wrap = el("div", "modal " + (opt.wide ? "modal--wide" : ""));
          wrap.innerHTML =
            '<div class="modal__head"><div>' +
            '<div class="modal__title">' +
            opt.title +
            "</div>" +
            (opt.sub ? '<div class="modal__sub">' + opt.sub + "</div>" : "") +
            "</div>" +
            '<button class="iconbtn modal__close" aria-label="Tutup dialog" data-close>' +
            '<svg class="ico"><use href="#i-x"></use></svg></button></div>' +
            '<div class="modal__body">' +
            opt.body +
            "</div>" +
            (opt.footer
              ? '<div class="modal__foot">' + opt.footer + "</div>"
              : "");
          var scrim = el("div", "modal__scrim");
          root.appendChild(scrim);
          root.appendChild(wrap);
          root.classList.add("is-on");
          modalClose = function () {
            root.classList.remove("is-on");
            root.innerHTML = "";
            modalClose = null;
          };
          scrim.addEventListener("click", function () {
            modalClose();
          });
          $$("[data-close]", root).forEach(function (b) {
            b.addEventListener("click", function () {
              modalClose();
            });
          });
          if (opt.onMount) opt.onMount(root);
          var f = root.querySelector(
            "input:not([type=radio]):not([type=checkbox]),select,textarea,button",
          );
          if (f && opt.focus !== false) f.focus();
        }
        document.addEventListener("keydown", function (e) {
          if (e.key === "Escape" && modalClose) modalClose();
        });

        /* --------------------------------------------------------------------- QR */
        function qrHTML(code, cls) {
          cls = cls || "qr";
          if (typeof qrcode !== "function") {
            return (
              '<div class="' +
              cls +
              ' mono" style="font-size:9px;text-align:center;padding:22px 4px;' +
              'border:1px dashed var(--c-line-strong)">' +
              esc(code) +
              "</div>"
            );
          }
          var q = qrcode(0, "M");
          q.addData(code);
          q.make();
          return (
            '<div class="' +
            cls +
            '">' +
            q.createSvgTag({ cellSize: 4, margin: 0, scalable: true }) +
            "</div>"
          );
        }

        /* ------------------------------------------------------------------ struk */
        function receiptBody(o) {
          var d = nowWita();
          var validDate = humanLong(d).toUpperCase();
          var pay = o.payment === "cash" ? "CASH" : "DEBIT";
          var h = "";
          h +=
            '<div class="rc-head"><b>' +
            esc(SETTINGS.business_name).toUpperCase() +
            "</b><br>" +
            esc(SETTINGS.business_address) +
            "</div>";
          h += '<div class="rc-rule"></div>';
          h +=
            '<dl class="rc-meta">' +
            "<dt>Tanggal</dt><dd>" +
            d.getDate() +
            " " +
            MONTHS[d.getMonth()] +
            " " +
            d.getFullYear() +
            " " +
            o.time +
            " WITA</dd>" +
            "<dt>Nota</dt><dd>" +
            (o.nota || "–") +
            "</dd>" +
            "<dt>Petugas</dt><dd>" +
            esc(o.cashier || "Admin Loket 1") +
            "</dd>" +
            "<dt>Nama</dt><dd>" +
            esc(o.name || "–") +
            "</dd>" +
            "<dt>Asal</dt><dd>" +
            esc(
              o.region
                ? o.region +
                    " (" +
                    (o.origin === "Domestik" ? "Domestik" : "Mancanegara") +
                    ")"
                : "–",
            ) +
            "</dd>" +
            "<dt>Bayar</dt><dd>" +
            pay +
            "</dd>" +
            "</dl>";
          h += '<div class="rc-rule"></div>';
          h +=
            '<div class="rc-strong">' +
            (o.mode === "group" ? "ROMBONGAN (GROUP)" : "INDIVIDUAL") +
            "</div>";

          if (!o.items || !o.items.length) {
            h +=
              '<div class="rc-rule"></div><div class="rc-note">Belum ada jenis tiket dipilih.<br>' +
              "Struk terbentuk otomatis saat transaksi diisi.</div>";
            h +=
              '<div class="rc-rule"></div><div class="rc-foot rc-note">' +
              esc(SETTINGS.receipt_footer) +
              "</div>";
            return h;
          }

          o.items.forEach(function (it) {
            h +=
              '<div class="rc-item"><span>' +
              it.qty +
              "x " +
              esc(it.type.name) +
              "</span>" +
              "<span>" +
              Math.round(it.gross).toLocaleString("id-ID") +
              "</span>" +
              '<span class="rc-item__sub">@' +
              it.type.price.toLocaleString("id-ID") +
              "</span></div>";
          });
          h +=
            '<div class="rc-row"><span>Subtotal</span><span>' +
            Math.round(o.subtotal).toLocaleString("id-ID") +
            "</span></div>";
          if (o.discount > 0) {
            var gd = o.items
              .filter(function (i) {
                return i.type.category === "group";
              })
              .reduce(function (a, b) {
                return a + b.disc;
              }, 0);
            var pct = o.items
              .filter(function (i) {
                return i.disc > 0;
              })
              .map(function (i) {
                return i.type.discount;
              })[0];
            h +=
              '<div class="rc-row"><span>Diskon ' +
              (gd > 0 ? "Rombongan" : "Promo") +
              " " +
              pct +
              "%</span>" +
              "<span>-" +
              Math.round(o.discount).toLocaleString("id-ID") +
              "</span></div>";
          }
          if (o.reprint) {
            h +=
              '<div class="rc-rule"></div><div class="rc-strong">CETAK ULANG</div>' +
              '<div class="rc-note">Dicatat: ' +
              esc(o.reprintBy || "Admin Loket 1") +
              " · " +
              o.time +
              " WITA</div>";
          }
          h += '<div class="rc-rule rc-rule--solid"></div>';
          h +=
            '<div class="rc-total"><span>TOTAL BAYAR</span><span>Rp ' +
            Math.round(o.total).toLocaleString("id-ID") +
            "</span></div>";
          if (o.payment === "cash") {
            if (o.got > 0) {
              h +=
                '<div class="rc-total"><span>TUNAI</span><span>Rp ' +
                Math.round(o.got).toLocaleString("id-ID") +
                "</span></div>" +
                '<div class="rc-total"><span>KEMBALIAN</span><span>Rp ' +
                Math.round(Math.max(0, o.change || 0)).toLocaleString("id-ID") +
                "</span></div>";
            } else {
              h +=
                '<div class="rc-row"><span>TUNAI</span><span>belum diisi</span></div>' +
                '<div class="rc-row"><span>KEMBALIAN</span><span>–</span></div>';
            }
          } else {
            h +=
              '<div class="rc-total"><span>KARTU DEBIT</span><span>Rp ' +
              Math.round(o.total).toLocaleString("id-ID") +
              "</span></div>" +
              '<div class="rc-row" style="font-size:11px"><span>Ref EDC</span><span>' +
              esc(o.edc || "–") +
              "</span></div>";
          }
          h += '<div class="rc-rule"></div>';
          h +=
            '<div class="rc-strong">TOTAL PENGUNJUNG: ' +
            o.visitors +
            " ORANG</div>";
          h +=
            '<div class="rc-qr"><div class="rc-qr__box">' +
            qrHTML(o.code) +
            "</div>" +
            '<div class="rc-code">' +
            o.code +
            "</div></div>";
          h += '<div class="rc-rule"></div>';
          h +=
            '<div class="rc-valid">BERLAKU HANYA HARI INI:<br><b>' +
            validDate +
            "</b><br>SAMPAI PUKUL 24.00 WITA</div>";
          h +=
            '<div class="rc-foot rc-note">' +
            esc(SETTINGS.receipt_footer) +
            "</div>";
          return h;
        }

        function receiptCoupon(tx, code, opts) {
          var o = {
            nota: tx.nota,
            time: tx.time,
            cashier: tx.cashier,
            name: tx.name,
            origin: tx.origin,
            region: tx.region,
            mode: tx.mode,
            items: tx.items,
            subtotal: tx.subtotal,
            discount: tx.discount,
            total: tx.total,
            visitors: tx.visitors,
            payment: tx.payment,
            got: tx.got,
            change: tx.change,
            edc: tx.edc,
            code: code,
          };
          if (opts && opts.reprint) {
            o.reprint = true;
            o.reprintBy = opts.reprintBy;
          }
          if (opts && opts.noPayment) {
            return (
              '<div class="rc-head"><b>' +
              esc(SETTINGS.business_name).toUpperCase() +
              "</b><br>" +
              esc(SETTINGS.business_address) +
              '</div><div class="rc-rule"></div>' +
              '<dl class="rc-meta"><dt>Tiket</dt><dd>' +
              code +
              "</dd>" +
              "<dt>Nama</dt><dd>" +
              esc(tx.name) +
              "</dd>" +
              "<dt>Jenis</dt><dd>" +
              (tx.mode === "group" ? "Rombongan" : "Individu 1 orang") +
              "</dd></dl>" +
              '<div class="rc-qr"><div class="rc-qr__box">' +
              qrHTML(code) +
              "</div>" +
              '<div class="rc-code">' +
              code +
              '</div></div><div class="rc-rule"></div>' +
              '<div class="rc-valid">BERLAKU HANYA HARI INI (<b>' +
              humanLong(nowWita()).toUpperCase() +
              '</b>) SAMPAI 24.00 WITA</div><div class="rc-foot rc-note">' +
              esc(SETTINGS.receipt_footer) +
              "</div>"
            );
          }
          return receiptBody(o);
        }

        function printCoupons(html) {
          $("#print-area").innerHTML = html;
          window.print();
        }

        /* ------------------------------------------------------------------ header */
        function renderBoard() {
          var s = TODAYS;
          var y = HISTORY[HISTORY.length - 1];
          var yTotal = y.cash + y.debit;
          var diff = yTotal
            ? Math.round(((s.sales - yTotal) / yTotal) * 100)
            : 0;
          var trendCls = diff >= 0 ? "trend--up" : "trend--down";
          var indT =
            s.tickets -
            TXS.filter(function (t) {
              return !t.void && t.mode === "group";
            }).length;
          var grpT = TXS.filter(function (t) {
            return !t.void && t.mode === "group";
          }).length;
          var masuk = s.visitors
            ? Math.round((s.checkedVisitors / s.visitors) * 100)
            : 0;

          var cells = [
            '<div class="cell cell--money">' +
              '<div class="cell__label">Penjualan hari ini</div>' +
              '<div class="cell__value">' +
              rp(s.sales) +
              "</div>" +
              '<div class="cell__foot"><span class="trend ' +
              trendCls +
              '">' +
              (diff >= 0 ? "+" : "") +
              diff +
              "%</span> dari kemarin (" +
              rp(yTotal) +
              ")</div>" +
              "</div>",
            '<div class="cell">' +
              '<div class="cell__label">Tiket terbit</div>' +
              '<div class="cell__value">' +
              s.tickets +
              "<small> tiket</small></div>" +
              '<div class="cell__foot">' +
              indT +
              " individual &middot; " +
              grpT +
              " rombongan</div>" +
              "</div>",
            '<div class="cell">' +
              '<div class="cell__label">Pengunjung sudah masuk</div>' +
              '<div class="cell__value">' +
              s.checkedVisitors +
              "<small> orang</small></div>" +
              '<div class="cell__foot">' +
              masuk +
              "% dari " +
              s.visitors +
              " pengunjung terjual</div>" +
              "</div>",
            '<div class="cell">' +
              '<div class="cell__label"><span class="pip" style="color:var(--c-gold)"></span> Belum dipakai</div>' +
              '<div class="cell__value">' +
              s.active +
              "<small> tiket</small></div>" +
              '<div class="cell__foot">' +
              s.activeVisitors +
              " pengunjung &middot; hangus 24.00 WITA</div>" +
              "</div>",
          ];
          $("#board-cells").innerHTML = cells.join("");

          var tc = s.cash + s.debit;
          var pc = tc ? (s.cash / tc) * 100 : 0;
          $("#paybar-track").innerHTML =
            '<div class="paybar__seg paybar__seg--cash" style="width:' +
            pc.toFixed(1) +
            '%"></div>' +
            '<div class="paybar__seg paybar__seg--debit" style="width:' +
            (100 - pc).toFixed(1) +
            '%"></div>';
          $("#paybar-legend").innerHTML =
            '<span><i class="key" style="background:var(--c-ink)"></i>Tunai <b>' +
            rp(s.cash) +
            "</b> (" +
            s.cashN +
            " nota)</span>" +
            '<span><i class="key" style="background:var(--c-primary)"></i>Debit <b>' +
            rp(s.debit) +
            "</b> (" +
            s.debitN +
            " nota)</span>" +
            '<span style="margin-left:auto">Total diterima <b>' +
            rp(tc) +
            "</b></span>";

          $("#recon-body").innerHTML =
            '<div class="bars">' +
            reconRow("Setoran tunai", s.cash, tc, "ink") +
            reconRow("Mesin EDC", s.debit, tc, "") +
            "</div>" +
            '<div class="rc-rule" style="margin:16px 0 12px"></div>' +
            '<div class="summary__row"><span>Jumlah nota</span><b>' +
            s.notes +
            " nota</b></div>" +
            '<div class="summary__row"><span>Diskon diberikan</span><b>−' +
            rp(s.discInd + s.discGroup) +
            "</b></div>" +
            (s.voidCount
              ? '<div class="summary__row"><span>Nota di-void</span><b>' +
                s.voidCount +
                " nota · " +
                rp(s.voidValue) +
                "</b></div>"
              : "") +
            '<div class="callout callout--info" style="margin-top:14px">' +
            '<svg class="ico ico--sm"><use href="#i-info"></use></svg>' +
            "<div>Angka tunai dicocokkan dengan isi laci, angka EDC dengan slip mesin. " +
            "Void bukan refund.</div></div>";

          // transaksi terbaru
          var rows = TXS.slice(0, 5).map(function (tx) {
            var st = ticketState(tx);
            var lb = TICKET_LABEL[st];
            var canPrint = tx.tickets.some(function (t) {
              return t.status === "active";
            });
            return (
              "<tr>" +
              '<td class="num nowrap">' +
              tx.time +
              "</td>" +
              '<td class="mono" style="font-size:12px">' +
              tx.nota +
              "</td>" +
              '<td><div class="tbl__who">' +
              esc(tx.name) +
              "</div>" +
              '<div class="tbl__sub">' +
              tx.visitors +
              " pengunjung · " +
              esc(tx.region) +
              "</div></td>" +
              "<td>" +
              (tx.mode === "group" ? "Rombongan" : "Individual") +
              "</td>" +
              "<td>" +
              (tx.payment === "cash" ? "Tunai" : "Debit") +
              "</td>" +
              '<td class="r num">' +
              rp(tx.total) +
              "</td>" +
              '<td><span class="badge ' +
              lb.cls +
              '">' +
              lb.txt +
              "</span></td>" +
              '<td><div class="tbl__actions">' +
              '<button class="iconbtn" ' +
              (canPrint ? "" : "disabled ") +
              'title="' +
              (canPrint
                ? "Cetak ulang tiket aktif"
                : "Tidak ada tiket aktif untuk dicetak ulang") +
              '" data-reprint="' +
              tx.id +
              '"><svg class="ico ico--sm"><use href="#i-printer"></use></svg></button>' +
              '<button class="iconbtn iconbtn--danger" ' +
              (tx.void ? "disabled " : "") +
              'title="' +
              (tx.void ? "Nota sudah di-void" : "Void administratif") +
              '" data-void="' +
              tx.id +
              '"><svg class="ico ico--sm"><use href="#i-ban"></use></svg></button>' +
              "</div></td></tr>"
            );
          });
          $("#recent-body").innerHTML = rows.join("");
        }

        function reconRow(label, value, total, tone) {
          var pct = total ? (value / total) * 100 : 0;
          return (
            '<div class="barrow"><span>' +
            label +
            "</span>" +
            '<span class="barrow__track"><span class="barrow__fill' +
            (tone === "ink" ? " barrow__fill--ink" : "") +
            '" style="width:' +
            pct.toFixed(1) +
            '%"></span></span>' +
            '<span class="barrow__val">' +
            rp(value) +
            "</span></div>"
          );
        }

        /* ------------------------------------------------------------------ grafik */
        function sevenDays() {
          var out = [];
          HISTORY.forEach(function (h) {
            var d = dayShift(h.off);
            out.push({
              label: DAYS_S[d.getDay()],
              date: humanShort(d),
              total: h.cash + h.debit,
              today: false,
            });
          });
          var d0 = nowWita();
          out.push({
            label: "Hari ini",
            date: humanLong(d0),
            total: TODAYS.sales,
            today: true,
          });
          return out;
        }
        function renderChart() {
          var data = sevenDays();
          var host = $("#chart");
          var W = Math.max(360, host.parentElement.clientWidth);
          var H = 214,
            padL = 62,
            padR = 14,
            padT = 16,
            padB = 32;
          var max = Math.max.apply(
            null,
            data.map(function (d) {
              return d.total;
            }),
          );
          max = (Math.ceil((max / 1000000) * 2) / 2) * 1000000;
          var iw = W - padL - padR,
            ih = H - padT - padB;
          var x = function (i) {
            return padL + (iw * i) / (data.length - 1);
          };
          var y = function (v) {
            return padT + ih - ih * (v / max);
          };

          var s =
            '<defs><linearGradient id="gradA" x1="0" y1="0" x2="0" y2="1">' +
            '<stop offset="0%" stop-color="#CF4647" stop-opacity=".16"/>' +
            '<stop offset="100%" stop-color="#CF4647" stop-opacity="0"/></linearGradient></defs>';
          for (var g = 0; g <= 4; g++) {
            var v = (max * g) / 4,
              yy = y(v);
            s +=
              '<line class="grid-line" x1="' +
              padL +
              '" y1="' +
              yy +
              '" x2="' +
              (W - padR) +
              '" y2="' +
              yy +
              '"/>';
            s +=
              '<text x="' +
              (padL - 10) +
              '" y="' +
              (yy + 3.5) +
              '" text-anchor="end">' +
              rpShort(v) +
              "</text>";
          }
          var line = data
            .map(function (d, i) {
              return (i ? "L" : "M") + x(i) + "," + y(d.total);
            })
            .join(" ");
          s +=
            '<path class="area" d="' +
            line +
            " L" +
            x(data.length - 1) +
            "," +
            (padT + ih) +
            " L" +
            padL +
            "," +
            (padT + ih) +
            ' Z"/>';
          s += '<path class="series" d="' + line + '"/>';
          data.forEach(function (d, i) {
            s +=
              '<circle class="' +
              (d.today ? "pt-today" : "pt") +
              '" cx="' +
              x(i) +
              '" cy="' +
              y(d.total) +
              '" r="' +
              (d.today ? 5 : 3.5) +
              '"><title>' +
              esc(d.date) +
              " · " +
              rp(d.total) +
              "</title></circle>";
            s +=
              '<text x="' +
              x(i) +
              '" y="' +
              (H - 12) +
              '" text-anchor="middle"' +
              (d.today ? ' style="font-weight:600;fill:#2A363B"' : "") +
              ">" +
              esc(d.label) +
              "</text>";
            if (d.today) {
              s +=
                '<text x="' +
                x(i) +
                '" y="' +
                (y(d.total) - 12) +
                '" text-anchor="end" ' +
                'style="font-weight:600;fill:#2A363B">' +
                rp(d.total) +
                "</text>";
            }
          });
          host.setAttribute("viewBox", "0 0 " + W + " " + H);
          host.setAttribute("width", W);
          host.setAttribute("height", H);
          host.innerHTML = s;

          var avg = Math.round(
            data.reduce(function (a, b) {
              return a + b.total;
            }, 0) / data.length,
          );
          $("#chart-legend").innerHTML =
            "<span>Rata-rata tujuh hari <b>" +
            rp(avg) +
            "</b></span>" +
            "<span>Tertinggi <b>" +
            rp(
              Math.max.apply(
                null,
                data.map(function (d) {
                  return d.total;
                }),
              ),
            ) +
            "</b></span>" +
            "<span>Hari ini <b>" +
            rp(TODAYS.sales) +
            "</b></span>";
        }

        /* ============================================================== POS LOKET */
        var MAX_IND = function () {
          return SETTINGS.max_individual_per_tx;
        };
        var MIN_GRP = function () {
          return SETTINGS.min_group_size;
        };
        var MAX_GRP = function () {
          return SETTINGS.max_group_members;
        };

        var CITIES = [
          "Denpasar",
          "Badung",
          "Gianyar",
          "Tabanan",
          "Buleleng",
          "Klungkung",
          "Bangli",
          "Karangasem",
          "Jembrana",
          "Jakarta",
          "Surabaya",
          "Bandung",
          "Yogyakarta",
          "Medan",
          "Makassar",
          "Lainnya",
        ];
        var COUNTRIES = [
          "Australia",
          "Jerman",
          "Inggris",
          "Prancis",
          "Belanda",
          "Jepang",
          "Tiongkok",
          "Amerika Serikat",
          "Polandia",
          "Italia",
          "Spanyol",
          "Korea Selatan",
          "India",
          "Kanada",
          "Selandia Baru",
        ];

        var pos = {
          step: 1,
          mode: "individual",
          origin: "domestic",
          region: "",
          name: "",
          phone: "",
          address: "",
          qty: {},
          pay: "cash",
          cash: "",
          edc: "",
        };

        function typesFor(mode) {
          return TYPES.filter(function (t) {
            return t.category === mode && t.active;
          });
        }
        function posItems() {
          return typesFor(pos.mode)
            .map(function (ty) {
              var qty = pos.qty[ty.id] || 0;
              var gross = ty.price * qty;
              var disc = Math.round((gross * ty.discount) / 100);
              return {
                type: ty,
                qty: qty,
                gross: gross,
                disc: disc,
                net: gross - disc,
              };
            })
            .filter(function (i) {
              return i.qty > 0;
            });
        }
        function posTotals() {
          var items = posItems();
          return {
            items: items,
            visitors: items.reduce(function (a, b) {
              return a + b.qty;
            }, 0),
            subtotal: items.reduce(function (a, b) {
              return a + b.gross;
            }, 0),
            discount: items.reduce(function (a, b) {
              return a + b.disc;
            }, 0),
            total: items.reduce(function (a, b) {
              return a + b.net;
            }, 0),
          };
        }

        var STEPS = [
          { n: 1, label: "Data pengunjung", sub: "Nama & asal" },
          { n: 2, label: "Jenis & jumlah", sub: "Sesuai kategori" },
          { n: 3, label: "Pembayaran", sub: "Tunai atau debit" },
          { n: 4, label: "Simpan & cetak", sub: "Struk thermal" },
        ];
        function renderStepper() {
          $("#pos-stepper").innerHTML = STEPS.map(function (s) {
            var cls =
              pos.step === s.n ? "is-on" : pos.step > s.n ? "is-done" : "";
            var canGo = s.n < pos.step;
            return (
              '<li class="step ' +
              cls +
              '">' +
              '<div class="step__top">' +
              '<button class="step__num" ' +
              (canGo ? "" : 'disabled aria-disabled="true" ') +
              'style="' +
              (canGo ? "cursor:pointer" : "cursor:default") +
              '" data-step-go="' +
              s.n +
              '" ' +
              'aria-label="Langkah ' +
              s.n +
              ": " +
              s.label +
              '">' +
              (pos.step > s.n
                ? '<svg class="ico ico--sm"><use href="#i-check"></use></svg>'
                : s.n) +
              "</button>" +
              '<span class="step__line"></span>' +
              "</div>" +
              '<button class="step__label" style="display:block" data-step-go="' +
              s.n +
              '" ' +
              'aria-current="' +
              (pos.step === s.n) +
              '">' +
              s.label +
              "</button>" +
              '<div class="step__sub">' +
              s.sub +
              "</div>" +
              "</li>"
            );
          }).join("");
        }

        function renderPosTypes() {
          var list = typesFor(pos.mode);
          var tot = posTotals();
          var html = "";
          list.forEach(function (ty) {
            var qty = pos.qty[ty.id] || 0;
            var pair = TYPES.filter(function (t) {
              return (
                t.name.replace(" Rombongan", "") ===
                  ty.name.replace(" Rombongan", "") && t.id !== ty.id
              );
            })[0];
            html +=
              '<div class="rowline">' +
              '<div><div class="rowline__name">' +
              esc(ty.name) +
              "</div>" +
              '<div class="rowline__meta">' +
              rp(ty.price) +
              " / orang" +
              (ty.discount ? " &middot; diskon " + ty.discount + "%" : "") +
              (pair
                ? " &middot; pasangan: " + esc(pair.name) + " " + rp(pair.price)
                : "") +
              "</div></div>" +
              '<div class="qty">' +
              '<button data-qty="-1" data-id="' +
              ty.id +
              '" aria-label="Kurangi ' +
              esc(ty.name) +
              '" ' +
              (qty <= 0 ? "disabled" : "") +
              '><svg class="ico ico--sm"><use href="#i-minus"></use></svg></button>' +
              '<input class="num" type="text" inputmode="numeric" value="' +
              qty +
              '" ' +
              'data-qty-input="' +
              ty.id +
              '" aria-label="Jumlah ' +
              esc(ty.name) +
              '">' +
              '<button data-qty="1" data-id="' +
              ty.id +
              '" aria-label="Tambah ' +
              esc(ty.name) +
              '">' +
              '<svg class="ico ico--sm"><use href="#i-plus"></use></svg></button>' +
              "</div>" +
              '<div class="rowline__sum num">' +
              (qty ? rp((ty.price * qty * (100 - ty.discount)) / 100) : "–") +
              "</div>" +
              "</div>";
          });
          html +=
            '<p class="msg" id="pos-limit-msg" style="margin-top:14px"><svg class="ico ico--sm"><use href="#i-info"></use></svg><span>' +
            (pos.mode === "group"
              ? "Rombongan: " +
                MIN_GRP() +
                "–" +
                MAX_GRP() +
                " orang dalam satu tiket group. Seluruh anggota masuk dengan satu kali scan."
              : "Individual: maksimum " +
                MAX_IND() +
                " tiket per transaksi, 1 QR per orang.") +
            "</span></p>";
          $("#pos-types").innerHTML = html;
          renderSummary("#pos-summary-2", tot);
        }

        function renderSummary(sel, tot) {
          var node = $(sel);
          if (!node) return;
          var key = tot.subtotal + ":" + tot.total + ":" + tot.visitors;
          var changed =
            node.getAttribute("data-key") &&
            node.getAttribute("data-key") !== key;
          node.setAttribute("data-key", key);
          node.innerHTML =
            '<div class="summary__row"><span>Pengunjung</span><b>' +
            tot.visitors +
            " orang</b></div>" +
            '<div class="summary__row"><span>Subtotal</span><b>' +
            rp(tot.subtotal) +
            "</b></div>" +
            '<div class="summary__row summary__row--disc"><span>Diskon</span><b>−' +
            rp(tot.discount) +
            "</b></div>" +
            '<div class="summary__row summary__row--total"><span>Total bayar</span><b>' +
            rp(tot.total) +
            "</b></div>";
          if (changed) node.classList.add("flash");
          setTimeout(function () {
            node.classList.remove("flash");
          }, 640);
        }

        function posMessages() {
          var t = posTotals(),
            out = [];
          if (pos.step >= 2) {
            if (!t.visitors) out.push(["warn", "Belum ada tiket dipilih."]);
            else if (pos.mode === "individual" && t.visitors > MAX_IND())
              out.push([
                "err",
                "Individual maksimum " +
                  MAX_IND() +
                  " tiket per transaksi. " +
                  t.visitors +
                  " orang dijual sebagai tiket rombongan (minimal " +
                  MIN_GRP() +
                  " orang).",
              ]);
            else if (pos.mode === "group" && t.visitors < MIN_GRP())
              out.push([
                "err",
                "Rombongan minimal " +
                  MIN_GRP() +
                  " orang. Pembelian " +
                  t.visitors +
                  " orang wajib diisi sebagai tiket individual.",
              ]);
            else if (pos.mode === "group" && t.visitors > MAX_GRP())
              out.push([
                "err",
                "Rombongan maksimum " +
                  MAX_GRP() +
                  " orang per tiket. Buat rombongan terpisah untuk sisanya.",
              ]);
          }
          if (pos.step >= 3) {
            if (pos.pay === "cash") {
              var got = Number(String(pos.cash).replace(/\D/g, "")) || 0;
              if (t.total && got && got < t.total)
                out.push([
                  "err",
                  "Uang diterima kurang " +
                    rp(t.total - got) +
                    " dari total bayar.",
                ]);
              else if (t.total && !got)
                out.push([
                  "warn",
                  "Isi uang diterima untuk menghitung kembalian.",
                ]);
            } else if (!pos.edc.trim()) {
              out.push([
                "warn",
                "Nomor referensi EDC wajib untuk pembayaran debit.",
              ]);
            }
          }
          return out;
        }

        function renderReceipt() {
          var tot = posTotals();
          var d = nowWita();
          var code;
          if (pos.mode === "group") code = ticketCode("GRP");
          else code = ticketCode("IND");

          var pseudo = {
            nota: "INV-" + isoOf(d).replace(/-/g, "") + "-••••••",
            time: pad(d.getHours()) + ":" + pad(d.getMinutes()),
            cashier: "Admin Loket 1",
            name: pos.name.trim(),
            origin: pos.origin === "domestic" ? "Domestik" : "Mancanegara",
            region: pos.region,
            mode: pos.mode,
            items: tot.items,
            subtotal: tot.subtotal,
            discount: tot.discount,
            total: tot.total,
            visitors: tot.visitors,
            payment: pos.pay,
            got: Number(String(pos.cash).replace(/\D/g, "")) || 0,
            change:
              (Number(String(pos.cash).replace(/\D/g, "")) || 0) - tot.total,
            edc: pos.edc.trim(),
            code: code,
          };
          var sheet = $("#receipt");
          var key = JSON.stringify([
            pos.name,
            pos.region,
            pos.mode,
            pos.pay,
            pos.cash,
            pos.edc,
            tot.total,
            tot.visitors,
          ]);
          var changed =
            sheet.getAttribute("data-key") &&
            sheet.getAttribute("data-key") !== key;
          sheet.setAttribute("data-key", key);
          sheet.innerHTML = receiptBody(pseudo);
          if (changed) sheet.classList.add("flash-ink");
          setTimeout(function () {
            sheet.classList.remove("flash-ink");
          }, 520);

          var coupons = pos.mode === "group" ? 1 : Math.max(1, tot.visitors);
          $("#receipt-nav").innerHTML = tot.visitors
            ? "<span>Potongan 1 dari " +
              coupons +
              "</span>" +
              '<span style="margin-left:auto">' +
              (coupons > 1
                ? "Potongan 2–" + coupons + " memuat QR tiket lainnya"
                : "Satu potongan, satu QR") +
              "</span>"
            : "<span>Struk kosong menunggu data transaksi</span>";
        }

        function renderPosStep() {
          $$(".panel-step").forEach(function (p) {
            p.classList.toggle(
              "is-on",
              Number(p.getAttribute("data-step")) === pos.step,
            );
          });
          renderStepper();
          $("#pos-back").disabled = pos.step === 1;

          var next = $("#pos-next"),
            save = $("#pos-save"),
            msgs = posMessages();
          var msgBox = $("#pos-step-msg");
          msgBox.className = "msg msg--" + (msgs.length ? msgs[0][0] : "");
          msgBox.innerHTML = msgs.length
            ? '<svg class="ico ico--sm"><use href="#i-alert"></use></svg><span>' +
              esc(msgs[0][1]) +
              "</span>"
            : '<span class="muted">' +
              (pos.step === 4
                ? "Periksa sekali lagi, lalu simpan & cetak."
                : "") +
              "</span>";

          if (pos.step === 4) {
            next.innerHTML =
              '<svg class="ico"><use href="#i-printer"></use></svg> Simpan &amp; cetak (Ctrl+P)';
            save.hidden = false;
            renderConfirm();
          } else {
            next.innerHTML =
              'Lanjut <svg class="ico"><use href="#i-arrow-right"></use></svg>';
            save.hidden = true;
          }
          renderReceipt();
        }

        function renderConfirm() {
          var t = posTotals();
          var payLbl =
            pos.pay === "cash"
              ? "Tunai · diterima " +
                rp(Number(String(pos.cash).replace(/\D/g, "")) || 0) +
                " · kembalian " +
                rp((Number(String(pos.cash).replace(/\D/g, "")) || 0) - t.total)
              : "Debit · ref " + esc(pos.edc);
          var rows = [
            ["Nama penanggung jawab", esc(pos.name || "–")],
            [
              "Asal",
              esc(pos.region || "–") +
                " (" +
                (pos.origin === "domestic" ? "Domestik" : "Mancanegara") +
                ")",
            ],
            [
              "Jenis penjualan",
              pos.mode === "group"
                ? "Rombongan · 1 tiket group"
                : "Individual · " + t.visitors + " tiket",
            ],
            ["Pengunjung", t.visitors + " orang"],
            ["Pembayaran", payLbl],
            ["Tanggal berlaku", humanLong(nowWita()) + " sampai 24.00 WITA"],
          ];
          $("#pos-confirm-body").innerHTML =
            '<div class="callout callout--ok"><svg class="ico"><use href="#i-check"></use></svg>' +
            "<div><b>Siap disimpan.</b> Transaksi, tiket, dan rincian harga tersimpan dalam satu transaksi database. " +
            "Tiket langsung berstatus <b>aktif</b> dan berlaku sampai pukul 24.00 WITA hari ini.</div></div>" +
            '<dl class="rc-meta" style="grid-template-columns:190px 1fr;margin-top:16px;font-size:13px;' +
            'font-family:var(--font-ui);row-gap:10px">' +
            rows
              .map(function (r) {
                return (
                  '<dt style="color:var(--c-muted)">' +
                  r[0] +
                  '</dt><dd style="text-align:left">' +
                  r[1] +
                  "</dd>"
                );
              })
              .join("") +
            "</dl>";
        }

        function validateStep() {
          if (pos.step === 1) {
            var okName = pos.name.trim().length >= 3;
            $("#pos-name").classList.toggle("is-bad", !okName);
            var okRegion = !!pos.region;
            $("#pos-region").classList.toggle("is-bad", !okRegion);
            if (!okName) return "Nama pengunjung minimal 3 karakter.";
            if (!okRegion)
              return pos.origin === "domestic"
                ? "Pilih kota/kabupaten asal pengunjung."
                : "Pilih negara asal pengunjung.";
            return null;
          }
          if (pos.step === 2) {
            var m = posMessages().filter(function (x) {
              return x[0] === "err";
            });
            return m.length ? m[0][1] : null;
          }
          if (pos.step === 3) {
            var t = posTotals();
            if (pos.pay === "cash") {
              var got = Number(String(pos.cash).replace(/\D/g, "")) || 0;
              if (got < t.total)
                return "Uang diterima kurang dari total bayar.";
            } else if (!pos.edc.trim())
              return "Nomor referensi EDC wajib diisi untuk pembayaran debit.";
            return null;
          }
          return null;
        }

        function buildTxFromPos() {
          var t = posTotals();
          var d = nowWita();
          var tickets = [];
          if (pos.mode === "group") {
            tickets.push({
              code: ticketCode("GRP"),
              status: "active",
              visitors: t.visitors,
            });
          } else {
            for (var i = 0; i < t.visitors; i++)
              tickets.push({
                code: ticketCode("IND"),
                status: "active",
                visitors: 1,
              });
          }
          return {
            time: pad(d.getHours()) + ":" + pad(d.getMinutes()),
            nota: notaCode(d),
            cashier: "Admin Loket 1",
            name: pos.name.trim(),
            origin: pos.origin === "domestic" ? "Domestik" : "Mancanegara",
            region: pos.region,
            mode: pos.mode,
            items: t.items,
            subtotal: t.subtotal,
            discount: t.discount,
            total: t.total,
            visitors: t.visitors,
            payment: pos.pay,
            edc: pos.edc.trim(),
            got: Number(String(pos.cash).replace(/\D/g, "")) || 0,
            change:
              (Number(String(pos.cash).replace(/\D/g, "")) || 0) - t.total,
            tickets: tickets,
          };
        }

        function resetPos(silent) {
          pos = {
            step: 1,
            mode: "individual",
            origin: "domestic",
            region: "",
            name: "",
            phone: "",
            address: "",
            qty: {},
            pay: "cash",
            cash: "",
            edc: "",
          };
          $("#pos-name").value = "";
          $("#pos-phone").value = "";
          $("#pos-address").value = "";
          $("#pos-cash").value = "";
          $("#pos-edc").value = "";
          $$("input[name=origin]")[0].checked = true;
          $$("input[name=mode]")[0].checked = true;
          $$("input[name=pay]")[0].checked = true;
          fillRegions();
          renderPosTypes();
          updatePayUI();
          renderPosStep();
          if (!silent)
            toast(
              "info",
              "Form dikosongkan",
              "Siap untuk transaksi berikutnya.",
            );
        }

        function fillRegions() {
          var dom = pos.origin === "domestic";
          $("#pos-region-label").textContent = dom
            ? "Kota / kabupaten"
            : "Negara";
          var list = dom ? CITIES : COUNTRIES;
          $("#pos-region").innerHTML =
            '<option value="">Pilih ' +
            (dom ? "kota / kabupaten" : "negara") +
            "…</option>" +
            list
              .map(function (c) {
                return (
                  '<option value="' +
                  esc(c) +
                  '"' +
                  (pos.region === c ? " selected" : "") +
                  ">" +
                  esc(c) +
                  "</option>"
                );
              })
              .join("");
        }

        function updatePayUI() {
          var cash = pos.pay === "cash";
          $("#pos-cash-field").hidden = !cash;
          $("#pos-edc-field").hidden = cash;
          var t = posTotals();
          var got = Number(String(pos.cash).replace(/\D/g, "")) || 0;
          var chg = got - t.total;
          $("#pos-change").textContent = cash ? rp(chg > 0 ? chg : 0) : "–";
          var m = $("#pos-cash-msg");
          if (cash && t.total && got && got < t.total) {
            m.className = "msg msg--err";
            m.innerHTML =
              '<svg class="ico ico--sm"><use href="#i-alert"></use></svg><span>Kurang ' +
              rp(t.total - got) +
              " dari total bayar.</span>";
            $("#pos-cash").classList.add("is-bad");
          } else {
            m.className = "msg";
            m.innerHTML =
              '<svg class="ico ico--sm"><use href="#i-info"></use></svg><span>Tidak boleh kurang dari total bayar.</span>';
            $("#pos-cash").classList.remove("is-bad");
          }
          renderSummary("#pos-summary-3", t);
        }

        function bindPos() {
          $("#pos-origin-opts").addEventListener("change", function () {
            pos.origin = $$("input[name=origin]").filter(function (r) {
              return r.checked;
            })[0].value;
            pos.region = "";
            fillRegions();
          });
          $("#pos-mode-opts").addEventListener("change", function () {
            pos.mode = $$("input[name=mode]").filter(function (r) {
              return r.checked;
            })[0].value;
            pos.qty = {};
            $("#pos-mode-msg").innerHTML =
              '<svg class="ico ico--sm"><use href="#i-info"></use></svg><span>' +
              (pos.mode === "group"
                ? "Satu tiket group mewakili seluruh rombongan (" +
                  MIN_GRP() +
                  "–" +
                  MAX_GRP() +
                  " orang) dan hanya dijual di kategori group."
                : "Batas " +
                  MAX_IND() +
                  " tiket per transaksi. Total pengunjung " +
                  MIN_GRP() +
                  " orang atau lebih wajib dibeli sebagai rombongan.") +
              "</span>";
            renderPosTypes();
            renderPosStep();
          });
          $("#pos-pay-opts").addEventListener("change", function () {
            pos.pay = $$("input[name=pay]").filter(function (r) {
              return r.checked;
            })[0].value;
            updatePayUI();
            renderPosStep();
          });
          $("#pos-name").addEventListener("input", function (e) {
            pos.name = e.target.value;
            e.target.classList.remove("is-bad");
            renderReceipt();
          });
          $("#pos-region").addEventListener("change", function (e) {
            pos.region = e.target.value;
            e.target.classList.remove("is-bad");
            renderReceipt();
          });
          $("#pos-phone").addEventListener("input", function (e) {
            pos.phone = e.target.value;
          });
          $("#pos-address").addEventListener("input", function (e) {
            pos.address = e.target.value;
          });
          $("#pos-cash").addEventListener("input", function (e) {
            var digits = e.target.value.replace(/\D/g, "");
            pos.cash = digits;
            e.target.value = digits
              ? Number(digits).toLocaleString("id-ID")
              : "";
            updatePayUI();
            renderReceipt();
          });
          $("#pos-edc").addEventListener("input", function (e) {
            pos.edc = e.target.value;
            renderReceipt();
          });

          $("#pos-types").addEventListener("click", function (e) {
            var b = e.target.closest("[data-qty]");
            if (!b) return;
            var id = Number(b.getAttribute("data-id"));
            var d = Number(b.getAttribute("data-qty"));
            var t = posTotals();
            if (d > 0 && t.visitors + 1 > MAX_GRP()) {
              toast(
                "warn",
                "Batas tercapai",
                "Maksimum " + MAX_GRP() + " orang per transaksi.",
              );
              return;
            }
            pos.qty[id] = Math.max(0, (pos.qty[id] || 0) + d);
            renderPosTypes();
            renderPosStep();
          });
          $("#pos-types").addEventListener("change", function (e) {
            var inp = e.target.closest("[data-qty-input]");
            if (!inp) return;
            var id = Number(inp.getAttribute("data-qty-input"));
            var v = Math.max(
              0,
              Math.min(
                MAX_GRP(),
                Number(String(inp.value).replace(/\D/g, "")) || 0,
              ),
            );
            pos.qty[id] = v;
            renderPosTypes();
            renderPosStep();
          });
          $("#pos-stepper").addEventListener("click", function (e) {
            var b = e.target.closest("[data-step-go]");
            if (!b) return;
            var n = Number(b.getAttribute("data-step-go"));
            if (n >= pos.step) return;
            pos.step = n;
            renderPosStep();
          });
          $("#pos-back").addEventListener("click", function () {
            if (pos.step > 1) {
              pos.step--;
              renderPosStep();
            }
          });
          $("#pos-reset").addEventListener("click", function () {
            openModal({
              title: "Kosongkan form transaksi?",
              sub: "Data pengunjung, jenis tiket, dan pembayaran yang sudah diisi akan hilang.",
              body: '<p class="caption">Transaksi belum tersimpan, jadi tidak ada nota yang perlu di-void.</p>',
              footer:
                '<button class="btn btn--ghost" data-close>Batal</button>' +
                '<button class="btn btn--danger" id="m-reset-ok">Kosongkan form</button>',
              onMount: function (r) {
                $("#m-reset-ok", r).addEventListener("click", function () {
                  modalClose();
                  resetPos();
                });
              },
            });
          });
          $("#pos-next").addEventListener("click", function () {
            var err = validateStep();
            if (err) {
              toast("err", "Belum bisa lanjut", err);
              return;
            }
            if (pos.step < 4) {
              pos.step++;
              renderPosStep();
              return;
            }
            finishSale(true);
          });
          $("#pos-save").addEventListener("click", function () {
            finishSale(false);
          });
        }

        function finishSale(print) {
          var err = validateStep();
          if (err) {
            toast("err", "Belum bisa disimpan", err);
            return;
          }
          var tx = buildTxFromPos();
          var coupons = tx.tickets
            .map(function (t, i) {
              return receiptCoupon(
                tx,
                t.code,
                i === 0 ? {} : { noPayment: true },
              );
            })
            .join("");
          openModal({
            wide: true,
            title: "Transaksi tersimpan",
            sub:
              tx.nota + " · " + tx.visitors + " pengunjung · " + rp(tx.total),
            body:
              '<div class="callout callout--ok"><svg class="ico"><use href="#i-check"></use></svg>' +
              "<div><b>" +
              tx.tickets.length +
              " tiket berstatus aktif</b>, berlaku sampai pukul 24.00 WITA hari ini. " +
              "Serahkan potongan kertas ke pengunjung: satu potongan memuat satu QR.</div></div>" +
              '<div class="stack" style="margin-top:18px">' +
              tx.tickets
                .map(function (t, i) {
                  return (
                    '<div class="receipt-wrap"><div class="receipt receipt--bare">' +
                    receiptCoupon(
                      tx,
                      t.code,
                      i === 0 ? {} : { noPayment: true },
                    ) +
                    "</div></div>"
                  );
                })
                .join("") +
              "</div>",
            footer:
              '<button class="btn btn--ghost" data-close>Transaksi berikutnya</button>' +
              '<button class="btn btn--primary" id="m-print">' +
              '<svg class="ico"><use href="#i-printer"></use></svg> Cetak ' +
              tx.tickets.length +
              " potongan</button>",
            onMount: function (r) {
              $("#m-print", r).addEventListener("click", function () {
                printCoupons(coupons);
                toast(
                  "info",
                  "Dialog cetak dibuka",
                  "Pilih printer thermal 80 mm, lalu cetak.",
                );
              });
            },
          });
          toast("ok", "Transaksi tersimpan", tx.nota + " · " + rp(tx.total));
          resetPos(true);
        }

        /* ============================================================== RIWAYAT */
        var rw = {
          date: isoOf(nowWita()),
          by: "",
          pay: "",
          status: "",
          q: "",
          page: 1,
          per: 15,
        };

        function riwayatRows() {
          return TXS.filter(function (tx) {
            if (rw.date && tx.date !== rw.date) return false;
            if (rw.by && tx.cashier !== rw.by) return false;
            if (rw.pay && tx.payment !== rw.pay) return false;
            if (rw.status) {
              var st = ticketState(tx);
              if (
                rw.status === "active" &&
                !(st === "active" || st === "partial")
              )
                return false;
              if (rw.status === "used" && st !== "used") return false;
              if (rw.status === "cancelled" && st !== "cancelled") return false;
            }
            if (rw.q) {
              var hay = (
                tx.nota +
                " " +
                tx.name +
                " " +
                tx.region +
                " " +
                tx.tickets
                  .map(function (t) {
                    return t.code;
                  })
                  .join(" ")
              ).toLowerCase();
              if (hay.indexOf(rw.q.toLowerCase()) < 0) return false;
            }
            return true;
          });
        }

        function renderRiwayat() {
          var rows = riwayatRows();
          var pages = Math.max(1, Math.ceil(rows.length / rw.per));
          if (rw.page > pages) rw.page = pages;
          var slice = rows.slice((rw.page - 1) * rw.per, rw.page * rw.per);
          var total = rows.reduce(function (a, b) {
            return a + (b.void ? 0 : b.total);
          }, 0);
          $("#riwayat-count").textContent =
            rows.length +
            " nota · " +
            rp(total) +
            (rw.date === isoOf(nowWita()) ? " · hari ini" : "");
          $("#riwayat-range").textContent = rows.length
            ? "Menampilkan " +
              ((rw.page - 1) * rw.per + 1) +
              "–" +
              ((rw.page - 1) * rw.per + slice.length) +
              " dari " +
              rows.length +
              " nota"
            : "Tidak ada nota pada filter ini";

          if (!slice.length) {
            var isToday = rw.date === isoOf(nowWita());
            $("#riwayat-body").innerHTML =
              '<tr><td colspan="9" style="height:auto;padding:0">' +
              '<div class="empty"><div class="empty__ico"><svg class="ico ico--lg"><use href="#i-receipt"></use></svg></div>' +
              '<div class="empty__title">Tidak ada nota yang cocok</div>' +
              '<div class="empty__text">' +
              (isToday
                ? "Coba longgarkan filter petugas, metode bayar, atau kata kunci."
                : "Mockup ini hanya memuat rincian nota hari ini. Hari lain muncul sebagai angka rekap di halaman Laporan.") +
              "</div>" +
              '<button class="btn btn--outline btn--sm" id="rw-clear">Tampilkan nota hari ini</button>' +
              "</div></td></tr>";
            var cb = $("#rw-clear");
            if (cb)
              cb.addEventListener("click", function () {
                rw = {
                  date: isoOf(nowWita()),
                  by: "",
                  pay: "",
                  status: "",
                  q: "",
                  page: 1,
                  per: 15,
                };
                syncFilterInputs();
                renderRiwayat();
              });
          } else {
            $("#riwayat-body").innerHTML = slice
              .map(function (tx) {
                var st = ticketState(tx);
                var lb = TICKET_LABEL[st];
                var active = tx.tickets.filter(function (t) {
                  return t.status === "active";
                }).length;
                var used = tx.tickets.filter(function (t) {
                  return t.status === "used";
                }).length;
                var canPrint = active > 0;
                var canVoid = !tx.void && used === 0;
                return (
                  "<tr>" +
                  '<td class="num nowrap">' +
                  tx.time +
                  "</td>" +
                  '<td class="mono" style="font-size:12px">' +
                  tx.nota +
                  "</td>" +
                  "<td>" +
                  esc(tx.cashier) +
                  "</td>" +
                  '<td><div class="tbl__who">' +
                  esc(tx.name) +
                  "</div>" +
                  '<div class="tbl__sub">' +
                  tx.visitors +
                  " pengunjung · " +
                  (tx.mode === "group" ? "Rombongan" : "Individual") +
                  "</div></td>" +
                  "<td><div>" +
                  esc(tx.region) +
                  '</div><div class="tbl__sub">' +
                  (tx.origin === "Domestik" ? "Domestik" : "Mancanegara") +
                  "</div></td>" +
                  "<td>" +
                  (tx.payment === "cash" ? "Tunai" : "Debit") +
                  (tx.edc
                    ? '<div class="tbl__sub mono">' + esc(tx.edc) + "</div>"
                    : "") +
                  "</td>" +
                  '<td class="r num">' +
                  rp(tx.total) +
                  (tx.discount
                    ? '<div class="tbl__sub">−' + rp(tx.discount) + "</div>"
                    : "") +
                  "</td>" +
                  '<td><span class="badge ' +
                  lb.cls +
                  '">' +
                  lb.txt +
                  "</span>" +
                  '<div class="tbl__sub">' +
                  used +
                  "/" +
                  tx.tickets.length +
                  " masuk</div></td>" +
                  '<td><div class="tbl__actions">' +
                  '<button class="iconbtn" ' +
                  (canPrint ? "" : "disabled ") +
                  'data-reprint="' +
                  tx.id +
                  '" title="' +
                  (canPrint
                    ? "Cetak ulang " + active + " tiket aktif"
                    : "Tidak ada tiket aktif untuk dicetak ulang") +
                  '"><svg class="ico ico--sm"><use href="#i-printer"></use></svg></button>' +
                  '<button class="iconbtn iconbtn--danger" ' +
                  (canVoid ? "" : "disabled ") +
                  'data-void="' +
                  tx.id +
                  '" title="' +
                  (tx.void
                    ? "Nota sudah di-void"
                    : used
                      ? "Tidak bisa di-void: sudah ada tiket yang masuk"
                      : "Void administratif") +
                  '"><svg class="ico ico--sm"><use href="#i-ban"></use></svg></button>' +
                  "</div></td></tr>"
                );
              })
              .join("");
          }

          var pager = $("#riwayat-pager");
          if (pages > 1) {
            var h =
              '<button class="pager__btn" ' +
              (rw.page === 1 ? "disabled" : "") +
              ' data-page="' +
              (rw.page - 1) +
              '">' +
              '<svg class="ico ico--sm"><use href="#i-chevron-left"></use></svg></button>';
            for (var p = 1; p <= pages; p++) {
              h +=
                '<button class="pager__btn ' +
                (p === rw.page ? "is-on" : "") +
                '" data-page="' +
                p +
                '">' +
                p +
                "</button>";
            }
            h +=
              '<button class="pager__btn" ' +
              (rw.page === pages ? "disabled" : "") +
              ' data-page="' +
              (rw.page + 1) +
              '">' +
              '<svg class="ico ico--sm"><use href="#i-chevron-right"></use></svg></button>';
            pager.innerHTML = h;
          } else {
            pager.innerHTML = "";
          }
          $("#riwayat-tools").innerHTML =
            '<span class="chip">' +
            (rw.date
              ? humanShort(new Date(rw.date + "T00:00:00"))
              : "Semua tanggal") +
            "</span>";
        }

        function syncFilterInputs() {
          $("#f-tanggal").value = rw.date;
          $("#f-petugas").value = rw.by;
          $("#f-bayar").value = rw.pay;
          $("#f-status").value = rw.status;
          $("#f-cari").value = rw.q;
        }

        function bindRiwayat() {
          $("#f-petugas").innerHTML =
            '<option value="">Semua petugas</option>' +
            ADMINS.map(function (u) {
              return (
                '<option value="' +
                esc(u.name) +
                '">' +
                esc(u.name) +
                "</option>"
              );
            }).join("");
          syncFilterInputs();
          $("#f-apply").addEventListener("click", function () {
            rw.date = $("#f-tanggal").value;
            rw.by = $("#f-petugas").value;
            rw.pay = $("#f-bayar").value;
            rw.status = $("#f-status").value;
            rw.q = $("#f-cari").value.trim();
            rw.page = 1;
            renderRiwayat();
            toast(
              "info",
              "Filter diterapkan",
              riwayatRows().length + " nota ditemukan.",
            );
          });
          $("#f-reset").addEventListener("click", function () {
            rw = {
              date: isoOf(nowWita()),
              by: "",
              pay: "",
              status: "",
              q: "",
              page: 1,
              per: 15,
            };
            syncFilterInputs();
            renderRiwayat();
          });
          $("#f-cari").addEventListener("keydown", function (e) {
            if (e.key === "Enter") $("#f-apply").click();
          });
          $("#riwayat-pager").addEventListener("click", function (e) {
            var b = e.target.closest("[data-page]");
            if (!b || b.disabled) return;
            rw.page = Number(b.getAttribute("data-page"));
            renderRiwayat();
          });
        }

        /* -------------------------------------------------------- reprint & void */
        function txById(id) {
          return TXS.filter(function (t) {
            return t.id === Number(id);
          })[0];
        }

        function doReprint(id) {
          var tx = txById(id);
          if (!tx) return;
          var active = tx.tickets.filter(function (t) {
            return t.status === "active";
          });
          if (!active.length) {
            toast(
              "err",
              "Tidak bisa dicetak ulang",
              "Tidak ada tiket aktif pada nota ini.",
            );
            return;
          }
          var html = active
            .map(function (t, i) {
              return receiptCoupon(tx, t.code, {
                reprint: true,
                reprintBy: "Admin Loket 1",
                noPayment: i > 0,
              });
            })
            .join("");
          openModal({
            wide: true,
            title: "Cetak ulang tiket",
            sub:
              tx.nota +
              " · " +
              active.length +
              " tiket aktif · dicatat di print log",
            body:
              '<div class="callout callout--warn"><svg class="ico"><use href="#i-alert"></use></svg>' +
              "<div><b>Cetak ulang tidak mengubah kode, status, atau tanggal berlaku.</b> " +
              "Yang tercatat hanya siapa dan kapan mencetak. Tiket yang sudah masuk atau di-void tidak bisa dicetak ulang.</div></div>" +
              '<div class="stack" style="margin-top:18px">' +
              active
                .map(function (t) {
                  return (
                    '<div class="receipt-wrap"><div class="receipt receipt--bare">' +
                    receiptCoupon(tx, t.code, {
                      reprint: true,
                      reprintBy: "Admin Loket 1",
                    }) +
                    "</div></div>"
                  );
                })
                .join("") +
              "</div>",
            footer:
              '<button class="btn btn--ghost" data-close>Batal</button>' +
              '<button class="btn btn--primary" id="m-reprint">' +
              '<svg class="ico"><use href="#i-printer"></use></svg> Cetak ' +
              active.length +
              " potongan</button>",
            onMount: function (r) {
              $("#m-reprint", r).addEventListener("click", function () {
                printCoupons(html);
                toast(
                  "ok",
                  "Cetak ulang dicatat",
                  tx.nota + " · 1 entri di print log.",
                );
              });
            },
          });
        }

        function doVoid(id) {
          var tx = txById(id);
          if (!tx) return;
          var used = tx.tickets.filter(function (t) {
            return t.status === "used";
          }).length;
          if (tx.void || used) {
            toast(
              "err",
              "Void tidak tersedia",
              tx.void
                ? "Nota ini sudah di-void."
                : "Sudah ada " +
                    used +
                    " tiket yang masuk; void tidak mungkin dilakukan.",
            );
            return;
          }
          openModal({
            title: "Void nota " + tx.nota + "?",
            sub:
              tx.name + " · " + tx.visitors + " pengunjung · " + rp(tx.total),
            body:
              '<div class="callout callout--err"><svg class="ico"><use href="#i-alert"></use></svg>' +
              "<div><b>Void bukan refund.</b> Uang dikembalikan mengikuti prosedur internal loket di luar sistem. " +
              "Setelah di-void, seluruh tiket pada nota ini berstatus dibatalkan dan scan di gate akan ditolak.</div></div>" +
              '<div class="field" style="margin-top:16px">' +
              '<label class="label" for="void-reason">Alasan void <span class="req">*</span></label>' +
              '<textarea class="textarea" id="void-reason" rows="3" ' +
              'placeholder="mis. salah jumlah tiket, tamu minta 1 tiket dewasa"></textarea>' +
              '<span class="hint">Alasan tersimpan bersama nama petugas dan waktu void.</span>' +
              "</div>" +
              '<div class="summary" style="margin-top:16px;border:1px solid var(--c-line);border-radius:8px">' +
              '<div class="summary__row"><span>Tiket pada nota ini</span><b>' +
              tx.tickets.length +
              " tiket</b></div>" +
              '<div class="summary__row"><span>Sudah masuk</span><b>0 tiket</b></div>' +
              '<div class="summary__row summary__row--total"><span>Nilai nota</span><b>' +
              rp(tx.total) +
              "</b></div>" +
              "</div>",
            footer:
              '<button class="btn btn--ghost" data-close>Batal</button>' +
              '<button class="btn btn--danger" id="m-void" disabled>Void nota &amp; batalkan tiket</button>',
            onMount: function (r) {
              var ta = $("#void-reason", r),
                ok = $("#m-void", r);
              ta.addEventListener("input", function () {
                ok.disabled = ta.value.trim().length < 5;
              });
              ok.addEventListener("click", function () {
                tx.void = true;
                tx.voidReason = ta.value.trim();
                tx.voidAt =
                  pad(nowWita().getHours()) + ":" + pad(nowWita().getMinutes());
                tx.tickets.forEach(function (t) {
                  t.status = "cancelled";
                });
                modalClose();
                TODAYS = todayStats();
                LIVE = TXS.filter(function (t) {
                  return !t.void;
                });
                renderBoard();
                renderChart();
                renderRiwayat();
                renderTiketTypes();
                toast(
                  "ok",
                  "Nota di-void",
                  tx.nota + " · " + tx.tickets.length + " tiket dibatalkan.",
                );
              });
            },
          });
        }

        /* ================================================== JENIS TIKET / MASTER */
        function pairOf(ty) {
          var base = ty.name.replace(" Rombongan", "").trim();
          return TYPES.filter(function (t) {
            return (
              t.id !== ty.id && t.name.replace(" Rombongan", "").trim() === base
            );
          })[0];
        }
        function priceWarnings() {
          var out = [];
          TYPES.forEach(function (ty) {
            var p = pairOf(ty);
            if (p && p.price !== ty.price)
              out.push(
                "Harga <b>" +
                  esc(ty.name) +
                  "</b> (" +
                  rp(ty.price) +
                  ") berbeda dari <b>" +
                  esc(p.name) +
                  "</b> (" +
                  rp(p.price) +
                  ").",
              );
          });
          return out;
        }
        function renderTiketTypes() {
          $("#tt-body").innerHTML = TYPES.map(function (ty) {
            var p = pairOf(ty);
            var mismatch = p && p.price !== ty.price;
            return (
              "<tr>" +
              '<td><div class="tbl__who">' +
              esc(ty.name) +
              "</div>" +
              '<div class="tbl__sub">Dibuat ' +
              ty.created +
              "</div></td>" +
              '<td><span class="badge ' +
              (ty.category === "group" ? "badge--info" : "badge--neutral") +
              '">' +
              (ty.category === "group" ? "Rombongan" : "Individual") +
              "</span></td>" +
              '<td class="mono" style="font-size:12px">' +
              (ty.category === "group" ? "GRP" : "IND") +
              "-" +
              pad(ty.id) +
              "</td>" +
              '<td class="r num">' +
              rp(ty.price) +
              (mismatch
                ? '<div class="tbl__sub" style="color:var(--c-warning)">≠ pasangan</div>'
                : "") +
              "</td>" +
              '<td class="num">' +
              (ty.discount ? ty.discount + "%" : "–") +
              "</td>" +
              '<td><label class="switch"><input type="checkbox" ' +
              (ty.active ? "checked" : "") +
              ' data-toggle-type="' +
              ty.id +
              '"><span class="switch__track"></span>' +
              "<span>" +
              (ty.active ? "Aktif" : "Nonaktif") +
              "</span></label></td>" +
              '<td><div class="tbl__actions">' +
              '<button class="iconbtn" data-edit-type="' +
              ty.id +
              '" title="Ubah harga & diskon">' +
              '<svg class="ico ico--sm"><use href="#i-pencil"></use></svg></button>' +
              "</div></td></tr>"
            );
          }).join("");
          $("#tt-count").textContent =
            TYPES.length +
            " jenis · " +
            TYPES.filter(function (t) {
              return t.active;
            }).length +
            " aktif";

          var w = priceWarnings();
          $("#tt-warning").innerHTML = w.length
            ? '<div class="callout callout--warn"><svg class="ico"><use href="#i-alert"></use></svg><div>' +
              "<b>Harga pasangan individual dan rombongan berbeda.</b> " +
              w.join(" ") +
              " Selama berbeda, pembeli rombongan bisa membayar lebih mahal atau lebih murah daripada individual " +
              "untuk jenis yang sama. Periksa apakah ini disengaja.</div></div>"
            : "";
        }
        function typeModal(id) {
          var ty = id
            ? TYPES.filter(function (t) {
                return t.id === id;
              })[0]
            : null;
          var isNew = !ty;
          var draft = ty
            ? {
                name: ty.name,
                category: ty.category,
                price: ty.price,
                discount: ty.discount,
                active: ty.active,
              }
            : {
                name: "",
                category: "individual",
                price: 0,
                discount: 0,
                active: true,
              };
          openModal({
            title: isNew ? "Tambah jenis tiket" : "Ubah " + esc(ty.name),
            sub: isNew
              ? "Harga dan diskon berlaku untuk transaksi berikutnya"
              : "Perubahan tidak mengubah nota lama",
            body:
              '<div class="form-grid">' +
              '<div class="field col-2"><label class="label" for="tt-name">Nama jenis <span class="req">*</span></label>' +
              '<input class="input" id="tt-name" value="' +
              esc(draft.name) +
              '" placeholder="mis. Adult Rombongan"></div>' +
              '<div class="field"><label class="label" for="tt-cat">Kategori <span class="req">*</span></label>' +
              '<select class="select" id="tt-cat">' +
              '<option value="individual"' +
              (draft.category === "individual" ? " selected" : "") +
              ">Individual</option>" +
              '<option value="group"' +
              (draft.category === "group" ? " selected" : "") +
              ">Rombongan</option></select>" +
              '<span class="hint">POS hanya menampilkan jenis sesuai mode penjualan.</span></div>' +
              '<div class="field"><label class="label" for="tt-price">Harga satuan (Rp) <span class="req">*</span></label>' +
              '<input class="input num" id="tt-price" inputmode="numeric" value="' +
              draft.price.toLocaleString("id-ID") +
              '"></div>' +
              '<div class="field"><label class="label" for="tt-disc">Diskon (%)</label>' +
              '<input class="input num" id="tt-disc" inputmode="numeric" value="' +
              draft.discount +
              '">' +
              '<span class="hint">0&ndash;100. Ini satu-satunya tempat diskon diatur.</span></div>' +
              '<div class="field"><label class="label">Status</label>' +
              '<label class="switch"><input type="checkbox" id="tt-active" ' +
              (draft.active ? "checked" : "") +
              ">" +
              '<span class="switch__track"></span><span id="tt-active-txt">' +
              (draft.active ? "Aktif dijual" : "Nonaktif") +
              "</span></label></div>" +
              "</div>" +
              '<div id="tt-dyn" style="margin-top:16px"></div>',
            footer:
              '<button class="btn btn--ghost" data-close>Batal</button>' +
              '<button class="btn btn--primary" id="tt-save">Simpan jenis tiket</button>',
            onMount: function (r) {
              var name = $("#tt-name", r),
                cat = $("#tt-cat", r),
                price = $("#tt-price", r),
                disc = $("#tt-disc", r),
                act = $("#tt-active", r),
                dyn = $("#tt-dyn", r);
              function draftNow() {
                return {
                  name: name.value.trim(),
                  category: cat.value,
                  price: Number(String(price.value).replace(/\D/g, "")) || 0,
                  discount: Math.max(
                    0,
                    Math.min(
                      100,
                      Number(String(disc.value).replace(/\D/g, "")) || 0,
                    ),
                  ),
                  active: act.checked,
                };
              }
              function refresh() {
                var d = draftNow();
                act.nextElementSibling;
                $("#tt-active-txt", r).textContent = act.checked
                  ? "Aktif dijual"
                  : "Nonaktif";
                var msgs = [];
                var base = d.name.replace(" Rombongan", "").trim();
                var pair = TYPES.filter(function (t) {
                  return (
                    t.id !== (ty ? ty.id : -1) &&
                    t.name.replace(" Rombongan", "").trim().toLowerCase() ===
                      base.toLowerCase()
                  );
                })[0];
                if (pair && pair.price !== d.price)
                  msgs.push([
                    "warn",
                    "Harga berbeda dari <b>" +
                      esc(pair.name) +
                      "</b> yang " +
                      rp(pair.price) +
                      ". POS akan memakai angka masing-masing jenis, jadi pastikan ini disengaja.",
                  ]);
                if (
                  TYPES.some(function (t) {
                    return (
                      t.id !== (ty ? ty.id : -1) &&
                      t.name.toLowerCase() === d.name.toLowerCase()
                    );
                  })
                )
                  msgs.push(["err", "Nama jenis sudah dipakai."]);
                if (!d.name) msgs.push(["warn", "Nama jenis wajib diisi."]);
                if (d.price <= 0)
                  msgs.push(["warn", "Harga harus lebih dari 0."]);
                dyn.innerHTML = msgs
                  .map(function (m) {
                    return (
                      '<div class="callout callout--' +
                      (m[0] === "err" ? "err" : "warn") +
                      '" style="margin-top:8px">' +
                      '<svg class="ico ico--sm"><use href="#i-alert"></use></svg><div>' +
                      m[1] +
                      "</div></div>"
                    );
                  })
                  .join("");
                $("#tt-save", r).disabled = msgs.some(function (m) {
                  return m[0] === "err";
                });
              }
              [name, cat, price, disc].forEach(function (f) {
                f.addEventListener("input", refresh);
                f.addEventListener("change", refresh);
              });
              act.addEventListener("change", refresh);
              price.addEventListener("blur", function () {
                price.value = draftNow().price.toLocaleString("id-ID");
              });
              price.addEventListener("focus", function () {
                price.value = String(
                  Number(String(price.value).replace(/\D/g, "")) || "",
                );
              });
              refresh();
              $("#tt-save", r).addEventListener("click", function () {
                var d = draftNow();
                if (isNew)
                  TYPES.push({
                    id:
                      Math.max.apply(
                        null,
                        TYPES.map(function (t) {
                          return t.id;
                        }),
                      ) + 1,
                    name: d.name,
                    category: d.category,
                    price: d.price,
                    discount: d.discount,
                    active: d.active,
                    created: "Hari ini",
                  });
                else {
                  ty.name = d.name;
                  ty.category = d.category;
                  ty.price = d.price;
                  ty.discount = d.discount;
                  ty.active = d.active;
                }
                modalClose();
                renderTiketTypes();
                renderPosTypes();
                renderPosStep();
                toast(
                  "ok",
                  isNew ? "Jenis tiket ditambahkan" : "Jenis tiket diperbarui",
                  d.name +
                    " · " +
                    rp(d.price) +
                    (d.discount ? " · diskon " + d.discount + "%" : ""),
                );
              });
            },
          });
        }

        /* ============================================================== PENGGUNA */
        var puTab = "admin";
        function renderPengguna() {
          var list = puTab === "admin" ? ADMINS : GATE_USERS;
          $("#pu-tabs").innerHTML = [
            ["admin", "Akun Admin", ADMINS.length],
            ["gate", "Akun Gate", GATE_USERS.length],
          ]
            .map(function (t) {
              return (
                '<button class="tab ' +
                (puTab === t[0] ? "is-on" : "") +
                '" role="tab" data-pu="' +
                t[0] +
                '" ' +
                'aria-selected="' +
                (puTab === t[0]) +
                '">' +
                t[1] +
                " (" +
                t[2] +
                ")</button>"
              );
            })
            .join("");
          $("#pu-body").innerHTML = list
            .map(function (u) {
              return (
                "<tr>" +
                '<td><div class="tbl__who">' +
                esc(u.name) +
                "</div>" +
                '<div class="tbl__sub">' +
                (u.active ? "Bisa login" : "Login diblokir") +
                "</div></td>" +
                '<td class="mono" style="font-size:12px">' +
                esc(u.username) +
                "</td>" +
                "<td>" +
                (u.role === "Admin"
                  ? "Admin loket (web)"
                  : "Petugas gate (mobile)") +
                "</td>" +
                '<td><label class="switch"><input type="checkbox" ' +
                (u.active ? "checked" : "") +
                ' data-toggle-user="' +
                esc(u.username) +
                '"><span class="switch__track"></span>' +
                "<span>" +
                (u.active ? "Aktif" : "Nonaktif") +
                "</span></label></td>" +
                '<td class="num">' +
                esc(u.last) +
                "</td>" +
                '<td><div class="tbl__actions">' +
                '<button class="iconbtn" data-reset="' +
                esc(u.username) +
                '" title="Reset password">' +
                '<svg class="ico ico--sm"><use href="#i-lock"></use></svg></button>' +
                '<button class="iconbtn" data-edit-user="' +
                esc(u.username) +
                '" title="Ubah data akun">' +
                '<svg class="ico ico--sm"><use href="#i-pencil"></use></svg></button>' +
                "</div></td></tr>"
              );
            })
            .join("");
        }
        function userByUsername(u) {
          return ADMINS.concat(GATE_USERS).filter(function (x) {
            return x.username === u;
          })[0];
        }
        function resetPasswordModal(username) {
          var u = userByUsername(username);
          openModal({
            title: "Reset password " + esc(u.name),
            sub:
              "Username " +
              esc(u.username) +
              " · " +
              (u.role === "Admin" ? "Admin loket" : "Petugas gate"),
            body:
              '<div class="callout callout--warn"><svg class="ico"><use href="#i-alert"></use></svg>' +
              "<div>Password lama langsung tidak berlaku. Sampaikan password baru ke pemilik akun secara langsung, " +
              "bukan lewat grup atau kertas yang tertinggal di loket.</div></div>" +
              '<div class="form-grid" style="margin-top:16px">' +
              '<div class="field"><label class="label" for="rp1">Password baru <span class="req">*</span></label>' +
              '<input class="input" id="rp1" type="password" placeholder="minimal 8 karakter"></div>' +
              '<div class="field"><label class="label" for="rp2">Ulangi password <span class="req">*</span></label>' +
              '<input class="input" id="rp2" type="password"></div>' +
              '</div><div id="rp-msg" style="margin-top:12px"></div>',
            footer:
              '<button class="btn btn--ghost" data-close>Batal</button>' +
              '<button class="btn btn--primary" id="rp-ok" disabled>Simpan password</button>',
            onMount: function (r) {
              var a = $("#rp1", r),
                b = $("#rp2", r),
                ok = $("#rp-ok", r),
                msg = $("#rp-msg", r);
              function chk() {
                var v = a.value,
                  okLen = v.length >= 8,
                  same = v && v === b.value;
                $("#rp-ok", r).disabled = !(okLen && same);
                msg.innerHTML =
                  v && !okLen
                    ? '<p class="msg msg--err"><svg class="ico ico--sm"><use href="#i-alert"></use></svg><span>Minimal 8 karakter.</span></p>'
                    : v && !same
                      ? '<p class="msg msg--warn"><svg class="ico ico--sm"><use href="#i-info"></use></svg><span>Ulangan password belum sama.</span></p>'
                      : "";
              }
              [a, b].forEach(function (f) {
                f.addEventListener("input", chk);
              });
              ok.addEventListener("click", function () {
                modalClose();
                toast(
                  "ok",
                  "Password direset",
                  u.name + " · perubahan tercatat di audit.",
                );
              });
            },
          });
        }

        /* ============================================================ CHECKPOINT */
        function renderCheckpoint() {
          var scans = {};
          TXS.forEach(function (tx) {
            tx.tickets.forEach(function (t) {
              if (t.status === "used")
                scans[t.usedGate || "Gerbang Utama"] =
                  (scans[t.usedGate || "Gerbang Utama"] || 0) + 1;
            });
          });
          var rej = {};
          REJECTED.forEach(function (r) {
            rej[r.cp] = (rej[r.cp] || 0) + 1;
          });
          $("#cp-body").innerHTML = CHECKPOINTS.map(function (c) {
            return (
              "<tr>" +
              '<td><div class="tbl__who">' +
              esc(c.name) +
              "</div>" +
              '<div class="tbl__sub">Terverifikasi di aplikasi gate</div></td>' +
              '<td><span class="badge ' +
              (c.active ? "badge--ok" : "badge--neutral") +
              '">' +
              (c.active ? "Aktif" : "Nonaktif") +
              "</span></td>" +
              '<td class="r num">' +
              (scans[c.name] || 0) +
              "</td>" +
              '<td class="r num">' +
              (rej[c.name] || 0) +
              "</td>" +
              '<td class="num">' +
              c.created +
              "</td>" +
              '<td><div class="tbl__actions">' +
              '<button class="iconbtn" data-edit-cp="' +
              c.id +
              '" title="Ubah checkpoint">' +
              '<svg class="ico ico--sm"><use href="#i-pencil"></use></svg></button>' +
              "</div></td></tr>"
            );
          }).join("");
          $("#cp-count").textContent =
            CHECKPOINTS.length +
            " checkpoint · " +
            CHECKPOINTS.filter(function (c) {
              return c.active;
            }).length +
            " aktif";
        }
        function checkpointModal(id) {
          var c = id
            ? CHECKPOINTS.filter(function (x) {
                return x.id === id;
              })[0]
            : null;
          var draft = c || { name: "", active: true };
          openModal({
            title: c ? "Ubah checkpoint" : "Tambah checkpoint",
            sub: c
              ? "Scan hari ini: " + 0
              : "Nama tampil di pemilih gate setelah login",
            body:
              '<div class="form-grid">' +
              '<div class="field col-2"><label class="label" for="cp-name">Nama checkpoint <span class="req">*</span></label>' +
              '<input class="input" id="cp-name" value="' +
              esc(draft.name) +
              '" placeholder="mis. Gerbang Timur"></div>' +
              '<div class="field"><label class="label">Status</label>' +
              '<label class="switch"><input type="checkbox" id="cp-active" ' +
              (draft.active ? "checked" : "") +
              ">" +
              '<span class="switch__track"></span><span id="cp-active-txt">' +
              (draft.active ? "Aktif" : "Nonaktif") +
              "</span></label>" +
              '<span class="hint">Checkpoint nonaktif tidak muncul di aplikasi gate.</span></div>' +
              "</div>",
            footer:
              '<button class="btn btn--ghost" data-close>Batal</button>' +
              '<button class="btn btn--primary" id="cp-save">Simpan</button>',
            onMount: function (r) {
              var n = $("#cp-name", r),
                a = $("#cp-active", r),
                ok = $("#cp-save", r);
              n.addEventListener("input", function () {
                ok.disabled = n.value.trim().length < 3;
              });
              a.addEventListener("change", function () {
                $("#cp-active-txt", r).textContent = a.checked
                  ? "Aktif"
                  : "Nonaktif";
              });
              ok.disabled = n.value.trim().length < 3;
              ok.addEventListener("click", function () {
                if (c) {
                  c.name = n.value.trim();
                  c.active = a.checked;
                } else
                  CHECKPOINTS.push({
                    id:
                      Math.max.apply(
                        null,
                        CHECKPOINTS.map(function (x) {
                          return x.id;
                        }),
                      ) + 1,
                    name: n.value.trim(),
                    active: a.checked,
                    created: "Hari ini",
                  });
                modalClose();
                renderCheckpoint();
                toast(
                  "ok",
                  c ? "Checkpoint diperbarui" : "Checkpoint ditambahkan",
                  n.value.trim(),
                );
              });
            },
          });
        }

        /* ============================================================ PENGATURAN */
        var stDraft = null;
        function loadSettingsForm() {
          stDraft = JSON.parse(JSON.stringify(SETTINGS));
          $("#s-business-name").value = SETTINGS.business_name;
          $("#s-business-address").value = SETTINGS.business_address;
          $("#s-receipt-footer").value = SETTINGS.receipt_footer;
          $("#s-min-group").value = SETTINGS.min_group_size;
          $("#s-max-individual").value = SETTINGS.max_individual_per_tx;
          $("#s-max-group").value = SETTINGS.max_group_members;
          $("#s-retention").value = SETTINGS.retention_months;
          $("#s-login-attempts").value = SETTINGS.login_max_attempts;
          $("#s-lock-minutes").value = SETTINGS.login_lock_minutes;
          $("#s-token-hours").value = SETTINGS.api_token_hours;
          $("#st-stamp").innerHTML =
            '<svg class="ico ico--sm"><use href="#i-clock"></use></svg> ' +
            "Diubah " +
            esc(SETTINGS_STAMP.by) +
            " · " +
            SETTINGS_STAMP.at;
          settingsWarnings();
          renderSettingsReceipt();
        }
        function settingsWarnings() {
          var g = Number($("#s-min-group").value) || 0;
          var i = Number($("#s-max-individual").value) || 0;
          var m = Number($("#s-max-group").value) || 0;
          var out = [];
          if (g < 2) out.push(["err", "Minimum rombongan minimal 2 orang."]);
          if (m < g)
            out.push([
              "err",
              "Maksimum anggota rombongan tidak boleh lebih kecil dari minimum rombongan.",
            ]);
          if (g !== i + 1)
            out.push([
              "warn",
              "Minimum rombongan (" +
                g +
                ") tidak sama dengan maksimum individual + 1 (" +
                (i + 1) +
                "). Selama berbeda, ada rentang jumlah pengunjung yang tidak bisa dijual sebagai kategori apa pun.",
            ]);
          if (i < 1) out.push(["err", "Maksimum tiket individual minimal 1."]);
          $("#st-warning").innerHTML = out
            .map(function (o) {
              return (
                '<div class="callout callout--' +
                (o[0] === "err" ? "err" : "warn") +
                '">' +
                '<svg class="ico"><use href="#i-alert"></use></svg><div>' +
                o[1] +
                "</div></div>"
              );
            })
            .join("");
          return !out.some(function (o) {
            return o[0] === "err";
          });
        }
        function renderSettingsReceipt() {
          var d = nowWita();
          $("#st-receipt").innerHTML =
            '<div class="rc-head"><b>' +
            esc($("#s-business-name").value || "–").toUpperCase() +
            "</b><br>" +
            esc($("#s-business-address").value || "–") +
            "</div>" +
            '<div class="rc-rule"></div>' +
            '<div class="rc-note">Header struk memakai dua baris di atas</div>' +
            '<div class="rc-rule"></div>' +
            '<div class="rc-valid">BERLAKU HANYA HARI INI:<br><b>' +
            humanLong(d).toUpperCase() +
            "</b><br>SAMPAI PUKUL 24.00 WITA</div>" +
            '<div class="rc-foot rc-note">' +
            esc($("#s-receipt-footer").value || "–") +
            "</div>";
        }
        function bindSettings() {
          [
            "#s-business-name",
            "#s-business-address",
            "#s-receipt-footer",
          ].forEach(function (s) {
            $(s).addEventListener("input", renderSettingsReceipt);
          });
          [
            "#s-min-group",
            "#s-max-individual",
            "#s-max-group",
            "#s-retention",
            "#s-login-attempts",
            "#s-lock-minutes",
            "#s-token-hours",
          ].forEach(function (s) {
            $(s).addEventListener("input", function () {
              stDraft = null;
              var ok = settingsWarnings();
              $("#st-msg").className = "msg";
              $("#st-msg").innerHTML =
                '<span class="muted">' +
                (ok
                  ? "Ada perubahan belum disimpan."
                  : "Perbaiki nilai yang ditandai.") +
                "</span>";
              $("#st-save").disabled = !ok;
            });
          });
          $("#st-save").addEventListener("click", function () {
            if (!settingsWarnings()) return;
            SETTINGS.business_name = $("#s-business-name").value.trim();
            SETTINGS.business_address = $("#s-business-address").value.trim();
            SETTINGS.receipt_footer = $("#s-receipt-footer").value.trim();
            SETTINGS.min_group_size = Number($("#s-min-group").value) || 0;
            SETTINGS.max_individual_per_tx =
              Number($("#s-max-individual").value) || 0;
            SETTINGS.max_group_members = Number($("#s-max-group").value) || 0;
            SETTINGS.retention_months = Number($("#s-retention").value) || 0;
            SETTINGS.login_max_attempts =
              Number($("#s-login-attempts").value) || 0;
            SETTINGS.login_lock_minutes =
              Number($("#s-lock-minutes").value) || 0;
            SETTINGS.api_token_hours = Number($("#s-token-hours").value) || 0;
            SETTINGS_STAMP = { by: "Admin Loket 1", at: humanStamp(nowWita()) };
            $("#st-stamp").innerHTML =
              '<svg class="ico ico--sm"><use href="#i-clock"></use></svg> Diubah ' +
              esc(SETTINGS_STAMP.by) +
              " · " +
              SETTINGS_STAMP.at;
            $("#pos-mode-msg").innerHTML =
              '<svg class="ico ico--sm"><use href="#i-info"></use></svg><span>' +
              "Pembelian di bawah " +
              SETTINGS.min_group_size +
              " orang wajib dibeli sebagai tiket individual.</span>";
            $("#login-attempts").textContent = SETTINGS.login_max_attempts;
            $("#login-lock").textContent = SETTINGS.login_lock_minutes;
            $("#st-msg").className = "msg msg--ok";
            $("#st-msg").innerHTML =
              '<svg class="ico ico--sm"><use href="#i-check"></use></svg><span>Tersimpan ' +
              SETTINGS_STAMP.at +
              ".</span>";
            renderReceipt();
            toast(
              "ok",
              "Pengaturan disimpan",
              "Berlaku untuk transaksi berikutnya.",
            );
          });
          $("#st-reset").addEventListener("click", function () {
            loadSettingsForm();
            $("#st-save").disabled = false;
            $("#st-msg").className = "msg";
            $("#st-msg").innerHTML =
              '<span class="muted">Perubahan dibatalkan.</span>';
            toast(
              "info",
              "Perubahan dibatalkan",
              "Nilai kembali ke pengaturan tersimpan.",
            );
          });
        }

        /* =============================================================== LAPORAN */
        var lapTab = "penjualan";
        var LAP_TABS = [
          ["penjualan", "Penjualan"],
          ["diskon", "Diskon individual vs rombongan"],
          ["demografi", "Demografi"],
          ["audit", "Audit scan"],
          ["terjual", "Terjual vs terpakai"],
        ];
        var lapRange = { from: isoOf(dayShift(6)), to: isoOf(nowWita()) };

        function ranges() {
          var days = [];
          HISTORY.forEach(function (h) {
            var d = dayShift(h.off);
            days.push({
              iso: isoOf(d),
              label: humanShort(d),
              notes: h.notes,
              cash: h.cash,
              debit: h.debit,
              total: h.cash + h.debit,
              visitors: h.visitors,
              tickets: h.tickets,
              checkedIn: h.checkedIn,
              discInd: h.discInd,
              discGroup: h.discGroup,
              domestic: h.domestic,
              foreign: h.foreign,
              today: false,
            });
          });
          var d0 = nowWita();
          days.push({
            iso: isoOf(d0),
            label: humanShort(d0) + " (hari ini)",
            notes: TODAYS.notes,
            cash: TODAYS.cash,
            debit: TODAYS.debit,
            total: TODAYS.sales,
            visitors: TODAYS.visitors,
            tickets: TODAYS.tickets,
            checkedIn: TODAYS.checkedVisitors,
            discInd: TODAYS.discInd,
            discGroup: TODAYS.discGroup,
            domestic: TODAYS.domestic,
            foreign: TODAYS.foreign,
            today: true,
          });
          return days.filter(function (d) {
            return d.iso >= lapRange.from && d.iso <= lapRange.to;
          });
        }

        function tbl(head, rows, foot) {
          return (
            '<div class="tbl-scroll"><table class="tbl"><thead><tr>' +
            head
              .map(function (h, i) {
                return (
                  "<th" + (h[1] ? ' class="r"' : "") + ">" + h[0] + "</th>"
                );
              })
              .join("") +
            "</tr></thead><tbody>" +
            rows
              .map(function (r) {
                return (
                  "<tr>" +
                  r
                    .map(function (c, i) {
                      return (
                        "<td" +
                        (head[i][1] ? ' class="r num"' : "") +
                        ">" +
                        c +
                        "</td>"
                      );
                    })
                    .join("") +
                  "</tr>"
                );
              })
              .join("") +
            "</tbody>" +
            (foot
              ? "<tfoot><tr>" +
                foot
                  .map(function (c, i) {
                    return (
                      "<td" +
                      (head[i][1] ? ' class="r num"' : "") +
                      ' style="border-top:1px solid var(--c-line);font-weight:600;height:44px">' +
                      c +
                      "</td>"
                    );
                  })
                  .join("") +
                "</tr></tfoot>"
              : "") +
            "</table></div>"
          );
        }

        function renderLaporan() {
          $("#lap-tabs").innerHTML = LAP_TABS.map(function (t) {
            return (
              '<button class="tab ' +
              (lapTab === t[0] ? "is-on" : "") +
              '" role="tab" data-lap="' +
              t[0] +
              '" ' +
              'aria-selected="' +
              (lapTab === t[0]) +
              '">' +
              t[1] +
              "</button>"
            );
          }).join("");
          var days = ranges();
          var body = $("#lap-body");

          if (!days.length) {
            body.innerHTML =
              '<div class="empty"><div class="empty__ico"><svg class="ico ico--lg"><use href="#i-bars"></use></svg></div>' +
              '<div class="empty__title">Rentang tanggal di luar data mockup</div>' +
              '<div class="empty__text">Mockup ini memuat rekap tujuh hari terakhir. Pilih rentang di dalam ' +
              humanShort(dayShift(6)) +
              " sampai " +
              humanShort(nowWita()) +
              ".</div></div>";
            return;
          }

          var sum = days.reduce(function (a, d) {
            [
              "notes",
              "cash",
              "debit",
              "total",
              "visitors",
              "tickets",
              "checkedIn",
              "discInd",
              "discGroup",
              "domestic",
              "foreign",
            ].forEach(function (k) {
              a[k] = (a[k] || 0) + d[k];
            });
            return a;
          }, {});

          if (lapTab === "penjualan") {
            var perStaff = ADMINS.concat(GATE_USERS.slice(0, 0))
              .map(function () {
                return null;
              })
              .filter(Boolean);
            var staffRows = ["Admin Loket 1", "Admin Loket 2"].map(
              function (name) {
                var list = TXS.filter(function (t) {
                  return t.cashier === name && !t.void;
                });
                var tot = list.reduce(function (a, b) {
                  return a + b.total;
                }, 0);
                var vis = list.reduce(function (a, b) {
                  return a + b.visitors;
                }, 0);
                return [
                  esc(name),
                  list.length + " nota",
                  vis + " orang",
                  rp(tot),
                ];
              },
            );
            body.innerHTML =
              '<div class="bars" style="margin-bottom:20px">' +
              barRow("Tunai", sum.cash, sum.total, "ink") +
              barRow("Debit (EDC)", sum.debit, sum.total, "") +
              "</div>" +
              tbl(
                [
                  ["Tanggal"],
                  ["Nota", 1],
                  ["Tiket", 1],
                  ["Pengunjung", 1],
                  ["Tunai", 1],
                  ["Debit", 1],
                  ["Total dibayar", 1],
                ],
                days.map(function (d) {
                  return [
                    '<span class="' +
                      (d.today ? "tbl__who" : "") +
                      '">' +
                      esc(d.label) +
                      "</span>",
                    d.notes,
                    d.tickets,
                    d.visitors,
                    rp(d.cash),
                    rp(d.debit),
                    rp(d.total),
                  ];
                }),
                [
                  "Total " + days.length + " hari",
                  sum.notes,
                  sum.tickets,
                  sum.visitors,
                  rp(sum.cash),
                  rp(sum.debit),
                  rp(sum.total),
                ],
              ) +
              '<div style="margin-top:22px"></div>' +
              '<div class="panel__sub" style="margin-bottom:8px">Penjualan hari ini per petugas loket</div>' +
              tbl(
                [["Petugas"], ["Nota", 1], ["Pengunjung", 1], ["Total", 1]],
                staffRows,
                null,
              );
          }

          if (lapTab === "diskon") {
            body.innerHTML =
              '<div class="callout callout--info" style="margin-bottom:18px">' +
              '<svg class="ico"><use href="#i-info"></use></svg><div>Diskon individual adalah promo/event yang dipasang manual ' +
              'pada jenis individual. Diskon rombongan berasal dari <span class="mono">discount_percent</span> jenis kategori group.</div></div>' +
              '<div class="bars" style="margin-bottom:20px">' +
              barRow(
                "Diskon rombongan",
                sum.discGroup,
                sum.discInd + sum.discGroup,
                "ink",
              ) +
              barRow(
                "Diskon individual",
                sum.discInd,
                sum.discInd + sum.discGroup,
                "gold",
              ) +
              "</div>" +
              tbl(
                [
                  ["Tanggal"],
                  ["Diskon individual", 1],
                  ["Diskon rombongan", 1],
                  ["Total diskon", 1],
                  ["% dari bruto", 1],
                ],
                days.map(function (d) {
                  var gross = d.total + d.discInd + d.discGroup;
                  var pct = gross
                    ? (((d.discInd + d.discGroup) / gross) * 100)
                        .toFixed(1)
                        .replace(".", ",") + "%"
                    : "–";
                  return [
                    esc(d.label),
                    rp(d.discInd),
                    rp(d.discGroup),
                    rp(d.discInd + d.discGroup),
                    pct,
                  ];
                }),
                [
                  "Total",
                  rp(sum.discInd),
                  rp(sum.discGroup),
                  rp(sum.discInd + sum.discGroup),
                  "–",
                ],
              );
          }

          if (lapTab === "demografi") {
            var cities = Object.keys(TODAYS.cities)
              .map(function (k) {
                return [k, TODAYS.cities[k]];
              })
              .sort(function (a, b) {
                return b[1] - a[1];
              });
            var countries = Object.keys(TODAYS.countries)
              .map(function (k) {
                return [k, TODAYS.countries[k]];
              })
              .sort(function (a, b) {
                return b[1] - a[1];
              });
            var totV = TODAYS.domestic + TODAYS.foreign;
            body.innerHTML =
              '<div class="bars" style="margin-bottom:20px">' +
              barRow("Domestik", TODAYS.domestic, totV, "ink") +
              barRow("Mancanegara", TODAYS.foreign, totV, "") +
              "</div>" +
              '<div class="grid-board" style="grid-template-columns:1fr 1fr">' +
              '<div><div class="panel__sub" style="margin-bottom:8px">Kota/kabupaten asal (hari ini)</div>' +
              tbl(
                [["Kota"], ["Pengunjung", 1], ["%", 1]],
                cities.map(function (c) {
                  return [
                    esc(c[0]),
                    c[1],
                    ((c[1] / totV) * 100).toFixed(1).replace(".", ",") + "%",
                  ];
                }),
                null,
              ) +
              "</div>" +
              '<div><div class="panel__sub" style="margin-bottom:8px">Negara asal (hari ini)</div>' +
              tbl(
                [["Negara"], ["Pengunjung", 1], ["%", 1]],
                countries.map(function (c) {
                  return [
                    esc(c[0]),
                    c[1],
                    ((c[1] / totV) * 100).toFixed(1).replace(".", ",") + "%",
                  ];
                }),
                null,
              ) +
              "</div>" +
              "</div>" +
              '<div class="callout callout--info" style="margin-top:18px">' +
              '<svg class="ico ico--info"><use href="#i-info"></use></svg><div>Kolom asal tetap disimpan setelah anonimisasi ' +
              "data pribadi, sehingga rekap demografi tidak hilang.</div></div>";
          }

          if (lapTab === "audit") {
            var okScans = [];
            TXS.forEach(function (tx) {
              if (tx.void) return;
              tx.tickets.forEach(function (t) {
                if (t.status === "used")
                  okScans.push({
                    t: t.usedAt,
                    code: t.code,
                    res: "VALID",
                    cp: t.usedGate,
                    by: t.usedBy || "Gate 1 · Made",
                    v: t.visitors,
                  });
              });
            });
            REJECTED.forEach(function (r) {
              okScans.push({
                t: r.t,
                code: r.code,
                res: r.res,
                cp: r.cp,
                by: r.by,
                v: 0,
                note: r.note,
              });
            });
            okScans.sort(function (a, b) {
              return a.t < b.t ? -1 : 1;
            });
            var rejected = okScans.filter(function (s) {
              return s.res !== "VALID";
            });
            body.innerHTML =
              '<div class="callout callout--info" style="margin-bottom:18px">' +
              '<svg class="ico"><use href="#i-info"></use></svg><div>Setiap scan dicatat, termasuk yang ditolak. ' +
              "Mode Cek tidak masuk daftar ini karena tidak mengubah status tiket.</div></div>" +
              '<div class="legend" style="margin-bottom:16px">' +
              "<span>Diterima <b>" +
              (okScans.length - rejected.length) +
              "</b></span>" +
              "<span>Ditolak <b>" +
              rejected.length +
              "</b></span>" +
              "<span>Pengunjung masuk <b>" +
              TODAYS.checkedVisitors +
              " orang</b></span>" +
              "</div>" +
              tbl(
                [
                  ["Jam"],
                  ["Kode tiket"],
                  ["Hasil"],
                  ["Checkpoint"],
                  ["Petugas gate"],
                  ["Orang", 1],
                  ["Catatan"],
                ],
                okScans.map(function (s) {
                  var lb = SCAN_LABEL[s.res];
                  return [
                    '<span class="num">' + s.t + "</span>",
                    '<span class="mono" style="font-size:12px">' +
                      esc(s.code) +
                      "</span>",
                    '<span class="badge ' + lb.cls + '">' + lb.txt + "</span>",
                    esc(s.cp),
                    esc(s.by),
                    s.v || "–",
                    '<span class="tbl__sub">' + esc(s.note || "–") + "</span>",
                  ];
                }),
                null,
              );
          }

          if (lapTab === "terjual") {
            body.innerHTML =
              tbl(
                [
                  ["Tanggal"],
                  ["Tiket terjual", 1],
                  ["Tiket terpakai", 1],
                  ["Selisih (hangus)", 1],
                  ["Pengunjung masuk", 1],
                  ["Tingkat pakai", 1],
                ],
                days.map(function (d) {
                  var gap = d.tickets - d.checkedIn;
                  return [
                    esc(d.label),
                    d.tickets,
                    d.checkedIn,
                    gap > 0 ? gap : 0,
                    d.checkedIn,
                    d.tickets
                      ? ((d.checkedIn / d.tickets) * 100).toFixed(0) + "%"
                      : "–",
                  ];
                }),
                [
                  "Total",
                  sum.tickets,
                  sum.checkedIn,
                  sum.tickets - sum.checkedIn,
                  sum.checkedIn,
                  sum.tickets
                    ? ((sum.checkedIn / sum.tickets) * 100).toFixed(0) + "%"
                    : "–",
                ],
              ) +
              '<div class="callout callout--info" style="margin-top:18px">' +
              '<svg class="ico"><use href="#i-info"></use></svg><div>Selisih adalah tiket yang tidak dipakai sampai pukul 24.00 WITA ' +
              "hari berlakunya. Tiket itu hangus tanpa refund dan tanpa perpanjangan.</div></div>";
          }
        }
        function barRow(label, value, total, tone) {
          var pct = total ? (value / total) * 100 : 0;
          return (
            '<div class="barrow"><span>' +
            esc(label) +
            "</span>" +
            '<span class="barrow__track"><span class="barrow__fill' +
            (tone === "ink"
              ? " barrow__fill--ink"
              : tone === "gold"
                ? " barrow__fill--gold"
                : "") +
            '" style="width:' +
            pct.toFixed(1) +
            '%"></span></span>' +
            '<span class="barrow__val">' +
            rp(value) +
            " · " +
            pct.toFixed(0) +
            "%</span></div>"
          );
        }
        function bindLaporan() {
          $("#l-dari").value = lapRange.from;
          $("#l-sampai").value = lapRange.to;
          $("#l-petugas").innerHTML =
            '<option value="">Semua petugas</option>' +
            ADMINS.map(function (u) {
              return (
                '<option value="' +
                esc(u.name) +
                '">' +
                esc(u.name) +
                "</option>"
              );
            }).join("");
          $("#lap-tabs").addEventListener("click", function (e) {
            var b = e.target.closest("[data-lap]");
            if (!b) return;
            lapTab = b.getAttribute("data-lap");
            renderLaporan();
          });
          $("#l-apply").addEventListener("click", function () {
            var f = $("#l-dari").value,
              t = $("#l-sampai").value;
            if (f && t && f > t) {
              toast(
                "err",
                "Rentang tidak valid",
                "Tanggal awal melewati tanggal akhir.",
              );
              return;
            }
            lapRange = { from: f || lapRange.from, to: t || lapRange.to };
            renderLaporan();
            toast(
              "info",
              "Rentang diterapkan",
              ranges().length + " hari dagang.",
            );
          });
          $("#l-reset").addEventListener("click", function () {
            lapRange = { from: isoOf(dayShift(6)), to: isoOf(nowWita()) };
            $("#l-dari").value = lapRange.from;
            $("#l-sampai").value = lapRange.to;
            $("#l-petugas").value = "";
            renderLaporan();
          });
          $$("[data-export]").forEach(function (b) {
            b.addEventListener("click", function () {
              var kind =
                b.getAttribute("data-export") === "pdf" ? "PDF" : "Excel";
              toast(
                "info",
                "Ekspor " + kind + " (pratinjau)",
                "Di sistem nyata berkas diunduh lewat dompdf / PhpSpreadsheet untuk tab " +
                  lapTab +
                  ".",
              );
            });
          });
        }

        /* ================================================================ GLOBAL */
        function delegate() {
          document.addEventListener("click", function (e) {
            var g = e.target.closest("[data-goto]");
            if (g) {
              go(g.getAttribute("data-goto"));
              return;
            }
            var rp1 = e.target.closest("[data-reprint]");
            if (rp1 && !rp1.disabled) {
              doReprint(rp1.getAttribute("data-reprint"));
              return;
            }
            var v = e.target.closest("[data-void]");
            if (v && !v.disabled) {
              doVoid(v.getAttribute("data-void"));
              return;
            }
            var et = e.target.closest("[data-edit-type]");
            if (et) {
              typeModal(Number(et.getAttribute("data-edit-type")));
              return;
            }
            var ru = e.target.closest("[data-reset]");
            if (ru) {
              resetPasswordModal(ru.getAttribute("data-reset"));
              return;
            }
            var eu = e.target.closest("[data-edit-user]");
            if (eu) {
              var u = userByUsername(eu.getAttribute("data-edit-user"));
              openModal({
                title: "Ubah akun " + esc(u.name),
                sub: "Username " + esc(u.username),
                body:
                  '<div class="form-grid">' +
                  '<div class="field"><label class="label" for="u-name">Nama tampilan</label>' +
                  '<input class="input" id="u-name" value="' +
                  esc(u.name) +
                  '"></div>' +
                  '<div class="field"><label class="label" for="u-user">Username</label>' +
                  '<input class="input mono" id="u-user" value="' +
                  esc(u.username) +
                  '"></div></div>' +
                  '<p class="caption" style="margin-top:14px">Perubahan nama tercatat di audit bersama waktu dan pelakunya.</p>',
                footer:
                  '<button class="btn btn--ghost" data-close>Batal</button>' +
                  '<button class="btn btn--primary" id="u-save">Simpan</button>',
                onMount: function (r) {
                  $("#u-save", r).addEventListener("click", function () {
                    u.name = $("#u-name", r).value.trim() || u.name;
                    u.username = $("#u-user", r).value.trim() || u.username;
                    modalClose();
                    renderPengguna();
                    toast("ok", "Akun diperbarui", u.name);
                  });
                },
              });
              return;
            }
            var ec = e.target.closest("[data-edit-cp]");
            if (ec) {
              checkpointModal(Number(ec.getAttribute("data-edit-cp")));
              return;
            }
            var pu = e.target.closest("[data-pu]");
            if (pu) {
              puTab = pu.getAttribute("data-pu");
              renderPengguna();
              return;
            }
          });
          document.addEventListener("change", function (e) {
            var t = e.target.closest("[data-toggle-type]");
            if (t) {
              var ty = TYPES.filter(function (x) {
                return x.id === Number(t.getAttribute("data-toggle-type"));
              })[0];
              ty.active = t.checked;
              renderTiketTypes();
              renderPosTypes();
              renderPosStep();
              toast(
                ty.active ? "ok" : "warn",
                "Jenis tiket " + (ty.active ? "diaktifkan" : "dinonaktifkan"),
                ty.name,
              );
              return;
            }
            var u = e.target.closest("[data-toggle-user]");
            if (u) {
              var usr = userByUsername(u.getAttribute("data-toggle-user"));
              usr.active = u.checked;
              renderPengguna();
              toast(
                usr.active ? "ok" : "warn",
                "Akun " + (usr.active ? "diaktifkan" : "dinonaktifkan"),
                usr.name,
              );
            }
          });
          $$("[data-view]").forEach(function (a) {
            a.addEventListener("click", function (e) {
              e.preventDefault();
              go(a.getAttribute("data-view"));
            });
          });
        }

        /* ================================================================== INIT */
        function init() {
          renderBoard();
          renderChart();
          bindPos();
          fillRegions();
          renderPosTypes();
          updatePayUI();
          renderPosStep();
          bindRiwayat();
          renderRiwayat();
          renderTiketTypes();
          renderPengguna();
          renderCheckpoint();
          loadSettingsForm();
          bindSettings();
          bindLaporan();
          renderLaporan();
          delegate();

          tickClock();
          setInterval(tickClock, 15000);

          $("#tt-add").addEventListener("click", function () {
            typeModal(null);
          });
          $("#pu-add").addEventListener("click", function () {
            toast(
              "info",
              "Tambah pengguna",
              "Di sistem nyata akun Gate dibuat di sini dan langsung bisa dipakai aplikasi gate.",
            );
          });
          $("#cp-add").addEventListener("click", function () {
            checkpointModal(null);
          });
          $("#btn-logout").addEventListener("click", function () {
            toast("info", "Keluar", "Sesi loket ditutup.");
            showLogin();
          });
          $("#user-menu").addEventListener("click", function () {
            toast("info", "Keluar", "Sesi loket ditutup.");
            showLogin();
          });
          $("#login-form").addEventListener("submit", function (e) {
            e.preventDefault();
            var u = $("#login-user").value.trim(),
              p = $("#login-pass").value;
            if (!u || !p) {
              $("#login-error").hidden = false;
              return;
            }
            $("#login-error").hidden = true;
            go("dashboard");
            toast(
              "ok",
              "Selamat bertugas",
              "Hari dagang ditutup pukul 24.00 WITA.",
            );
          });

          var rt;
          window.addEventListener("resize", function () {
            clearTimeout(rt);
            rt = setTimeout(renderChart, 160);
          });

          var start = (location.hash || "#dashboard").replace("#", "");
          go(start === "login" ? "login" : start);
        }
        if (document.readyState === "loading")
          document.addEventListener("DOMContentLoaded", init);
        else init();
      })();
    </script>
  </body>
</html>
