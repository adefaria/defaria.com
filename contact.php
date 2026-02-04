<?php
$page_title = "Contact";
include 'includes/frame_header.php';
?>

<main class="container">
  <h1 class="centered brand-name" style="text-align: center; margin-bottom: 2rem; color: var(--google-blue);">Contact
    Information</h1>

  <h2>Home</h2>
  <div class="info-grid" style="margin-bottom: 2rem;">
    <address style="margin-bottom: 1rem;">
      Andrew DeFaria<br>
      <a href="https://maps.google.com/maps?q=33.1349083,-117.2172925&output=embed" target="_blank">
        2010 West San Marcos Blvd, Unit 33<br>
        San Marcos, California 92078
      </a>
    </address>

    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem 1rem; align-items: baseline;">
      <span class="standout">Phone:</span>
      <span><a href="tel:+14085964937">(408) 596-4937</a></span>

      <span class="standout">Email:</span>
      <span><a href="mailto:Andrew@DeFaria.com">Andrew@DeFaria.com</a></span>

      <span class="standout">Alternate Email:</span>
      <span><a href="mailto:adefaria@gmail.com">adefaria@gmail.com</a></span>

      <span class="standout">Website:</span>
      <span><a href="/" target="_top">https://defaria.com</a></span>
    </div>
  </div>

  <h2>Current Employer</h2>
  <div class="info-grid" style="margin-bottom: 2rem;">
    <address style="margin-bottom: 1rem;">
      <a href="https://cpanel.net/" target="_blank">
        <img src="/Images/cPanel.png" alt="cPanel" class="cpanel-logo"
          style="background: white; padding: 5px; border-radius: 4px; vertical-align: middle; margin-right: 10px;">
        cPanel LLC
      </a><br>
      2550 North Loop W., Suite 4006<br>
      Houston, Texas 77092
    </address>

    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem 1rem; align-items: baseline;">
      <span class="standout">Email:</span>
      <span><a href="mailto:Andrew.DeFaria@webpros.com">Andrew.DeFaria@webpros.com</a></span>

      <span class="standout">Phone:</span>
      <span><i>What's a phone? :-)</i></span>
    </div>
  </div>

  <h2>Other</h2>
  <div class="info-grid">
    <p><strong>Podcast OPML:</strong> <a href="/podcasts.opml">Podcasts.opml</a></p>
  </div>

  <h2>Social Networks</h2>
  <div class="info-grid">
    <p>I guess I succumbed - I don't know why - I'll probably regret this...</p>
    <p>
      <a href="https://plus.google.com/105126312267915112280" target="_blank">Google+</a>
    </p>
    <p><i>"Facebook seems like an incredible waste of time"</i> - Betty White - 2010</p>
  </div>

</main>

<style>
  h2 {
    color: var(--google-blue);
    margin-top: 2rem;
    border-bottom: 1px solid var(--muted-color);
    padding-bottom: 0.5rem;
  }

  .info-grid a {
    color: var(--google-blue);
  }

  .standout {
    font-weight: bold;
    color: var(--google-green);
  }
</style>

<?php include 'includes/footer.php'; ?>