<?php include 'includes/header.php'; ?>
<?php // Shell Index - content load via iframe ?>

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
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="red"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        onblur="if(this.value=='') this.value='Type your email address and hit return';" class="email-input-box"
        data-bwignore="true" autocomplete="off">
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
    <iframe id="content-frame" name="content-frame" src="welcome.php"
      style="width: 100%; flex: 1; border: none;"></iframe>
  </main>
</div>

<!-- Shell Footer include moved to EOF -->

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const iframe = document.getElementById('content-frame');
    const tabs = document.querySelectorAll('.tab-btn');
    // const themeToggle = document.getElementById('theme-toggle'); // No longer used directly
    // const sunIcon = themeToggle.querySelector('.sun-icon');
    // const moonIcon = themeToggle.querySelector('.moon-icon');

    // Mobile Sidebar Scrolling
    const sidebar = document.getElementById('sidebar-nav');
    const scrollLeftBtn = document.getElementById('scroll-left');
    const scrollRightBtn = document.getElementById('scroll-right');

    if (sidebar && scrollLeftBtn && scrollRightBtn) {
      scrollLeftBtn.addEventListener('click', () => {
        sidebar.scrollBy({
          left: -100,
          behavior: 'smooth'
        });
      });

      scrollRightBtn.addEventListener('click', () => {
        sidebar.scrollBy({
          left: 100,
          behavior: 'smooth'
        });
      });
    }

    // Theme Storage Helpers
    // Use localStorage as primary (not blocked by Brave Shields).
    // Also write a cookie as a fallback for PHP server-side reads.
    function saveTheme(theme) {
      try { localStorage.setItem('user_theme', theme); } catch(e) {}
      try {
        const date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        document.cookie = 'user_theme=' + theme + '; expires=' + date.toUTCString() + '; path=/';
      } catch(e) {}
    }

    function getStoredTheme() {
      try {
        const ls = localStorage.getItem('user_theme');
        if (ls) return ls;
      } catch(e) {}
      // Fallback: read from cookie
      try {
        const match = document.cookie.match(/(^| )user_theme=([^;]+)/);
        if (match) return match[2];
      } catch(e) {}
      return null;
    }

    // Theme Logic
    function getPreferredTheme() {
      const storedTheme = getStoredTheme();
      if (storedTheme) return storedTheme;
      // Fall back to system preference
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
      }
      return 'light';
    }

    const btnToDark = document.getElementById('btn-to-dark');
    const btnToLight = document.getElementById('btn-to-light');

    function setTheme(theme, save = false) {
      document.documentElement.setAttribute('data-theme', theme);
      if (save) {
        saveTheme(theme);
      }

      // Update Buttons Visibility
      if (theme === 'light') {
        // In Light mode, we want to go to Dark (Moon)
        if (btnToDark) btnToDark.style.display = 'block';
        if (btnToLight) btnToLight.style.display = 'none';
      } else {
        // In Dark mode, we want to go to Light (Sun)
        if (btnToDark) btnToDark.style.display = 'none';
        if (btnToLight) btnToLight.style.display = 'block';
      }

      // Sync Iframe using postMessage to bypass CORS
      if (iframe && iframe.contentWindow) {
        iframe.contentWindow.postMessage({ type: 'themeChange', theme: theme }, '*');
      }
    }

    // Initial Theme Set
    setTheme(getPreferredTheme());

    // Listen for system theme changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        // Only auto-switch if the user hasn't set an explicit cookie preference
        if (!getCookie('user_theme')) {
          setTheme(e.matches ? 'dark' : 'light', false);
        }
      });
    }

    // Toggle Handler
    if (btnToDark) {
      btnToDark.addEventListener('click', () => {
        setTheme('dark', true);
      });
    }

    if (btnToLight) {
      btnToLight.addEventListener('click', () => {
        setTheme('light', true);
      });
    }

    // Re-apply theme when iframe loads (use postMessage to avoid CORS issues)
    iframe.addEventListener('load', () => {
      const theme = document.documentElement.getAttribute('data-theme');
      if (iframe.contentWindow) {
        iframe.contentWindow.postMessage({ type: 'themeChange', theme: theme }, '*');
      }
    });

    window.activateTab = function (path) {
      // Split path and query string
      let [routePath, queryString] = path.split('?');
      queryString = queryString ? '?' + queryString : '';

      // Normalize path (remove leading slash)
      let route = routePath.replace(/^\//, '');
      if (route === '' || route === 'index.php') route = 'welcome';

      // Strip trailing slash if present (unless it's just root)
      if (route.endsWith('/') && route.length > 1) {
        route = route.slice(0, -1);
      }

      // Map route to file
      let page = '/' + route + '.php' + queryString;

      // Special Routes mappings
      if (route === 'welcome') {
        page = 'welcome.php' + queryString;
      } else if (route === 'resume') {
        page = '/resume/index.php' + queryString;
      } else if (route === 'music') {
        page = '/music.php' + queryString;
      } else if (route === 'personal') {
        page = '/personal.php' + queryString;
      } else if (route === 'professional') {
        page = '/professional.php' + queryString;
      } else if (route === 'projects') {
        page = '/projects.php' + queryString;
      } else if (route === 'blogs') {
        page = '/blogs.php' + queryString;
      } else if (route === 'misc') {
        page = '/misc.php' + queryString;
      } else if (route === 'songbook' || route === 'songs') {
        page = '/songbook/index.php' + queryString;
      } else if (route.startsWith('songs/')) {
        // Deep link to specific song: /songs/webchord.cgi?chordpro=Song.pro
        const songPath = route.substring(6); // Remove 'songs/' prefix
        page = '/songbook/' + songPath + queryString;
      } else if (route.startsWith('sets/')) {
        // Deep link to set: /sets/SetName -> /songbook/displayset.php?set=SetName.lst
        const setName = route.substring(5);
        let setParam = setName;
        if (!setParam.toLowerCase().endsWith('.lst')) {
          setParam += '.lst';
        }
        page = '/songbook/displayset.php?set=' + setParam + (queryString ? '&' + queryString.substring(1) : '');
      } else if (route === 'maps') {
        page = '/maps/php/main.php' + queryString + (queryString ? '&' : '?') + 'cb=' + new Date().getTime();
      } else if (route === 'mapsmobile') {
        page = '/maps/mobile/?bypass=true&cb=' + new Date().getTime(); // Has its own query params
      } else if (route === 'clearscm') {
        page = '/clearscm/index.php' + queryString;
      } else if (route === 'contact') {
        page = '/contact.php' + queryString;
      } else if (route === 'addresses') {
        page = '/addresses.php' + queryString;
      } else if (route === 'family') {
        page = '/Family/index.php' + queryString;
      } else if (route === 'jokes') {
        page = '/Jokes/index.php' + queryString;
      } else if (route === 'quotes') {
        page = '/libertarian.php' + queryString;
      } else if (route === 'vette') {
        page = '/Vette/index.html' + queryString;
      } else if (route === 'computers') {
        page = '/Computers/index.php' + queryString;
      } else if (route === 'band' || route === 'cos') {
        page = '/Band/index.php' + queryString;
      } else if (route === 'bottomsup') {
        page = '/Band/BottomsUp.php' + queryString;
      } else if (route === 'bottomingout') {
        page = '/Media/Bottoming%20Out%20in%20Monterey/?bypass=true';
      } else if (route === 'arm') {
        page = '/Media/ARM/?bypass=true';
      } else if (route.toLowerCase() === 'broadcom') {
        page = '/Broadcom/?bypass=true';
      } else if (route.toLowerCase() === 'ibm') {
        page = '/IBM/?bypass=true';
      } else if (route.toLowerCase() === 'sanmelia') {
        page = '/SanMelia/?bypass=true';
      } else if (route.toLowerCase() === 'wellsfargo') {
        page = '/wellsfargo/?bypass=true';
      } else if (route === 'upload') {
        page = '/upload.php' + queryString;
      } else if (route === 'ytdownload') {
        page = '/yt/?bypass=true';
      } else if (route === 'spleeter') {
        page = 'https://spleeter.defariahome.com';
      } else if (route.toLowerCase() === 'rr') {
        page = '/rr/?bypass=true';
      } else if (route.toLowerCase() === 'rockready') {
        page = '/rockready/?bypass=true';
      } else if (route === 'webmonitor') {
        page = '/php/logviewer.php' + queryString;
      } else if (route.toLowerCase() === 'tmp') {
        page = '/tmp/?bypass=true';
      } else {
        // Default logic: ensure absolute path
        page = '/' + route;
        if (!page.startsWith('/') && !page.startsWith('http')) {
          page = '/' + page;
        }

        // Append query string if not already present in page (some pages above have hardcoded params)
        if (queryString && !page.includes('?')) {
          page += queryString;
        } else if (queryString && page.includes('?')) {
          page += '&' + queryString.substring(1);
        }

        // AUTO-BYPASS: If it's a local path (starts with /) and doesn't explicitly have bypass, add it.
        // This solves the issue for ANY directory the user adds (e.g. /rr, /share, etc.) without manual updates.
        if (page.startsWith('/') && !page.includes('?bypass=true') && !page.includes('&bypass=true')) {
          page += (page.includes('?') ? '&' : '?') + 'bypass=true';
        }
      }

      // Fix for infinite recursion if route is effectively current page
      // But we handled 'tmp' specifically.

      // Update Iframe
      let currentPath = '';
      let isCrossOrigin = false;
      try {
        currentPath = iframe.contentWindow.location.pathname + iframe.contentWindow.location.search;
      } catch (e) {
        isCrossOrigin = true;
      }

      // Check if we need to update src
      // If page has query params, we must match them.
      // Simplest check: does logic require update?
      // We can just set it?
      // To avoid reloading same page: check logic.

      // Let's use a simplified check:
      // If the desired page is different from current src, update.
      // Note: iframe.src might be full URL. page is relative.

      const absolutePage = page.startsWith('http') ? page : window.location.origin + page;

      if (isCrossOrigin || iframe.src !== absolutePage) {
        // Also check contentWindow location to avoid reloading if user navigated nicely inside
        // But for deep linking, we want to force it.
        iframe.src = page;
      }

      // Update Active Tab UI
      tabs.forEach(tab => {
        // Tab Hrefs should now be Clean paths e.g. /personal
        const tabPath = tab.getAttribute('href').replace(/^\//, '');

        // Match logic
        let isActive = false;
        if (tabPath === route) {
          isActive = true;
        } else {
          // Child mappings
          if (tabPath === 'professional') {
            if (['resume', 'clearscm', 'maps', 'computers'].includes(route)) isActive = true;
          } else if (tabPath === 'personal') {
            if (['contact', 'addresses', 'family', 'jokes', 'quotes', 'vette'].includes(route)) isActive = true;
          } else if (tabPath === 'music') {
            if (['band', 'cos', 'bottomsup', 'bottomingout', 'arm', 'songbook', 'songs'].includes(route.split('/')[0])) isActive = true;
          } else if (tabPath === 'projects') {
            if (['upload', 'ytdownload', 'spleeter'].includes(route)) isActive = true;
          } else if (tabPath === 'misc') {
            if (['broadcom', 'ibm', 'sanmelia', 'wellsfargo', 'webmonitor'].includes(route)) isActive = true;
          }
        }

        if (isActive) tab.classList.add('active');
        else tab.classList.remove('active');
      });
    }

    // Handle Sidebar Tab Clicks
    // With clean URLs, we can just let the link navigate?
    // User wants "single page feel" maybe?
    // If we navigate, the whole page reloads = Flash.
    // Ideally we use pushState.

    tabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
        e.preventDefault();
        const href = tab.getAttribute('href');
        window.history.pushState(null, '', href);
        activateTab(window.location.pathname + window.location.search);
      });
    });

    // Handle Back/Forward
    window.addEventListener('popstate', () => {
      activateTab(window.location.pathname + window.location.search);
    });

    // Tagline and other Internal Link handling
    // We need to catch clicks on internal links to avoid full reload
    // Attach a global listener? Or specific ones.

    // Tagline Links
    const linkComputers = document.getElementById('link-computers');
    const linkCars = document.getElementById('link-cars');
    const linkMusic = document.getElementById('link-music');

    if (linkComputers) {
      linkComputers.addEventListener('click', (e) => {
        e.preventDefault();
        window.history.pushState(null, '', '/professional');
        activateTab('/professional');
      });
    }

    if (linkCars) {
      linkCars.addEventListener('click', (e) => {
        e.preventDefault();
        window.history.pushState(null, '', '/vette'); // Or personal? Link says Cars -> Vette
        activateTab('/vette');
      });
    }

    if (linkMusic) {
      linkMusic.addEventListener('click', (e) => {
        e.preventDefault();
        window.history.pushState(null, '', '/music');
        activateTab('/music');
      });
    }

    // Initial Load
    // Check for hash fallback (e.g. from cached redirects or bookmarks)
    if (window.location.pathname === '/' && window.location.hash) {
      const hashRoute = window.location.hash.substring(1);
      activateTab('/' + hashRoute);
      // Optional: Clean the URL
      history.replaceState(null, '', '/' + hashRoute);
    } else {
      activateTab(window.location.pathname + window.location.search);
    }

    // Favicon Logic
    function updateFavicon(path) {
      let iconPath = '/Icons/Home.ico';
      let type = 'image/x-icon';

      if (path.includes('/songbook/') || path.includes('/music') || path.includes('songs/')) {
        iconPath = '/songbook/Music.ico';
      } else if (path.includes('/maps/')) {
        iconPath = '/maps/MAPS.png';
        type = 'image/png';
      }

      // Update standard icon
      let link = document.querySelector("link[rel~='icon']");
      if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.head.appendChild(link);
      }
      link.type = type;
      link.href = iconPath + '?v=' + new Date().getTime();

      // Update shortcut icon if it exists (for some browsers)
      let shortcutLink = document.querySelector("link[rel~='shortcut']");
      if (shortcutLink) {
        shortcutLink.type = type;
        shortcutLink.href = iconPath + '?v=' + new Date().getTime();
      }
    }

    // Update Footer from Iframe & Sync Title & Favicon
    iframe.addEventListener('load', () => {
      try {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        const currentPath = iframe.contentWindow.location.pathname;

        // Sync Title
        if (iframeDoc.title) {
          document.title = iframeDoc.title;
        }

        // Update Favicon
        updateFavicon(currentPath);

        const meta = iframeDoc.querySelector('meta[name="last-modified"]');
        if (meta) {
          const date = meta.getAttribute('content');
          const footerDate = document.getElementById('footer-mod-date');
          if (footerDate) {
            let title = document.title.replace(' - Andrew DeFaria', '');
            if (title === 'Andrew DeFaria') title = 'Welcome';
            footerDate.textContent = title + ': Last modified ' + date;
          }
        }
      } catch (e) {
        // Fallback for cross-origin or other errors
        if (iframe.src) {
          updateFavicon(iframe.src);
        }
      }
    });
  });
</script>

<!-- Shell Footer -->
<?php include 'includes/footer.php'; ?>