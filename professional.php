<?php include 'includes/frame_header.php'; ?>
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

<main class="container">
    <h1 class="page-title">Professional</h1>
    <p class="page-description">This section focuses on my professional life. I am a Linux administrator who often
        writes in Perl and performs Build and Release and Devops role for corporation. ClearSCM was my old corporation
        where I focused on Clearcase and Clearquest. If you want to employ me check out my resume. Historical
        information about me and computers is an interesting read. MAPS is my home grown spam filter that I've been
        using since ~2001. CPAN Modules are coming and also Mobile MAPS - a mobile app for MAPS.</p>
    <div class="link-grid">
        <div class="link-card" onclick="window.open('/clearscm/index.php', '_self');">
            <h3>ClearSCM</h3>
            <p>Advanced Software Configuration Management systems and tools.</p>
        </div>

        <div class="link-card" onclick="window.open('/maps/index.php', '_self');">
            <h3>MAPS</h3>
            <p>Mail Authorization and Permission System - A homegrown spam solution.</p>
        </div>

        <div class="link-card" onclick="window.open('https://metacpan.org/author/ADEFARIA', '_blank');">
            <h3>
                CPAN Modules
                <svg class="external-icon"
                    style="width: 0.8em; height: 0.8em; vertical-align: middle; fill: currentColor; margin-left: 0.3em;"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7zm-2 16H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7z" />
                </svg>
            </h3>
            <p>Contributions to the Comprehensive Perl Archive Network.</p>
        </div>

        <div class="link-card" onclick="window.open('/resume/index.php', '_self');">
            <h3>Resume</h3>
            <p>My professional background and experience.</p>
        </div>

        <div class="link-card" onclick="window.open('/Computers/index.php', '_self');">
            <h3>Computers</h3>
            <p>My history of computers owned.</p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>