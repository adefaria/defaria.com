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
  <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode" style="margin-right: 1rem;">
    <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <line x1="12" y1="1" x2="12" y2="3"></line>
      <line x1="12" y1="21" x2="12" y2="23"></line>
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
      <line x1="1" y1="12" x2="3" y2="12"></line>
      <line x1="21" y1="12" x2="23" y2="12"></line>
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
    </svg>
    <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
    </svg>
  </button>

  <!-- Search Widget -->
  <div class="search-widget">
    <form method="get" action="https://www.google.com/search" name="search">
      <input type="hidden" name="domains" value="defaria.com">
      <input type="hidden" name="sitesearch" value="defaria.com">
      <input type="text" name="q" id="q" maxlength="255" placeholder="Search my website..." aria-label="Search">
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
        onblur="if(this.value=='') this.value='Type your email address and hit return';" class="email-input-box">
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

<!-- Shell Footer -->
<?php include 'includes/footer.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const iframe = document.getElementById('content-frame');
    const tabs = document.querySelectorAll('.tab-btn');
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = themeToggle.querySelector('.sun-icon');
    const moonIcon = themeToggle.querySelector('.moon-icon');

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

    // Cookie Helpers
    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
      const nameEQ = name + "=";
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    }

    // Theme Logic
    function getPreferredTheme() {
      const storedTheme = getCookie('theme'); // Read from Cookie
      if (storedTheme) {
        return storedTheme;
      }

      // Auto-switch based on time of day (7am - 7pm = light)
      const hour = new Date().getHours();
      if (hour >= 7 && hour < 19) {
        return 'light';
      }
      return 'dark'; // Default to dark at night
    }

    function setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      setCookie('theme', theme, 365); // Save to Cookie for 1 year

      // Update Icons
      // Standard: Sun shows in Dark Mode (to switch to Light). Moon shows in Light Mode (to switch to Dark).
      // Icon visibility logic:
      if (theme === 'light') {
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'block';
      } else {
        sunIcon.style.display = 'block';
        moonIcon.style.display = 'none';
      }

      // Sync Iframe
      try {
        if (iframe.contentDocument) {
          iframe.contentDocument.documentElement.setAttribute('data-theme', theme);
        }
      } catch (e) {
        console.log('Cannot access iframe content for theme sync');
      }
    }

    // Initial Theme Set
    setTheme(getPreferredTheme());

    // Toggle Handler
    themeToggle.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      setTheme(newTheme);
    });

    // Re-apply theme when iframe loads
    iframe.addEventListener('load', () => {
      const theme = document.documentElement.getAttribute('data-theme');
      try {
        if (iframe.contentDocument) {
          iframe.contentDocument.documentElement.setAttribute('data-theme', theme);
        }
      } catch (e) {
        // Ignore cross-origin
      }
    });

    window.activateTab = function (path) {
      // Normalize path (remove leading slash)
      let route = path.replace(/^\//, '');
      if (route === '' || route === 'index.php') route = 'welcome';

      // Strip trailing slash if present (unless it's just root)
      if (route.endsWith('/') && route.length > 1) {
        route = route.slice(0, -1);
      }

      // Map route to file
      let page = '/' + route + '.php';

      // Special Routes mappings
      if (route === 'welcome') {
        page = 'welcome.php';
      } else if (route === 'resume') {
        page = '/resume/index.php';
      } else if (route === 'music') {
        page = '/music.php';
      } else if (route === 'maps') {
        page = '/maps/php/main.php';
      } else if (route === 'mapsmobile') {
        page = '/maps/mobile/?bypass=true';
      } else if (route === 'clearscm') {
        page = '/clearscm/index.php';
      } else if (route === 'contact') {
        page = '/contact.php';
      } else if (route === 'addresses') {
        page = '/addresses.php';
      } else if (route === 'family') {
        page = '/Family/index.php';
      } else if (route === 'jokes') {
        page = '/Jokes/index.php';
      } else if (route === 'quotes') {
        page = '/libertarian.php';
      } else if (route === 'vette') {
        page = '/Vette/index.html';
      } else if (route === 'computers') {
        page = '/Computers/index.php';
      } else if (route === 'band' || route === 'cos') {
        page = '/Band/index.php';
      } else if (route === 'bottomsup') {
        page = '/Band/BottomsUp.php';
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
        page = '/upload.php';
      } else if (route === 'ytdownload') {
        page = '/yt/?bypass=true';
      } else if (route === 'spleeter') {
        page = 'https://spleeter.defariahome.com';
      } else if (route.toLowerCase() === 'rr') {
        page = '/rr/?bypass=true';
      } else if (route.toLowerCase() === 'rockready') {
        page = '/rockready/?bypass=true';
      } else if (route === 'webmonitor') {
        page = '/php/logviewer.php';
      } else if (route.toLowerCase() === 'tmp') {
        page = '/tmp/?bypass=true';
      } else {
        // Default logic: ensure absolute path
        if (!page.startsWith('/') && !page.startsWith('http')) {
          page = '/' + page;
        }
        // If route is just a directory name, maybe we should try that?
        // Fallback: use route as is if it looks like a path
        if (route.includes('/') && !route.endsWith('.php')) {
          page = '/' + route;
        }

        // AUTO-BYPASS: If it's a local path (starts with /) and doesn't explicitly have bypass, add it.
        // This solves the issue for ANY directory the user adds (e.g. /rr, /share, etc.) without manual updates.
        if (page.startsWith('/') && !page.includes('?bypass=true')) {
            page += (page.includes('?') ? '&' : '?') + 'bypass=true';
        }
      }

      // Fix for infinite recursion if route is effectively current page
      // But we handled 'tmp' specifically.

      // Update Iframe
      let currentPath = '';
      let isCrossOrigin = false;
      try {
        currentPath = iframe.contentWindow.location.pathname;
      } catch (e) {
        isCrossOrigin = true;
      }

      if (isCrossOrigin || !iframe.src.endsWith(page) || (currentPath && !currentPath.endsWith(page))) {
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
            if (['band', 'cos', 'bottomsup', 'bottomingout', 'arm'].includes(route)) isActive = true;
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
        activateTab(window.location.pathname);
      });
    });

    // Handle Back/Forward
    window.addEventListener('popstate', () => {
      activateTab(window.location.pathname);
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
      activateTab(window.location.pathname);
    }

    // Update Footer from Iframe & Sync Title
    iframe.addEventListener('load', () => {
      try {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        // Sync Title
        if (iframeDoc.title) {
          document.title = iframeDoc.title;
        }

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
        console.log('Cross-origin iframe access restricted or other error.');
      }
    });
  });
</script>

</body>

</html>