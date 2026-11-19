/**
 * 2026 entry point.
 *
 * Order of operations:
 *   1. Wire shell behaviors (theme toggle, dropdowns, nav groups, todos).
 *
 * Shell (sidebar/topbar/footer) dirender oleh PHP — lihat
 * app/Views/partials/*.php yang dipanggil layouts/main.php.
 */

import { initShellBehaviors } from './init.js';
import { initCharts } from './charts.js';
import { initVectorMaps } from './maps.js';
import { initCalendarPage } from './calendar.js';
import { initPalette } from './palette.js';

function start() {
  initShellBehaviors();
  initPalette();
  initCharts();
  initVectorMaps();
  initCalendarPage();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', start);
} else {
  start();
}
