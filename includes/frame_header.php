<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
    <title>Andrew DeFaria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?v=3">
    <style>
        body {
            background-color: transparent;
            /* Or var(--surface-color) */
            padding: 2rem;
            padding-bottom: 80px;
            /* Ensure content is above footer */
            overflow-y: auto;
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
        // Redirect standalone pages to App Shell
        if (window.self === window.top) {
            var path = window.location.pathname;
            var hash = '';
            if (path.indexOf('resume') !== -1) hash = 'resume';
            else if (path.indexOf('personal.php') !== -1) hash = 'personal';
            else if (path.indexOf('professional.php') !== -1) hash = 'professional';
            else if (path.indexOf('music.php') !== -1) hash = 'music';
            else if (path.indexOf('projects.php') !== -1) hash = 'projects';
            else if (path.indexOf('welcome.php') !== -1) hash = 'welcome';

            if (hash) {
                window.location.replace('/#' + hash);
            }
        }

        // Apply theme immediately to prevent flash
        (function () {
            try {
                // Try to get from parent first (most accurate if in sync)
                var theme;
                if (window.parent && window.parent.document) {
                    theme = window.parent.document.documentElement.getAttribute('data-theme');
                }

                // Fallback to local storage or matching logic if standalone
                if (!theme) {
                    theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
                }

                if (theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                }
            } catch (e) {
                // If cross-origin or other error, fallback to storage
                var theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
</head>

<?php include_once __DIR__ . '/../php/site-functions.php'; ?>

<body>