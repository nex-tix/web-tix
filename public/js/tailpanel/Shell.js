/**
 * 2026 Shell renderer.
 *
 * Each page provides:
 *   <body data-active="dashboard" data-crumbs="Workspace | Dashboard">
 *     <div class="shell">
 *       <div data-shell-sidebar></div>
 *       <div class="main">
 *         <div data-shell-topbar></div>
 *         <main class="content"> ...page content... </main>
 *         <div data-shell-footer></div>
 *       </div>
 *     </div>
 *   </body>
 *
 * mountShell() fills the three placeholder divs with the shared chrome,
 * marking the active sidebar item and writing the breadcrumbs.
 *
 * NAV is the single source of truth — adding a page is one entry here.
 */

const packageInfo = { version: "1.0.0" };
const BASE =
  (document.querySelector('meta[name="base-url"]')?.content || "/").replace(
    /\/+$/,
    "",
  ) + "/";

export const NAV = [
  {
    label: "OPERASIONAL",
    items: [
      {
        key: "dashboard",
        text: "Dashboard",
        href: BASE + "admin/dashboard",
        icon: '<path d="M3 12 12 3l9 9"/><path d="M5 10v10h14V10"/>',
      },
      {
        key: "pos",
        text: "Pos Loket",
        href: BASE + "admin/pos",
        icon: ' xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pc-display" viewBox="0 0 16 16"> <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z"/>',
      },
      {
        key: "reprint",
        text: "Cetak Ulang Tiket",
        href: BASE + "admin/pos/reprint",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="50" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"> <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/> <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>',
      },
      {
        key: "riwayat",
        text: "Riwayat Penjualan",
        href: BASE + "admin/transaction-history",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16"> <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/> <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/> <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/> ',
      },
      {
        key: "laporan",
        text: "Laporan",
        href: BASE + "admin/laporan",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16"><path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-50v-3H2v3z"/>',
      },
      {
        key: "data-tiket",
        text: "Data Tiket",
        href: BASE + "admin/data-tiket",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-perforated" viewBox="0 0 16 16"><path d="M4 4.85v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9z"/><path d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3zM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9z"/>',
      },
      {
        key: "jenis-tiket",
        text: "Jenis Tiket",
        href: BASE + "admin/ticket-types",
        icon: ' xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16"><path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z"/><path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z"/>',
      },
      {
        key: "checkpoint",
        text: "Checkpoint",
        href: BASE + "admin/checkpoints",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16"><path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/><path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>',
      },
    ],
  },
  {
    label: "USERS",
    items: [
      {
        key: "admin",
        text: "Admin",
        href: BASE + "admin/users",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>',
      },
      {
        key: "gate",
        text: "Penjaga Gate",
        href: BASE + "admin/gate-users",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16"><path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/><path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>',
      },
      {
        key: "guide",
        text: "Guides",
        href: "#",
        icon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16"><path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/><path d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zM1 3a1 1 0 0 1 1-1h2v2H1zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3zm-4-2h3v2H2a1 1 0 0 1-1-1zm3-1H1V8h3zm0-3H1V5h3z"/>',
      },
    ],
  },
  {
    label: "SISTEM",
    items: [
      {
        key: "setting",
        text: "Settings",
        href: BASE + "admin/settings",
        icon: 'xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>',
      },
    ],
  },
];

const BRAND_LOGO = `<svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
  <path fill="#ffffff" d="M14.747 9.125c.527-1.426 1.736-2.573 3.317-2.573c1.643 0 2.792 1.085 3.318 2.573l6.077 16.867c.186.496.248.931.248 1.147c0 1.209-.992 2.046-2.139 2.046c-1.303 0-1.954-.682-2.264-1.611l-.931-2.915h-8.62l-.93 2.884c-.31.961-.961 1.642-2.232 1.642c-1.24 0-2.294-.93-2.294-2.17c0-.496.155-.868.217-1.023l6.233-16.867zm.34 11.256h5.891l-2.883-8.992h-.062l-2.946 8.992z"/>
</svg>`;

const CHEV =
  '<svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg>';

function isExternal(href) {
  try {
    const url = new URL(href, window.location.origin);
    return url.origin !== window.location.origin;
  } catch {
    return false;
  }
}

function renderNavLink(item, activeKey) {
  const active = item.key === activeKey ? " is-active" : "";
  const badge = item.badge
    ? `<span class="nav-badge ${item.badge.kind}">${item.badge.text}</span>`
    : "";
  const external = isExternal(item.href)
    ? ' target="_blank" rel="noopener noreferrer"'
    : "";
  return `
    <a class="nav-link${active}" href="${item.href}"${external}>
      <svg viewBox="0 0 24 24">${item.icon}</svg>
      <span>${item.text}</span>
      ${badge}
    </a>`;
}

function renderNavGroup(item, activeKey) {
  const open = item.children.some((c) => c.key === activeKey) ? " is-open" : "";
  const submenu = item.children
    .map((c) => `<a href="${c.href}">${c.text}</a>`)
    .join("");
  return `
    <div class="nav-item-group${open}" data-nav-group>
      <a class="nav-link" href="javascript:void(0)" data-nav-toggle>
        <svg viewBox="0 0 24 24">${item.icon}</svg>
        <span>${item.text}</span>
        ${CHEV}
      </a>
      <div class="nav-submenu">${submenu}</div>
    </div>`;
}

function renderSection(section, activeKey) {
  const items = section.items
    .map((item) =>
      item.children
        ? renderNavGroup(item, activeKey)
        : renderNavLink(item, activeKey),
    )
    .join("");
  return `
    <nav class="nav-section">
      <div class="nav-label">${section.label}</div>
      ${items}
    </nav>`;
}

function renderSidebar(activeKey) {
  const sections = NAV.map((s) => renderSection(s, activeKey)).join("");
  return `
    <aside class="d-sidebar">
      <div class="brand">
        <div class="brand-logo">${BRAND_LOGO}</div>
        <div class="brand-text">
          <div class="brand-name">Adminator</div>
          <div class="brand-tag">v${packageInfo.version} · preview</div>
        </div>
      </div>
      ${sections}
      <div class="sidebar-footer">
        <div class="workspace">
          <div class="workspace-avatar">JD</div>
          <div class="workspace-text">
            <div class="workspace-name">John Doe</div>
            <div class="workspace-role">admin</div>
          </div>
          <svg class="workspace-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="m7 9 5-5 5 5"/><path d="m7 15 5 5 5-5"/>
          </svg>
        </div>
      </div>
    </aside>`;
}

function renderCrumbs(crumbsAttr) {
  if (!crumbsAttr) return "";
  const parts = crumbsAttr
    .split("|")
    .map((p) => p.trim())
    .filter(Boolean);
  const sep =
    '<svg class="sep" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>';
  return parts
    .map((p, i) => {
      const cls = i === parts.length - 1 ? ' class="current"' : "";
      return `${i > 0 ? sep : ""}<span${cls}>${p}</span>`;
    })
    .join("");
}

function renderTopbar(crumbsAttr) {
  return `
    <header class="d-topbar">
      <div class="crumbs">
        <button class="hamburger" data-drawer-open aria-label="Open navigation">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        ${renderCrumbs(crumbsAttr)}
      </div>
      <div class="topbar-actions">
        <button class="cmd" data-palette-open>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <span>Search...</span>
          <kbd class="kbd">⌘K</kbd>
        </button>

        <div class="dd-wrap">
          <button class="icon-btn" data-dropdown aria-label="Notifications">
            <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="count danger">3</span>
          </button>
          <div class="dd-menu" role="menu">
            <div class="dd-head">
              <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              Notifications
            </div>
            <div class="dd-list">
              <a class="dd-item" href="#">
                <div class="dd-avatar a1">JD</div>
                <div class="dd-body">
                  <div class="dd-text"><strong>John Doe</strong> liked your <em>post</em></div>
                  <div class="dd-time">5 MIN AGO</div>
                </div>
              </a>
              <a class="dd-item" href="#">
                <div class="dd-avatar a2">MD</div>
                <div class="dd-body">
                  <div class="dd-text"><strong>Moo Doe</strong> liked your <em>cover image</em></div>
                  <div class="dd-time">7 MIN AGO</div>
                </div>
              </a>
              <a class="dd-item" href="#">
                <div class="dd-avatar a3">LD</div>
                <div class="dd-body">
                  <div class="dd-text"><strong>Lee Doe</strong> commented on your <em>video</em></div>
                  <div class="dd-time">10 MIN AGO</div>
                </div>
              </a>
            </div>
            <a class="dd-footer" href="#">View all notifications →</a>
          </div>
        </div>

        <div class="dd-wrap">
          <button class="icon-btn" data-dropdown aria-label="Messages">
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            <span class="count info">3</span>
          </button>
          <div class="dd-menu" role="menu">
            <div class="dd-head">
              <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
              Messages
            </div>
            <div class="dd-list">
              <a class="dd-item" href="#">
                <div class="dd-avatar a1">JD</div>
                <div class="dd-body">
                  <div class="dd-row-head"><strong>John Doe</strong><span class="dd-time">5 MIN</span></div>
                  <div class="dd-preview">Want to create your own customized data generator for your app…</div>
                </div>
              </a>
              <a class="dd-item" href="#">
                <div class="dd-avatar a2">MD</div>
                <div class="dd-body">
                  <div class="dd-row-head"><strong>Moo Doe</strong><span class="dd-time">15 MIN</span></div>
                  <div class="dd-preview">Want to create your own customized data generator for your app…</div>
                </div>
              </a>
              <a class="dd-item" href="#">
                <div class="dd-avatar a3">LD</div>
                <div class="dd-body">
                  <div class="dd-row-head"><strong>Lee Doe</strong><span class="dd-time">25 MIN</span></div>
                  <div class="dd-preview">Want to create your own customized data generator for your app…</div>
                </div>
              </a>
            </div>
            <a class="dd-footer" href="#">View all messages →</a>
          </div>
        </div>

        <button class="icon-btn" id="themeToggle" aria-label="Toggle theme"></button>

        <div class="dd-wrap">
          <div class="avatar" data-dropdown tabindex="0" role="button" aria-label="Account menu">JD</div>
          <div class="dd-menu dd-profile" role="menu">
            <div class="dd-profile-head">
              <div class="dd-profile-name">John Doe</div>
              <div class="dd-profile-email">john@adminator.app</div>
            </div>
            <a class="dd-menu-item" href="#">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              Settings
            </a>
            <a class="dd-menu-item" href="#">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Profile
            </a>
            <a class="dd-menu-item" href="email.html">
              <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
              Messages
            </a>
            <div class="dd-divider"></div>
            <a class="dd-menu-item danger" href="#">
              <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
              Logout
            </a>
          </div>
        </div>
      </div>
    </header>`;
}

function renderFooter() {
  return `
    <footer class="d-footer">
      <div>© 2026 · Designed by <a href="https://colorlib.com" target="_blank" rel="nofollow noopener noreferrer">Colorlib</a></div>
      <div class="d-footer-meta">
        <span>v${packageInfo.version}</span>
        <span>preview build</span>
      </div>
    </footer>`;
}

export function mountShell() {
  const body = document.body;
  const activeKey = body.getAttribute("data-active") || "";
  const crumbs = body.getAttribute("data-crumbs") || "";

  const sidebarHost = document.querySelector("[data-shell-sidebar]");
  const topbarHost = document.querySelector("[data-shell-topbar]");
  const footerHost = document.querySelector("[data-shell-footer]");

  if (sidebarHost) sidebarHost.outerHTML = renderSidebar(activeKey);
  if (topbarHost) topbarHost.outerHTML = renderTopbar(crumbs);
  if (footerHost) footerHost.outerHTML = renderFooter();
}

document.addEventListener("DOMContentLoaded", () => {
  mountShell();
});
