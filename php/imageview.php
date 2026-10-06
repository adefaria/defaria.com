<?php
// imageview.php
// Display images with theme support, filename header (no ext), navigation buttons, description, and slideshow auto-advance

require_once realpath(__DIR__ . '/media_functions.php');

if (isset($_GET['image'])) {
    $image = $_GET['image'];
} else {
    echo "No image file provided.";
    exit;
}

$title = getMediaTitle($image);
$nav = getMediaNavigation($image);
$nextUrl = $nav['next'];
$description = getMediaDescription($image);
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background-color: white;
            color: black;
            font-family: sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: black;
            color: white;
        }

        .player-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 90vw;
        }

        img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .media-title {
            margin: 15px 20px 10px 20px;
            font-size: 1.5rem;
            text-align: center;
            word-break: break-word;
        }

        .nav-controls {
            display: flex;
            gap: 15px;
            margin: 10px 0 15px 0;
        }

        .nav-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .nav-button:hover:not(.disabled) {
            background-color: #0056b3;
        }

        .nav-button.disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            pointer-events: none;
        }

        body.dark-mode .nav-button.disabled {
            background-color: #444;
            color: #888;
        }

        .media-description {
            width: 100%;
            max-width: 700px;
            margin: 10px 20px 30px 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: left;
            word-break: break-word;
        }

        body.dark-mode .media-description {
            border-top-color: #444;
        }
    </style>
    <script>
        function updateTheme() {
            try {
                const parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                if (parentTheme === 'dark') {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
            } catch (e) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.body.classList.add('dark-mode');
                }
            }
        }
        document.addEventListener('DOMContentLoaded', updateTheme);
        if (window !== window.top) {
            try {
                const observer = new MutationObserver(updateTheme);
                observer.observe(window.parent.document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
            } catch (e) { }
        }
    </script>
</head>

<body>
    <div class="player-container">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
        <h2 class="media-title"><?php echo htmlspecialchars($title); ?></h2>

        <div class="nav-controls">
            <?php if (!empty($nav['prev'])): ?>
                <a href="<?php echo htmlspecialchars($nav['prev']); ?>" class="nav-button prev-button">&laquo; Previous</a>
            <?php else: ?>
                <span class="nav-button disabled">&laquo; Previous</span>
            <?php endif; ?>

            <?php if (!empty($nav['next'])): ?>
                <a href="<?php echo htmlspecialchars($nav['next']); ?>" class="nav-button next-button">Next &raquo;</a>
            <?php else: ?>
                <span class="nav-button disabled">Next &raquo;</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($description)): ?>
            <div class="media-description">
                <?php echo $description; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const nextUrl = "<?php echo htmlspecialchars($nextUrl, ENT_QUOTES); ?>";
        if (nextUrl) {
            // Auto-advance slideshow to next media file after 5 seconds
            setTimeout(() => {
                window.location.href = nextUrl;
            }, 5000);
        }
    </script>
</body>

</html>