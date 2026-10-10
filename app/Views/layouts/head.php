<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Kecak Uluwatu - Dashboard (AdminLTE 4)</title>

<!-- Bootstrap 5 CSS -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
  rel="stylesheet" />
<!-- Bootstrap Icons -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
  rel="stylesheet" />
<!-- DataTables Bootstrap 5 CSS -->
<link
  href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"
  rel="stylesheet" />
<link
  href="https://cdn.datatables.net/buttons/4.0.2/css/buttons.dataTables.min.css"
  rel="stylesheet" />
<!-- AdminLTE 4 CSS -->
<link rel="stylesheet" href="<?= base_url('css/adminlte.min.css') ?>" />

<link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/modal.css') ?>">



<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration for exact Brand Colors -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              charcoal: '#2A363B',
              dark: '#1e262a',
              red: '#CF4647',
              redHover: '#b53b3c',
              gold: '#F5D061',
              goldHover: '#e0bd50',
              bgLight: '#F8F6F6',
              borderLight: '#E5E7EB',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
          }
        }
      }
    }
  </script>
<!-- BEGIN: Custom Styles for Layout & Utilities -->
<style data-purpose="custom-utilities">
    /* Custom scrollbar for receipt and sidebar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
      background: #c7cbd1;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #a0a6b1;
    }
    
    /* Subtle dot grid for receipt aesthetic */
    .receipt-perforation {
      background-image: radial-gradient(circle, #E5E7EB 1.5px, transparent 1.5px);
      background-size: 8px 8px;
    }
    
    .badge-subtle {
      letter-spacing: 0.025em;
    }

  </style>