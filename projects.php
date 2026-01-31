<?php $page_title = "Projects";
include 'includes/frame_header.php'; ?>
<?php
$project_links = [
    ['title' => 'Upload', 'url' => 'upload.php', 'desc' => 'File upload utility.'],
    ['title' => 'YouTube Download', 'url' => '/yt/', 'desc' => 'Download videos from YouTube.'],
    ['title' => 'Spleeter', 'url' => 'https://spleeter.defariahome.com', 'desc' => 'AI source separation tool.']
];
?>

<main class="container">
    <h1 class="page-title">Projects</h1>
    <p class="page-description">Contained here are various projects or utilities that I've been working on.</p>
    <!-- <p>Code, Utilities, and Tools.</p> -->

    <div class="link-grid">

        <?php foreach ($project_links as $link): ?>
            <?php
            // Default target logic similar to music.php, but override specifically for these internal tools
            $is_tool = in_array($link['title'], ['Upload', 'YouTube Download', 'Spleeter']);
            $target = $is_tool ? '_self' : '_blank';
            $is_external = ($target === '_blank');
            ?>
            <a href="<?php echo $link['url']; ?>"
                class="link-card card-<?php echo strtolower(str_replace(' ', '-', $link['title'])); ?>"
                target="<?php echo $target; ?>">
                <h3>
                    <?php echo $link['title']; ?>
                    <?php if ($is_external): ?>
                        <svg class="external-icon"
                            style="width: 0.8em; height: 0.8em; vertical-align: middle; fill: currentColor; margin-left: 0.3em;"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7zm-2 16H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7z" />
                        </svg>
                    <?php endif; ?>
                </h3>
                <p><?php echo $link['desc']; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<style>
    /* Specific styling for Project cards */
    .link-card h3 {
        color: var(--google-green);
    }

    .page-title {
        color: var(--google-green);
    }

    .link-card {
        border-top: 3px solid var(--google-green);
    }
</style>

<?php include 'includes/footer.php'; ?>