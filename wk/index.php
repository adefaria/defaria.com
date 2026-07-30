<?php $page_title = "Wolters Kluwer";
include '../includes/frame_header.php'; ?>

<script>
    if (window === window.top) {
        const currentUrl = window.location.pathname + window.location.search;
        window.location.href = '/?url=' + encodeURIComponent(currentUrl);
    }
</script>

<style>
    /* Specific styling for Wolters Kluwer cards */
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

<main class="container">
    <h1 class="page-title">Wolters Kluwer</h1>
    <p class="page-description">A collection of internal logs, experiences, and technical articles from my time at Wolters Kluwer.</p>
    <div class="link-grid">
        <a href="Wolters_Kluwer.html" class="link-card">
            <h3>1. Wolters Kluwer</h3>
            <p>April 13, 2026</p>
        </a>
        <a href="Missing_Perl.html" class="link-card">
            <h3>2. Missing Perl</h3>
            <p>April 27, 2026</p>
        </a>
        <a href="Contingent_worker___Code_Games.html" class="link-card">
            <h3>3. Contingent worker - Code Games</h3>
            <p>April 28, 2026</p>
        </a>
        <a href="The_LEO_Paradox.html" class="link-card">
            <h3>4. The LEO Paradox</h3>
            <p>April 30, 2026</p>
        </a>
        <a href="One_Shot_to_Sync.html" class="link-card">
            <h3>5. One Shot to Sync</h3>
            <p>May 5, 2026</p>
        </a>
        <a href="The_5_AM_Contract.html" class="link-card">
            <h3>6. The 5 AM Contract</h3>
            <p>May 5, 2026</p>
        </a>
        <a href="More_security_training.html" class="link-card">
            <h3>7. More security training</h3>
            <p>May 6, 2026</p>
        </a>
        <a href="Pitfalls_of_Global_Settings_Caching.html" class="link-card">
            <h3>8. Pitfalls of Global Settings Caching</h3>
            <p>May 13, 2026</p>
        </a>
        <a href="Technical_Debt.html" class="link-card">
            <h3>9. Technical Debt</h3>
            <p>May 26, 2026</p>
        </a>
        <a href="Space_aged_coding.html" class="link-card">
            <h3>10. Space aged coding</h3>
            <p>May 27, 2026</p>
        </a>
        <a href="Communication_Style.html" class="link-card">
            <h3>11. Communication Style</h3>
            <p>May 27, 2026</p>
        </a>
        <a href="Broke_in_Production.html" class="link-card">
            <h3>12. Broke in Production</h3>
            <p>June 15, 2026</p>
        </a>
        <a href="Your_Dev_Environment_Is_Lying_To_You__And_Your_Tea.html" class="link-card">
            <h3>13. Your Dev Environment Is Lying To You (And Your Team Doesn't Care)</h3>
            <p>June 17, 2026</p>
        </a>
        <a href="The_Windows_Docker_Dilemma__Licenses___Free_Open_S.html" class="link-card">
            <h3>14. The Windows Docker Dilemma: Licenses & Free Open-Source Alternatives</h3>
            <p>June 23, 2026</p>
        </a>
        <a href="Deployment_focused_vs_develop_focus.html" class="link-card">
            <h3>15. Deployment focused vs develop focus</h3>
            <p>June 24, 2026</p>
        </a>
        <a href="More_on_Corporate_VPNs_and_Software_locks.html" class="link-card">
            <h3>16. More on Corporate VPNs and Software locks</h3>
            <p>July 6, 2026</p>
        </a>
        <a href="Umasking_Security_Theater.html" class="link-card">
            <h3>17. Umasking Security Theater</h3>
            <p>July 10, 2026</p>
        </a>
        <a href="How_to_waste_time_chasing_ghosts.html" class="link-card">
            <h3>18. How to waste time chasing ghosts</h3>
            <p>July 10, 2026</p>
        </a>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
