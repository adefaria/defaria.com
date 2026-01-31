<?php $page_title = "Blogs";
include 'includes/frame_header.php'; ?>

<?php
$blog_links = [
    ['title' => 'Work Blog', 'url' => 'https://defaria-status.blogspot.com/', 'desc' => 'Blog about things I do at work or for clients'],
    ['title' => 'MAPS Blog', 'url' => 'https://defaria-maps.blogspot.com/', 'desc' => 'Updates on the Mail Authorization and Permission System.'],
    ['title' => 'Personal Blog', 'url' => 'https://defaria-personal.blogspot.com/', 'desc' => 'Personal thoughts and musings.'],
    ['title' => 'General Blog', 'url' => 'https://defaria-general.blogspot.com/', 'desc' => 'General topics and commentary.']
];
?>

<main class="container">
    <h1 class="page-title">Blogs</h1>
    <p class="page-description">I write about various topics in my blogs. From work status and technical updates to
        personal thoughts and general commentary.</p>
    <div class="link-grid">
        <?php foreach ($blog_links as $link): ?>
            <?php
            $is_external = (strpos($link['url'], 'http') === 0);
            $target = $is_external ? '_blank' : '_self';
            ?>
            <a href="<?php echo $link['url']; ?>" class="link-card" target="<?php echo $target; ?>">
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
    /* Specific styling for Blog cards */
    .link-card h3 {
        color: var(--google-blue);
    }

    .page-title {
        color: var(--google-blue);
    }

    .link-card {
        border-top: 3px solid var(--google-blue);
    }
</style>

<?php include 'includes/footer.php'; ?>