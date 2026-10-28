<?php $page_title = "Professional";
include 'includes/frame_header.php'; ?>
<style>
    /* Specific styling for Business cards */
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

<?php
$professional_links = [
    [
        'title' => 'ClearSCM',
        'url' => '/clearscm',
        'desc' => 'Advanced Software Configuration Management systems and tools.',
        'target' => '_top'
    ],
    [
        'title' => 'CPAN Modules',
        'url' => 'https://metacpan.org/author/DEFARIA',
        'desc' => 'Contributions to the Comprehensive Perl Archive Network.',
        'target' => '_blank'
    ],
    [
        'title' => 'Resume',
        'url' => '/resume',
        'desc' => 'My professional background and experience.',
        'target' => '_top'
    ],
    [
        'title' => 'Computers',
        'url' => '/computers',
        'desc' => 'My history of computers owned.',
        'target' => '_top'
    ],
    [
        'title' => 'MAPS',
        'url' => '/maps',
        'desc' => 'Mail Authorization and Permission System - A homegrown spam solution.',
        'target' => '_top'
    ],
    [
        'title' => 'MAPS Mobile',
        'url' => '/mapsmobile',
        'desc' => 'Mobile interface for the Mail Authorization and Permission System.',
        'target' => '_top'
    ],
    [
        'title' => 'Job Hunt',
        'url' => '/jobhunt',
        'desc' => 'Track and manage job opportunities, applications, and interview pipelines.',
        'target' => '_top',
        'requires_login' => true
    ],
    [
        'title' => 'Reach Out',
        'url' => '/reachout',
        'desc' => 'Professional networking, recruiter communications, and relationship management.',
        'target' => '_top',
        'requires_login' => true
    ],
];
?>

<main class="container">
    <h1 class="page-title">Professional</h1>
    <p class="page-description">This section focuses on my professional life. I am a Linux administrator who often
        focused on Clearcase and Clearquest. If you want to employ me check out my resume. Historical
        information about me and computers is an interesting read. MAPS is my homegrown spam filter that I've been
        using since ~2001.</p>
    <div class="link-grid">
        <?php foreach ($professional_links as $link): ?>
            <?php
            $target = $link['target'] ?? '_self';
            $is_external = ($target === '_blank');
            ?>
            <a href="<?php echo $link['url']; ?>" target="<?php echo $target; ?>" class="link-card"
                style="text-decoration: none; color: inherit; cursor: pointer;">
                <div>
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
                        <?php if (!empty($link['requires_login'])): ?>
                            <svg class="lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Requires Login" title="Requires Login">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                    </h3>
                    <p><?php echo $link['desc']; ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>