<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andrew DeFaria</title>
    <?php
    $favicon = "/Icons/Home.ico";
    $reltype = "image/x-icon";

    // Check URL to set specific favicons
    $req_url = $_SERVER['REQUEST_URI'];
    if (isset($_GET['url'])) {
        $req_url = $_GET['url'];
    }

    // Simple substring matching for context
    if (stripos($req_url, 'song') !== false || stripos($req_url, 'music') !== false) {
        $favicon = "/songbook/Music.ico";
        $reltype = "image/png"; // It is actually an ico file but served as png sometimes? Let's check. 
        // The file is Music.ico. Browser handles mime type usually.
        // But let's stick to valid types.
        $reltype = "image/x-icon";
    } elseif (stripos($req_url, 'maps') !== false) {
        $favicon = "/maps/MAPS.png";
        $reltype = "image/png";
    }
    ?>
    <link rel="icon" href="<?php echo $favicon; ?>?v=<?php echo time(); ?>" type="<?php echo $reltype; ?>">
    <link rel="shortcut icon" href="<?php echo $favicon; ?>?v=<?php echo time(); ?>" type="<?php echo $reltype; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <script language="JavaScript1.2" src="/maps/JavaScript/CheckAddress.js?v=<?php echo time(); ?>"
        type="text/javascript"></script>


</head>

<?php include_once __DIR__ . '/../php/site-functions.php'; ?>

<body>
    <!-- Body starts here, app structure follows in index.php -->