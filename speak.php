<?php
$page_title = "Speak - Android Text-to-Speech Assistant";
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

    .editions-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 900px) {
        .editions-container {
            grid-template-columns: 1fr;
        }
    }

    .edition-card {
        background-color: var(--surface-color);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-top: 4px solid var(--project-color);
        border-radius: var(--radius-md);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .edition-card h2 {
        color: var(--project-color);
        margin-top: 0;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .edition-card .edition-subtitle {
        color: var(--muted-color);
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
        font-weight: 500;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }

    .feature-list li {
        position: relative;
        padding-left: 1.6rem;
        margin-bottom: 0.8rem;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .feature-list li::before {
        content: "✓";
        position: absolute;
        left: 0;
        top: 0;
        color: var(--project-color);
        font-weight: bold;
    }

    .screenshot-gallery {
        margin: 2rem 0;
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
        width: 240px;
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
        font-size: 0.8rem;
        color: var(--muted-color);
        text-align: center;
        padding: 0.5rem;
        background: var(--surface-color);
    }

    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
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

    .comparison-section {
        background: var(--surface-color);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius-md);
        padding: 1.75rem;
        margin-bottom: 2.5rem;
    }

    .comparison-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .comparison-table th,
    .comparison-table td {
        padding: 0.8rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .comparison-table th {
        font-family: var(--font-heading);
        color: var(--project-color);
        font-size: 1.05rem;
    }

    .comparison-table td:nth-child(2),
    .comparison-table td:nth-child(3),
    .comparison-table th:nth-child(2),
    .comparison-table th:nth-child(3) {
        text-align: center;
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
        <img src="/Images/speak/pro/icon_512x512.png" alt="Speak App Icon" class="app-icon">
        <div class="header-text">
            <h1>Speak (Lite &amp; Pro)</h1>
            <div class="header-tagline">
                Smart hands-free notification and clipboard text-to-speech assistant for Android.
            </div>
            <div class="badge-container">
                <span class="badge">Android 10+</span>
                <span class="badge">100% Private</span>
                <span class="badge">Zero Ads</span>
                <span class="badge">Offline Engine / AI Voices</span>
            </div>
        </div>
    </div>

    <p style="font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
        Whether you are driving, working out, cooking, or just away from your phone screen, 
        <strong>Speak</strong> automatically reads aloud your incoming texts, emails, calendar events, and tasks without requiring you to pick up your phone. 
        It filters out the garbage—stripping ugly tracking numbers, URLs, and repetitive background alerts—leaving only the messages that matter.
    </p>

    <!-- Editions Side by Side -->
    <div class="editions-container">
        <!-- Speak Lite -->
        <div class="edition-card">
            <div>
                <h2>
                    <img src="/Images/speak/lite/icon_512x512.png" alt="Speak Lite Icon" style="width: 32px; height: 32px; border-radius: 8px;">
                    Speak Lite
                </h2>
                <div class="edition-subtitle">Free • 100% Offline • Built-in System TTS</div>
                <p>Designed for lightweight simplicity and battery efficiency, Speak Lite uses your device's built-in Android Text-to-Speech engine without requiring any internet access or server setup.</p>
                <ul class="feature-list">
                    <li><strong>Hands-Free Notification Announcements:</strong> Automatically reads incoming SMS, Gmail, calendar alerts, and Google Tasks.</li>
                    <li><strong>Smart Content Cleaning:</strong> Automatically removes messy URLs, FedEx/UPS tracking codes, and status updates.</li>
                    <li><strong>Natural Reaction Formatting:</strong> Translates emoji tapback notifications into natural spoken sentences (e.g., <em>"Sarah liked your message"</em>).</li>
                    <li><strong>Quiet Night Hours:</strong> Automatically silences non-urgent Google Tasks between 10 PM and 7 AM.</li>
                    <li><strong>Instant Screen &amp; Headset Silence:</strong> Turn your screen on/off or press your headset button to instantly mute active speech.</li>
                    <li><strong>Clipboard &amp; Text Sandbox:</strong> Copy text to your clipboard or type custom text to hear it spoken aloud with one tap.</li>
                    <li><strong>Searchable Speech History:</strong> Search, inspect, and replay previously spoken notifications anytime.</li>
                </ul>
            </div>
            <div class="btn-group">
                <a href="/downloads/SpeakLite-v1.4-release.apk" class="btn-primary" download>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download Lite APK (v1.4)
                </a>
                <a href="/privacy/speak-lite.html" class="btn-secondary" target="_blank">
                    Privacy Policy
                </a>
            </div>
        </div>

        <!-- Speak Pro -->
        <div class="edition-card">
            <div>
                <h2>
                    <img src="/Images/speak/pro/icon_512x512.png" alt="Speak Pro Icon" style="width: 32px; height: 32px; border-radius: 8px;">
                    Speak Pro
                </h2>
                <div class="edition-subtitle">Advanced • AI Neural Voices • Personas &amp; Chat Speak</div>
                <p>Speak Pro elevates your hands-free notification experience with cutting-edge AI voice synthesis (via F5-TTS), custom sender personas, and conversational chat-speak expansion.</p>
                <ul class="feature-list">
                    <li><strong>All Speak Lite Features:</strong> Full hands-free notifications, smart filtering, quiet hours, and instant mute controls.</li>
                    <li><strong>Custom AI &amp; Contact Personas:</strong> Connect to an F5-TTS synthesis server to assign unique, natural voice personas to individual contacts.</li>
                    <li><strong>Automatic Gender Matching:</strong> Intelligently detects sender first names on incoming messages and switches dynamically between preferred male and female voices.</li>
                    <li><strong>Smart Chat-Speak Expansion:</strong> Automatically expands SMS abbreviations (<em>"lol", "tbh", "omw", "idk", "btw"</em>) into natural spoken phrases with a fully customizable dictionary.</li>
                    <li><strong>Voice Persona Library:</strong> Browse and test custom synthesized voice profiles for your family, friends, and colleagues.</li>
                    <li><strong>Detailed History Metadata:</strong> Review speech history logs annotated with the specific persona and synthesis engine used.</li>
                    <li><strong>Privacy First:</strong> No ads, no third-party tracking, and completely user-controlled server endpoints.</li>
                </ul>
            </div>
            <div class="btn-group">
                <a href="/downloads/SpeakPro-v1.4-release.apk" class="btn-primary" download>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download Pro APK (v1.4)
                </a>
                <a href="/privacy/speak.html" class="btn-secondary" target="_blank">
                    Privacy Policy
                </a>
            </div>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="comparison-section">
        <h2 style="color: var(--project-color); margin-top: 0;">Feature Comparison</h2>
        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Capability</th>
                    <th>Speak Lite</th>
                    <th>Speak Pro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Hands-free Notification Reading (SMS, Email, Tasks, Calendar)</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Smart Content Cleaning (URLs, tracking numbers, status spam)</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Emoji Reaction &amp; Tapback Translation</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Quiet Night Hours for Task Notifications</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Instant Silence via Screen Toggle or Headset Button</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Searchable Notification History Log</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Clipboard &amp; Custom Text Sandbox</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>100% Offline System TTS Engine</td>
                    <td>✓</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>F5-TTS AI Neural Voice Server Support</td>
                    <td>—</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Automatic Sender Gender Voice Matching</td>
                    <td>—</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Contact-Specific Voice Personas</td>
                    <td>—</td>
                    <td>✓</td>
                </tr>
                <tr>
                    <td>Chat-Speak Acronym Expansion (Configurable Dictionary)</td>
                    <td>—</td>
                    <td>✓</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Screenshots Section -->
    <div class="screenshot-gallery">
        <div class="gallery-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
            Speak Lite Screenshots
        </div>
        <img src="/Images/speak/lite/feature_graphic_1024x500.png" alt="Speak Lite Feature Banner" class="feature-banner">
        <div class="screenshots-row">
            <div class="screenshot-frame">
                <img src="/Images/speak/lite/screenshot_1_main_1080x1920.png" alt="Speak Lite Main Controls">
                <div class="screenshot-caption">Main Controls &amp; Settings</div>
            </div>
            <div class="screenshot-frame">
                <img src="/Images/speak/lite/screenshot_2_history_1080x1920.png" alt="Speak Lite Speech History">
                <div class="screenshot-caption">Searchable Speech History</div>
            </div>
        </div>
    </div>

    <div class="screenshot-gallery" style="margin-top: 3rem;">
        <div class="gallery-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
            Speak Pro Screenshots
        </div>
        <img src="/Images/speak/pro/feature_graphic_1024x500.png" alt="Speak Pro Feature Banner" class="feature-banner">
        <div class="screenshots-row">
            <div class="screenshot-frame">
                <img src="/Images/speak/pro/screenshot_1_main_1080x1920.png" alt="Speak Pro Main Settings & Personas">
                <div class="screenshot-caption">AI Personas &amp; Voice Config</div>
            </div>
            <div class="screenshot-frame">
                <img src="/Images/speak/pro/screenshot_2_history_1080x1920.png" alt="Speak Pro History & Persona Badges">
                <div class="screenshot-caption">Speech History with Persona Metadata</div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
