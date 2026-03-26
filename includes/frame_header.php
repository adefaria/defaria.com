<!DOCTYPE html>
<html lang="en" data-theme="<?php echo isset($_COOKIE['user_theme']) ? htmlspecialchars($_COOKIE['user_theme']) : 'dark'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php date_default_timezone_set('America/Los_Angeles'); ?>
    <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
    <title><?php echo isset($page_title) ? $page_title . " - Andrew DeFaria" : "Andrew DeFaria"; ?></title>
    <link rel="icon" href="/Icons/Home.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            background-color: transparent;
            /* Or var(--surface-color) */
            padding: 2rem;
            padding-bottom: 120px;
            /* Ensure content is above footer */
            height: auto;
            min-height: 0;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
                padding-bottom: 120px;
                /* Nav (60) + Copyright (~30-40) + buffer */
            }
        }

        main.container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
    <script>
        // Apply theme immediately to prevent flash
        (function () {
            try {
                // Try to get from parent first (most accurate if in sync)
                var theme;
                if (window.parent && window.parent.document) {
                    theme = window.parent.document.documentElement.getAttribute('data-theme');
                }

                // Fallback to cookie (matching index.php) or parent
                if (!theme) {
                    var match = document.cookie.match(new RegExp('(^| )user_theme=([^;]+)'));
                    if (match) theme = match[2];
                }

                // Fallback to system preference or light
                if (!theme) {
                    theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                if (theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                }
            } catch (e) {
                var theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                // Try cookie in catch block too
                try {
                    var match = document.cookie.match(new RegExp('(^| )user_theme=([^;]+)'));
                    if (match) theme = match[2];
                } catch (err) { }
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();

        // Keep theme in sync with parent dynamically
        if (window.parent && window.parent.document !== document) {
            try {
                // Determine preferred theme from parent immediately
                var parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                if (parentTheme) document.documentElement.setAttribute('data-theme', parentTheme);

                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.type === "attributes" && mutation.attributeName === "data-theme") {
                            var newTheme = window.parent.document.documentElement.getAttribute('data-theme');
                            document.documentElement.setAttribute('data-theme', newTheme);
                        }
                    });
                });

                observer.observe(window.parent.document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-theme']
                });
            } catch (e) {
                console.log('Error syncing theme with parent:', e);
            }

            // Sync Title and Footer Date
            window.addEventListener('DOMContentLoaded', function () {
                try {
                    if (window.parent && window.parent.document !== document) {
                        // Update parent title
                        if (document.title) {
                            window.parent.document.title = document.title;
                        }

                        // Update parent footer if accessible
                        var footerDate = window.parent.document.getElementById('footer-mod-date');
                        if (footerDate) {
                            var meta = document.querySelector('meta[name="last-modified"]');
                            if (meta) {
                                var pageName = document.title.replace(' - Andrew DeFaria', '');
                                if (pageName === 'Andrew DeFaria') pageName = 'Welcome';
                                footerDate.textContent = pageName + ': Last modified ' + meta.getAttribute('content');
                            }
                        }
                    }
                } catch (e) {
                    console.log('Cross-origin theme sync failed, falling back to message listener');
                }
            });
        }
        
        // Listen for robust postMessage theme updates from parent (Bypasses CORS entirely)
        window.addEventListener('message', function (event) {
            if (event.data && event.data.type === 'themeChange' && event.data.theme) {
                document.documentElement.setAttribute('data-theme', event.data.theme);
            }
        });
    </script>
</head>

<?php include_once __DIR__ . '/../php/site-functions.php'; ?>

<body class="content-frame">