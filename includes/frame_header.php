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
            var theme = null;

            // 1. Try localStorage first
            try { theme = localStorage.getItem('user_theme_override'); } catch(e) {}

            // 2. Try cookie
            if (!theme) {
                try {
                    var match = document.cookie.match(/(^| )user_theme_override=([^;]+)/);
                    if (match) theme = match[2];
                } catch(e) {}
            }

            // 3. Try parent document attribute
            if (!theme) {
                try {
                    if (window.parent && window.parent.document) {
                        theme = window.parent.document.documentElement.getAttribute('data-theme');
                    }
                } catch(e) {}
            }

            // 4. Fallback to system preference
            if (!theme) {
                try {
                    theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                } catch(e) {}
            }
            
            if (!theme) theme = 'dark'; // Ultimate fallback

            try { document.documentElement.setAttribute('data-theme', theme); } catch(e) {}
        })();

        // Standalone OS theme listener (for direct PHP page access without iframe)
        try {
            if (window.self === window.top && window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    var manualTheme = null;
                    try { manualTheme = localStorage.getItem('user_theme_override'); } catch(err) {}
                    if (!manualTheme) {
                        try {
                            var match = document.cookie.match(/(^| )user_theme_override=([^;]+)/);
                            if (match) manualTheme = match[2];
                        } catch(err) {}
                    }
                    if (!manualTheme) {
                        document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                    }
                });
            }
        } catch(e) {}

        // PRIMARY: Listen for postMessage theme updates from parent.
        // This MUST be registered first, before any risky parent.document access,
        // so it works even in Brave/Firefox where parent.document may throw SecurityError.
        window.addEventListener('message', function (event) {
            if (event.data && event.data.type === 'themeChange' && event.data.theme) {
                document.documentElement.setAttribute('data-theme', event.data.theme);
                // Persist to localStorage only if it was a manual user action
                if (event.data.save === true) {
                    try { localStorage.setItem('user_theme_override', event.data.theme); } catch(e) {}
                } else if (event.data.save === false) {
                    try { localStorage.removeItem('user_theme_override'); } catch(e) {}
                }
            }
        });

        // SECONDARY: Try direct parent DOM sync (same-origin only).
        // Use window.self !== window.top as the iframe check (doesn't access parent.document).
        // All parent.document access is wrapped in try/catch for Firefox/Brave safety.
        if (window.self !== window.top) {
            try {
                var parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                if (parentTheme) document.documentElement.setAttribute('data-theme', parentTheme);

                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                            try {
                                var newTheme = window.parent.document.documentElement.getAttribute('data-theme');
                                document.documentElement.setAttribute('data-theme', newTheme);
                            } catch(e) {}
                        }
                    });
                });

                observer.observe(window.parent.document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-theme']
                });
            } catch (e) {
                // Cross-origin or Brave/Firefox security restriction - postMessage will handle it
            }

            // Sync Title and Footer Date with parent (best-effort, same-origin only)
            window.addEventListener('DOMContentLoaded', function () {
                try {
                    if (document.title) window.parent.document.title = document.title;
                    var footerDate = window.parent.document.getElementById('footer-mod-date');
                    if (footerDate) {
                        var meta = document.querySelector('meta[name="last-modified"]');
                        if (meta) {
                            var pageName = document.title.replace(' - Andrew DeFaria', '');
                            if (pageName === 'Andrew DeFaria') pageName = 'Welcome';
                            footerDate.textContent = pageName + ': Last modified ' + meta.getAttribute('content');
                        }
                    }
                } catch (e) { /* cross-origin - silently ignore */ }
            });
        }
    </script>
</head>

<?php include_once __DIR__ . '/../php/site-functions.php'; ?>

<body class="content-frame">