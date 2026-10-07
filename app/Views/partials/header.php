      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid px-0">
          <!-- Sidebar Toggle -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a
                class="nav-link"
                data-lte-toggle="sidebar"
                href="#"
                role="button"
                aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
              </a>
            </li>
          </ul>
          <!-- Header kanan -->
          <div class="top-bar-right">
            <div class="header-clock">
              <span class="date-label" id="header-trading-date"></span>
              <span class="time-label" id="header-countdown">
                Menghitung... <span>batas 24.00 WITA</span>
              </span>
            </div>
            <a href="<?= base_url('/logout') ?>" class="btn-keluar">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </a>
          </div>
        </div>
      </nav>

      <script>
        (() => {
          const dateElement = document.getElementById('header-trading-date');
          const countdownElement = document.getElementById('header-countdown');
          const witaFormatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Makassar',
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
          });

          function updateWitaClock() {
            const now = new Date();
            const witaParts = new Intl.DateTimeFormat('en-CA', {
              timeZone: 'Asia/Makassar',
              year: 'numeric',
              month: '2-digit',
              day: '2-digit',
              hour: '2-digit',
              minute: '2-digit',
              second: '2-digit',
              hourCycle: 'h23'
            }).formatToParts(now).reduce((parts, part) => {
              parts[part.type] = part.value;
              return parts;
            }, {});

            const witaNow = new Date(Date.UTC(
              Number(witaParts.year),
              Number(witaParts.month) - 1,
              Number(witaParts.day),
              Number(witaParts.hour),
              Number(witaParts.minute),
              Number(witaParts.second)
            ));
            const nextMidnight = new Date(witaNow);
            nextMidnight.setUTCDate(nextMidnight.getUTCDate() + 1);
            nextMidnight.setUTCHours(0, 0, 0, 0);
            const remainingSeconds = Math.max(0, Math.floor((nextMidnight - witaNow) / 1000));
            const hours = Math.floor(remainingSeconds / 3600);
            const minutes = Math.floor((remainingSeconds % 3600) / 60);
            const seconds = remainingSeconds % 60;

            dateElement.textContent = `Hari dagang - ${witaFormatter.format(now)}`;
            countdownElement.childNodes[0].textContent = `Sisa ${hours}j ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}d `;
          }

          updateWitaClock();
          window.setInterval(updateWitaClock, 1000);
        })();
      </script>