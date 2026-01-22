<?php include 'includes/header.php'; ?>
<?php // Shell Index - content load via iframe ?>

<div class="top-bar">
  <div class="top-bar-left">
    <a href="/" class="home-icon">
      <img src="/Icons/NewHome.png" alt="Home" height="32" width="32">
      <span class="brand-name">Andrew DeFaria</span>
    </a>
  </div>

  <!-- Search Widget -->
  <div class="search-widget">
    <form method="get" action="https://www.google.com/search" name="search">
      <input type="hidden" name="domains" value="defaria.com">
      <input type="hidden" name="sitesearch" value="defaria.com">
      <input type="text" name="q" id="q" maxlength="255" placeholder="Search..." aria-label="Search">
    </form>
  </div>

  <div class="top-bar-right">
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
    <iframe id="content-frame" name="content-frame" src="personal.php"
      style="width: 100%; flex: 1; border: none; height: calc(100vh - 60px);"></iframe>
  </main>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const iframe = document.getElementById('content-frame');
    const tabs = document.querySelectorAll('.tab-btn');

    function activateTab(hash) {
      // Normalize hash (remove #)
      // Default to 'personal' if empty
      const route = hash ? hash.substring(1) : 'personal';

      // Map route to file
      const page = route + '.php';

      // Update Iframe
      // Check if we need to update src to avoid reload loops
      // We must check the ACTUAL content location, not just the stale src attribute.
      let currentPath = '';
      try {
        currentPath = iframe.contentWindow.location.pathname;
      } catch (e) {
        // Cross-origin or not loaded yet. Fallback to src.
        currentPath = iframe.src;
      }

      // If strict match fails, or if the content has drifted (e.g. navigation to contact.php)
      // Note: page is like 'personal.php'. pathname might be '/personal.php'
      if (!iframe.src.endsWith(page) || (currentPath && !currentPath.endsWith(page))) {
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