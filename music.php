<?php $page_title = "Music";
include 'includes/frame_header.php'; ?>
<?php
$music_links = [
    ['title' => 'Rock Ready', 'url' => 'https://defaria.com/rockready', 'desc' => 'My current band - we are ready to rock you! See the band\'s website for more information.'],
    ['title' => 'Songbook', 'url' => '/songbook/', 'desc' => 'My personal songbook. Contains lyrics and chords of various songs. Even plays them!', 'target' => '_blank'],
    ['title' => 'Cast of Shadows', 'url' => '/cos', 'target' => '_top', 'desc' => 'COS was perhaps my most successful band. We played Top 40 Dance music in the clubs of the South Bay Area and produced a CD of 10 original tunes'],
    ['title' => 'Bottoms Up', 'url' => '/bottomsup', 'target' => '_top', 'desc' => 'After COS I was in a band called Bottoms Up for a while.'],
    ['title' => 'Bottoming Out in Monterey', 'url' => '/bottomingout', 'desc' => 'One of Bottoms Up final performances at Doc Rickets in Monterey, CA. Captured on cassette from the sound board and preserved here.', 'target' => '_top'],
    ['title' => 'ARM', 'url' => '/arm', 'desc' => 'Andrew, Rick and Michele - a little acoustic group doing some memorable songs.', 'target' => '_top']
];
?>

<main class="container">
    <h1 class="page-title">Music</h1>
    <div class="music-intro">
        <img src="/Images/Music/AndrewLiveBW.jpg" alt="Andrew playing guitar" class="intro-image left">
        <p class="page-description">Computers and music have always been my yin yang of my life. I play guitar, bass and
            vocals. Cast of Shadows was my best band and Rock Ready is my current band. Songbook is a web based songbook
            that uses Chordpro file format to organize my music and interface with the Songbook app on my Android
            Tablet.
        </p>
        <img src="/Images/Music/AndrewLiveColor.jpg" alt="Andrew playing guitar" class="intro-image right">
    </div>

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
    .music-intro {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .intro-image {
        max-width: 250px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        object-fit: cover;
    }

    .page-description {
        flex: 1;
        font-size: 1.1rem;
        line-height: 1.6;
        text-align: justify;
    }

    @media (max-width: 900px) {
        .music-intro {
            flex-direction: column;
            text-align: center;
        }

        .intro-image {
            max-width: 80%;
            margin-bottom: 1rem;
        }

        .page-description {
            text-align: left;
        }

        /* Reorder for mobile: Image 1, Text, Image 2 defaults. 
           If we want Image 1, Image 2, Text or Text, Image 1, Image 2? 
           Default flow is fine: Image (Left), Text, Image (Right). 
        */
    }

    /* Specific styling for Music cards if needed */
    .link-card h3 {
        color: var(--google-yellow);
    }

    .page-title {
        color: var(--google-yellow);
    }

    .link-card {
        border-top: 3px solid var(--google-yellow);
    }
</style>

<?php include 'includes/footer.php'; ?>