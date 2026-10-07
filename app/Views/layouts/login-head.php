<meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login Petugas - nextix LOKET &amp; GATE</title>
    <!-- Tailwind CSS CDN with Forms & Container Queries -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1e232a',
                            darker: '#16191f',
                            sidebar: '#222731',
                            coral: '#e24a3b',
                            coralHover: '#cc3d2f',
                            accentYellow: '#f59e0b',
                            surface: '#f3f4f7'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- BEGIN: Custom Styles -->
        <link rel="stylesheet" href="<?= base_url('css/auth-main.css') ?>">
    <!-- END: Custom Styles -->