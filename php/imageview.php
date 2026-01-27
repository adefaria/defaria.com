<?php
// imageview.php
// Wrapper to display images with white background

if (isset($_GET['image'])) {
    $image = $_GET['image'];
} else {
    echo "No image file provided.";
    exit;
}

// Security/Validation check (basic) - ensure path is safe? 
// monitorFile.php validates path before sending here.
// But we should be careful. $image is likely relative web path.
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo basename($image); ?></title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: white;
            color: black;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: black;
            color: white;
        }

        img {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    <img src="<?php echo htmlspecialchars($image); ?>" alt="Image">
</body>

</html>