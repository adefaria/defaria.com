<?php include 'includes/frame_header.php'; ?>

<?php
// Correct Personal Links from original index.php
$personal_links = [
    ['title' => 'Contact', 'url' => '/contact.php', 'desc' => 'How to get in touch with me'],
    ['title' => 'Addresses', 'url' => '/addresses.php', 'desc' => "I've lived in a lot of places. My address history is here."],
    ['title' => 'Family', 'url' => '/Family/', 'desc' => "Early on the Internet I posted things about my family and my daughter - they are available here."],
    ['title' => 'Jokes', 'url' => '/Jokes/', 'desc' => 'I collected a bunch of joke lines - you know the emails you used to get with a bunch of funny lines. I collected them here.'],
    ['title' => 'Quotes', 'url' => '/libertarian.php', 'desc' => "I'm a libertarian - OK sue me! You'll lose - Here's a bunch of libertarian quotes."],
    ['title' => 'Hotroad - My Corvettes', 'url' => '/Vette/', 'desc' => "I like fast cars - here's a page about my first Corvette."],
    // Blogs section separate? Or just included? Original had them in the same grid loop.
    ['title' => 'Work Blog', 'url' => 'https://defaria-status.blogspot.com/', 'desc' => 'Blog about things I do at work or for clients'],
    ['title' => 'MAPS Blog', 'url' => 'https://defaria-maps.blogspot.com/', 'desc' => 'Updates on the Mail Authorization and Permission System.'],
    ['title' => 'Personal Blog', 'url' => 'https://defaria-personal.blogspot.com/', 'desc' => 'Personal thoughts and musings.'],
    ['title' => 'General Blog', 'url' => 'https://defaria-general.blogspot.com/', 'desc' => 'General topics and commentary.']
];
?>

<main class="container">
    <h1 class="page-title">Personal</h1>
    <div class="link-grid">
        <?php foreach ($personal_links as $link): ?>
            <?php
            $is_external = (strpos($link['url'], 'http') === 0);
            $target = $is_external ? '_blank' : '_self';
            ?>
            <div class="link-card" onclick="window.open('<?php echo $link['url']; ?>', '<?php echo $target; ?>');">
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
            </div>
        <?php endforeach; ?>
    </div>
</main>

<style>
    /* Specific styling for Personal cards */
    .link-card h3 {
        color: var(--google-blue);
    }

    .link-card {
        border-top: 3px solid var(--google-blue);
    }

    /* Ensure container has some padding if needed, frame_header adds 2rem to body */
</style>

<?php // Footer handled by shell ?>