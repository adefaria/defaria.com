<?php include 'includes/header.php'; ?>
<?php // Shell Index - content load via iframe ?>

<div class="top-bar">
  <div class="top-bar-left">
    <a href="/#welcome" class="home-icon">
      <img src="/Icons/NewHome.png" alt="Home" height="32" width="32">
      <span class="brand-name">Andrew DeFaria</span>
    </a>
  </div>

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
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode">
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

    <!-- CheckAddress.js script -->
    <script src="/maps/JavaScript/CheckAddress.js" type="text/javascript"></script>

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
  <nav class="sidebar">
    <a href="#personal" class="tab-btn" id="tab-personal">Personal</a>
    <a href="#professional" class="tab-btn" id="tab-professional">Professional</a>
    <a href="#music" class="tab-btn" id="tab-music">Music</a>
    <a href="#projects" class="tab-btn" id="tab-projects">Projects</a>
    <?php if (isset($_GET['show_hidden']) && $_GET['show_hidden'] == '1'): ?>
      <a href="#hidden" class="tab-btn" id="tab-hidden">Hidden</a>
    <?php endif; ?>
  </nav>

  <!-- Main Content -->
  <main class="main-content" style="padding: 0; display: flex; flex-direction: column;">
    <iframe id="content-frame" name="content-frame" src="welcome.php"
      style="width: 100%; flex: 1; border: none;"></iframe>
  </main>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const iframe = document.getElementById('content-frame');
    const tabs = document.querySelectorAll('.tab-btn');
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = themeToggle.querySelector('.sun-icon');
    const moonIcon = themeToggle.querySelector('.moon-icon');

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
      return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
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

          // Also update footer date while we are here
          const meta = iframe.contentDocument.querySelector('meta[name="last-modified"]');
          if (meta) {
            const date = meta.getAttribute('content');
            const footerDate = document.getElementById('footer-mod-date');
            if (footerDate) {
              footerDate.textContent = 'This page was last modified: ' + date;
            }
          }
        }
      } catch (e) {
        // Ignore cross-origin
      }
    });

    function activateTab(hash) {
      // Normalize hash (remove #)
      // Default to 'welcome' if empty
      const route = hash ? hash.substring(1) : 'welcome';

      // Map route to file
      let page = route + '.php';

      // Special Routes
      if (route === 'resume') {
        page = 'resume/index.php';
      } else if (route === 'music') {
        page = 'music.php'; // or whatever implicit logic if consistent
      }

      // Update Iframe
      // Check if we need to update src to avoid reload loops
      // We must check the ACTUAL content location, not just the stale src attribute.
      let currentPath = '';
      let isCrossOrigin = false;
      try {
        currentPath = iframe.contentWindow.location.pathname;
      } catch (e) {
        // Cross-origin or not loaded yet.
        // If we have a cross-origin error, we are typically on an external site (like Google Maps)
        // So we definitely want to reload our local page.
        isCrossOrigin = true;
      }

      // If strict match fails, or if the content has drifted (e.g. navigation to contact.php)
      // OR if we are cross-origin (map view)
      if (isCrossOrigin || !iframe.src.endsWith(page) || (currentPath && !currentPath.endsWith(page))) {
        iframe.src = page;
      }

      // Update Active Tab UI
      tabs.forEach(tab => {
        const tabHash = tab.getAttribute('href').substring(1);
        if (tabHash === route) {
          tab.classList.add('active');
        } else {
          tab.classList.remove('active');
        }

        // Add Click Listener for Reset
        // Remove old listener if any to avoid duplicates? 
        // Better: do this outside activateTab or ensure idempotency. 
        // Doing it outside is better.
      });
    }

    // Handle Tab Clicks
    tabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
        const hash = tab.getAttribute('href');
        // If current hash matches clicked hash, force reload
        if (window.location.hash === hash) {
          e.preventDefault(); // Prevent default anchor jump
          activateTab(hash); // Retrieve default page
          // Force iframe src update even if endsWith matches?
          // activateTab uses check: if (!iframe.src.endsWith(page))
          // If we are on personal.php via contact.php click, src is contact.php on DOM?
          // If we are on contact.php: src="contact.php"
          // We click Personal (#personal) -> activateTab('#personal')
          // page = 'personal.php'
          // src ends with contact.php -> logic sets src = personal.php
          // So logic should work IF hashchange fires. 
          // If hash is SAME, hashchange doesn't fire.
          // So this click handler is NECESSARY.

          // One catch: activateTab relies on implicit check. 
          // If src ALREADY matches page (e.g. personal.php) and we want to RELOAD?
          // User said: "if I click on the Personal tab again I expect to get the personal cards displayed".
          // If we are on Contact, src is contact.php. activateTab will switch it.
          // So calling activateTab(hash) here works.

          // But wait, activateTab is defined above.
        }
      });
    });

    // Listen for hash changes
    window.addEventListener('hashchange', () => {
      activateTab(window.location.hash);
    });

    // Initial Load
    activateTab(window.location.hash);

    // Update Footer from Iframe
    iframe.addEventListener('load', () => {
      try {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        const meta = iframeDoc.querySelector('meta[name="last-modified"]');
        if (meta) {
          const date = meta.getAttribute('content');
          const footerDate = document.getElementById('footer-mod-date');
          if (footerDate) {
            footerDate.textContent = 'This page was last modified: ' + date;
          }
        }
      } catch (e) {
        console.log('Cross-origin iframe access restricted or other error.');
      }
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
</body>

</html>