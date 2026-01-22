<?php include 'includes/frame_header.php'; ?>
<?php
$hidden_links = [
    ['title' => 'Anthem', 'url' => '/Anthem/', 'desc' => 'Hidden site.'],
    ['title' => 'Broadcom', 'url' => '/Broadcom/', 'desc' => 'Hidden site.'],
    ['title' => 'IBM', 'url' => '/IBM/', 'desc' => 'Hidden site.'],
    ['title' => 'Insurance', 'url' => '/Insurance/', 'desc' => 'Hidden site.'],
    ['title' => 'SanMelia', 'url' => '/SanMelia/', 'desc' => 'Hidden site.'],
    ['title' => 'Wells Fargo', 'url' => '/wellsfargo/', 'desc' => 'Hidden site.']
];
?>

<main class="container">
    <h1 class="page-title">Hidden</h1>
    <!-- <p>Shhh... It's a secret.</p> -->

    <div class="link-grid">
        <?php foreach ($hidden_links as $link): ?>
            <a href="<?php echo $link['url']; ?>" class="link-card">
                <h3><?php echo $link['title']; ?></h3>
                <p><?php echo $link['desc']; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<style>
    /* Specific styling for Hidden cards */
    .link-card h3 {
        color: var(--google-purple);
    }

    .link-card {
        border-top: 3px solid var(--google-purple);
    }
</style>

<?php // Footer handled by shell ?>