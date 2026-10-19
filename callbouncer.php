<?php
$page_title = "CallBouncer - Android Call Screener & Firewall";
include 'includes/frame_header.php';
?>

<style>
    :root {
        --project-color: var(--google-green);
    }

    .project-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .app-icon {
        width: 96px;
        height: 96px;
        border-radius: 22px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        object-fit: cover;
    }

    .header-text {
        flex: 1;
        min-width: 280px;
    }

    .header-text h1 {
        margin: 0 0 0.4rem 0;
        color: var(--project-color);
        font-family: var(--font-heading);
        font-size: 2.2rem;
    }

    .header-tagline {
        font-size: 1.15rem;
        color: var(--text-color);
        margin-bottom: 0.8rem;
    }

    .badge-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        background: rgba(52, 168, 83, 0.12);
        color: var(--project-color);
        border: 1px solid rgba(52, 168, 83, 0.25);
    }

    .feature-banner {
        width: 100%;
        max-width: 100%;
        border-radius: var(--radius-md);
        margin: 1.5rem 0 2.5rem 0;
        box-shadow: var(--shadow-lg);
        display: block;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .info-card {
        background-color: var(--surface-color);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-top: 3px solid var(--project-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .info-card h3 {
        color: var(--project-color);
        margin-top: 0;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }

    .info-card p {
        margin: 0;
        line-height: 1.6;
        font-size: 0.95rem;
        color: var(--text-color);
    }

    .screenshot-gallery {
        margin: 2.5rem 0;
    }

    .gallery-title {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .screenshots-row {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory;
    }

    .screenshot-frame {
        flex: 0 0 auto;
        width: 260px;
        scroll-snap-align: start;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.1);
        background: #000;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .screenshot-frame:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5);
    }

    .screenshot-frame img {
        width: 100%;
        height: auto;
        display: block;
    }

    .screenshot-caption {
        font-size: 0.85rem;
        color: var(--muted-color);
        text-align: center;
        padding: 0.6rem;
        background: var(--surface-color);
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
        margin-bottom: 2rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--project-color);
        color: #ffffff !important;
        font-weight: 600;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        transition: filter 0.2s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: rgba(255, 255, 255, 0.08);
        color: var(--text-color) !important;
        font-weight: 500;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .code-box {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius-md);
        padding: 1rem 1.25rem;
        font-family: monospace;
        font-size: 0.9rem;
        overflow-x: auto;
        margin-top: 0.8rem;
    }

    .back-nav {
        margin-bottom: 1.5rem;
    }

    .back-nav a {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--muted-color);
        font-weight: 500;
        text-decoration: none;
    }

    .back-nav a:hover {
        color: var(--project-color);
    }
</style>

<main class="container">
    <div class="back-nav">
        <a href="/projects" target="_top">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back to Projects
        </a>
    </div>

    <div class="project-header">
        <img src="/Images/callbouncer/icon_512x512.png" alt="CallBouncer App Icon" class="app-icon">
        <div class="header-text">
            <h1>CallBouncer</h1>
            <div class="header-tagline">
                Fast, 100% offline Android Call Screener &amp; personal call firewall.
            </div>
            <div class="badge-container">
                <span class="badge">Android 10+</span>
                <span class="badge">100% Offline</span>
                <span class="badge">Zero Network Permissions</span>
                <span class="badge">Wildcard &amp; RegEx</span>
                <span class="badge">Country Firewall</span>
            </div>
        </div>
    </div>

    <p style="font-size: 1.05rem; line-height: 1.7; margin-bottom: 1rem;">
        <strong>CallBouncer</strong> is a high-performance Android Call Screener and personal call firewall designed to 
        silence telemarketers, robocalls, neighbor-spoofing prefix clusters, and unwanted international calls before your phone ever rings.
    </p>

    <div class="btn-group">
        <a href="/downloads/CallBouncer-v1.5.1-release.apk" class="btn-primary" download>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Download Release APK (v1.5.1)
        </a>
        <a href="/privacy/callbouncer.html" class="btn-secondary" target="_blank">
            Privacy Policy
        </a>
    </div>

    <img src="/Images/callbouncer/feature_graphic_1024x500.png" alt="CallBouncer Feature Banner" class="feature-banner">

    <!-- Key Features Grid -->
    <h2 style="color: var(--project-color); margin-bottom: 1.25rem;">Key Capabilities</h2>
    <div class="card-grid">
        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                </svg>
                Wildcard &amp; RegEx Blocking
            </h3>
            <p>Block whole area codes, exchange prefixes, or nuisance clusters using simple wildcards like <code>(408) 596-*</code> or powerful Regular Expressions, keeping your blocklist compact and potent.</p>
        </div>

        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                International &amp; Country Firewall
            </h3>
            <p>Block all foreign spam calls instantly with a single "+1 Only" switch, or cherry-pick specific international dialing codes (such as +52 Mexico, +91 India, +44 UK, and 60+ countries) to reject automatically.</p>
        </div>

        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Contact Whitelist Protection
            </h3>
            <p>Never miss an important call from friends, family, doctors, or colleagues. Anyone in your Android Contacts is automatically whitelisted and bypasses all blocking rules.</p>
        </div>

        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                Sort by Most Bounced
            </h3>
            <p>Instantly sort your blocklist by the number of times each rule or number was triggered, identifying your most aggressive repeat offenders in real time.</p>
        </div>

        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                One-Tap CSV Import &amp; Export
            </h3>
            <p>Migrate existing massive blocklists (thousands of entries) in seconds with cluster detection that flags repeated prefix patterns for consolidation.</p>
        </div>

        <div class="info-card">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                100% Offline &amp; Zero Network Permissions
            </h3>
            <p>CallBouncer does not even request the Android <code>INTERNET</code> permission. Your call history, contacts, and firewall rules never leave your phone—zero cloud dependency, zero tracking.</p>
        </div>
    </div>

    <!-- Screenshots Section -->
    <div class="screenshot-gallery">
        <div class="gallery-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
            CallBouncer App Screenshots
        </div>
        <div class="screenshots-row">
            <div class="screenshot-frame">
                <img src="/Images/callbouncer/screenshot_1_calls_1080x1920.png" alt="Recent Calls &amp; Bounced Screener Log">
                <div class="screenshot-caption">Recent Calls &amp; Live Screener Log</div>
            </div>
            <div class="screenshot-frame">
                <img src="/Images/callbouncer/screenshot_2_rules_1080x1920.png" alt="Block Rules &amp; Pattern Configuration">
                <div class="screenshot-caption">Wildcard &amp; RegEx Rule Management</div>
            </div>
        </div>
    </div>

    <!-- Companion Tool Suite -->
    <div class="info-card" style="margin-top: 2rem;">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="4 17 10 11 4 5"></polyline>
                <line x1="12" y1="19" x2="20" y2="19"></line>
            </svg>
            Companion Desktop &amp; Terminal Tools
        </h3>
        <p>In addition to the Android app, the CallBouncer suite includes powerful desktop command-line and terminal tools for comprehensive blocklist management:</p>
        <ul style="margin: 0.8rem 0; padding-left: 1.5rem; line-height: 1.6; font-size: 0.95rem;">
            <li><strong>Interactive TUI:</strong> Visual terminal interface powered by Textual (<code>blocked ui</code>) with instant fuzzy searching and keyboard navigation.</li>
            <li><strong>Google Voice Extractor:</strong> Browser console automation script (<code>google_blocklist_extractor.js</code>) to dump blocked numbers directly from Google Account blocklists into CSV format.</li>
            <li><strong>Android ADB Integration:</strong> Inspect or synchronize Android's internal system blocked number provider over ADB.</li>
            <li><strong>SQLite Database:</strong> Normalized E.164 phone numbers with country code parsing, labels, and timestamps.</li>
        </ul>
        <div class="code-box">
# Interactive terminal UI
$ blocked ui

# Search blocked numbers by pattern or label
$ blocked search 555
$ blocked search "Telemarketer"

# Import or export rules directly
$ blocked import google_blocked_numbers.csv --source google_voice
$ blocked export rules.csv --format csv
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
