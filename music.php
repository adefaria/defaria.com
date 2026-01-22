<?php include 'includes/frame_header.php'; ?>
<?php
$music_links = [
    ['title' => 'Rock Ready', 'url' => 'https://defaria.com/rockready', 'desc' => 'My current band - we are ready to rock you! See the band\'s website for more information.'],
    ['title' => 'Cast of Shadows', 'url' => '/Band/index.php', 'desc' => 'COS was perhaps my most successful band. We played Top 40 Dance music in the clubs of the South Bay Area and produced a CD of 10 original tunes'],
    ['title' => 'Songbook', 'url' => '/songbook/', 'desc' => 'My personal songbook. Contains lyrics and chords of various songs. Even plays them!', 'target' => '_blank'],
    ['title' => 'Bluegrass', 'url' => '/bluegrass/', 'desc' => 'Bluegrass Songbook.', 'target' => '_blank'],
    ['title' => 'XMAS', 'url' => '/xmas/', 'desc' => 'Christmas Songbook.', 'target' => '_blank'],
    ['title' => 'Spleeter', 'url' => 'https://spleeter.defariahome.com', 'desc' => 'AI source separation tool.', 'target' => '_blank']
];
?>

<main class="container">
    <h1 class="page-title">Music</h1>
    <!-- <p>Bands, Songs, and Sound.</p> -->

    <div class="link-grid">
        <?php foreach ($music_links as $link): ?>
            <?php
            $target = $link['target'] ?? ((strpos($link['url'], 'http') === 0) ? '_blank' : '_self');
            $is_external = ($target === '_blank');
            ?>
            <a href="<?php echo $link['url']; ?>" class="link-card" target="<?php echo $target; ?>">
                <!-- target=_top for external links? No, regular links should probably stay frame or top? User said 'Every content page is an iframe'. If it's a link to a sub-site, it might need to load in the frame or new window. For now, assuming standard link behavior inside frame or top if it breaks frame. Most existing links are sub-folders. -->
                <h3>
                    <?php echo $link['title']; ?>
                    <?php if ($is_external): ?>
                        <?php if ($is_external): ?>
                            <svg class="external-icon"
                                style="width: 0.8em; height: 0.8em; vertical-align: middle; fill: currentColor; margin-left: 0.3em;"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7zm-2 16H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7z" />
                            </svg>
                        <?php endif; ?>
                    <?php endif; ?>
                </h3>
                <p><?php echo $link['desc']; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<style>
    /* Specific styling for Music cards if needed */
    .link-card h3 {
        color: var(--google-yellow);
    }

    .link-card {
        border-top: 3px solid var(--google-yellow);
    }
</style>

<?php // Footer handled by shell ?>