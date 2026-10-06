        <header class="topbar">
          <div class="topbar__ctx">
            <span class="dot" aria-hidden="true"></span>
            <strong>Terminal Loket 1</strong>
            <span>Shift siang &middot; online</span>
          </div>
          <div class="topbar__spacer"></div>
          <div
            class="dayclock"
            title="Tiket hanya berlaku sampai pukul 24.00 WITA hari ini"
          >
            <svg class="ico ico--lg dayclock__ico">
              <use href="#i-clock"></use>
            </svg>
            <div style="flex: 1">
              <div class="dayclock__date" id="day-date">Hari dagang</div>
              <div class="dayclock__row">
                <span class="dayclock__left" id="day-left">--j --m</span>
                <span class="dayclock__cap">batas 24.00 WITA</span>
              </div>
              <div class="dayclock__bar">
                <div class="dayclock__fill" id="day-bar"></div>
              </div>
            </div>
          </div>
          <button
            class="btn btn--ghost btn--sm"
            id="btn-logout"
            title="Keluar ke layar login"
          >
            <svg class="ico"><use href="#i-logout"></use></svg> Keluar
          </button>
        </header>