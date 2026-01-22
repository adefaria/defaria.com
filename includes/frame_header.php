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
        }

        main.container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>

<?php include_once __DIR__ . '/../php/site-functions.php'; ?>

<body>