<div class="top-bar">
    <div class="top-bar-left">
        <a href="/" class="home-icon">
            <img src="/Icons/NewHome.png" alt="Home" height="32" width="32">
        </a>
        <div class="brand-text-col">
            <a href="/" style="text-decoration: none;">
                <span class="brand-name">Andrew DeFaria</span>
            </a>
            <span class="brand-tagline">
                <a href="/professional" target="content-frame" id="link-computers"
                    style="display:inline-flex; align-items:center; gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="4 17 10 11 4 5"></polyline>
                        <line x1="12" y1="19" x2="20" y2="19"></line>
                    </svg>
                    Computers
                </a> /
                <a href="/music" id="link-music" style="display:inline-flex; align-items:center; gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                    Music
                </a> /
                <a href="/vette" target="content-frame" id="link-cars"
                    style="display:inline-flex; align-items:center; gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2">
                        </path>
                        <circle cx="7" cy="17" r="2"></circle>
                        <circle cx="17" cy="17" r="2"></circle>
                    </svg>
                    Cars
                </a>
            </span>
        </div>
    </div>

    <!-- Theme Toggle (Moved) -->
    <!-- Theme Toggle (Moved) -->
    <div id="theme-toggle-wrapper" style="display: flex; align-items: center;">
        <!-- Go to dark mode (Moon) - Visible in Light Mode -->
        <button id="btn-to-dark" aria-label="Switch to dark theme"
            class="nav-menu-item theme-toggle rounded-lg p-2 transition-colors active:scale-95 focus:outline-none"
            type="button" style="display:none;">🌙</button>

        <!-- Go to light mode (Sun) - Visible in Dark Mode -->
        <button id="btn-to-light" aria-label="Switch to light theme"
            class="nav-menu-item theme-toggle rounded-lg p-2 transition-colors active:scale-95 focus:outline-none"
            type="button" style="display:none;">☀️</button>
    </div>

    <!-- Search Widget -->
    <div class="search-widget">
        <form method="get" action="https://www.google.com/search" name="search">
            <input type="hidden" name="domains" value="defaria.com">
            <input type="hidden" name="sitesearch" value="defaria.com">
            <input type="text" name="q" id="q" maxlength="255" placeholder="Search my website..." aria-label="Search"
                onfocus="this.placeholder = ''; this.value = '';" onblur="this.placeholder = 'Search my website...'"
                onclick="this.value = '';">
        </form>
    </div>

    <div class="top-bar-right">
        <!-- Theme Toggle -->
        <!-- Theme Toggle -->

        <!-- CheckAddress.js script -->
        <script src="/maps/JavaScript/CheckAddress.js?v=<?php echo time(); ?>" type="text/javascript"></script>

        <!-- Email Form -->
        <form method="post" action="javascript:void(0);" name="address" onsubmit="return checkaddress(this, 'andrew');"
            class="email-form">
            <label for="email" class="email-label">Can you email me?</label>
            <input type="text" id="email" name="email" value="Type your email address and hit return"
                onfocus="if(this.value=='Type your email address and hit return') this.value='';"
                onblur="if(this.value=='') this.value='Type your email address and hit return';"
                class="email-input-box">
        </form>
    </div>
</div>

<div class="app-container">
    <!-- Sidebar -->
    <!-- Sidebar Wrapper for Mobile Arrows -->
    <div class="sidebar-wrapper">
        <button class="nav-arrow left-arrow" id="scroll-left" aria-label="Scroll Left">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <nav class="sidebar" id="sidebar-nav">
            <a href="/personal" class="tab-btn" id="tab-personal">Personal</a>
            <a href="/professional" class="tab-btn" id="tab-professional">Professional</a>
            <a href="/music" class="tab-btn" id="tab-music">Music</a>
            <a href="/projects" class="tab-btn" id="tab-projects">Projects</a>
            <a href="/blogs" class="tab-btn" id="tab-blogs">Blogs</a>
            <a href="/misc" class="tab-btn" id="tab-misc">Misc</a>
        </nav>
        <button class="nav-arrow right-arrow" id="scroll-right" aria-label="Scroll Right">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    </div>

    <!-- Main Content -->
    <main class="main-content" style="padding: 0; display: flex; flex-direction: column;">