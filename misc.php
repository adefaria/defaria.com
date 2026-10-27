<?php $page_title = "Misc";
include 'includes/frame_header.php'; ?>
<?php
$hidden_links = [
    ['title' => 'Temporary Files', 'url' => '/tmp', 'desc' => 'Temporary area for sharing files'],
    ['title' => 'Web Monitor', 'url' => '/webmonitor', 'desc' => 'A little PHP app to see if people are viewing pictures and videos of the links I send them.'],
    ['title' => 'SanMelia', 'url' => '/SanMelia', 'desc' => 'Beautiful apartment complex with terrible management - KEEP AWAY!'],
    ['title' => 'IBM', 'url' => '/IBM', 'desc' => 'Problems with IBM support'],
    ['title' => 'Wells Fargo', 'url' => '/wellsfargo', 'desc' => 'A bank to avoid (even though they later hired me as a contractor!)'],
    ['title' => 'Wolters Kluwer', 'url' => '/wk', 'desc' => 'Back in the corporate world things are still messed up']
];
?>

<main class="container">
    <h1 class="page-title">Misc</h1>
    <!-- <p>Shhh... It's a secret.</p> -->

    <div class="link-grid">
        <?php foreach ($hidden_links as $link): ?>
            <a href="<?php echo $link['url']; ?>" onclick="if(window.parent && window.parent.activateTab) { 
                   window.parent.history.pushState(null, '', '<?php echo $link['url']; ?>'); 
                   window.parent.activateTab('<?php echo $link['url']; ?>'); 
                   return false; 
               } return true;" class="link-card">
                <h3><?php echo $link['title']; ?></h3>
                <p><?php echo $link['desc']; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<style>
    /* Specific styling for Hidden cards */
    .link-card h3 {
        color: var(--google-red);
    }

    .page-title {
        color: var(--google-red);
    }

    .link-card {
        border-top: 3px solid var(--google-red);
    }
</style>

<?php include 'includes/footer.php'; ?>