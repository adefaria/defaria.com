<?php
$page_title = "Contact";
include 'includes/frame_header.php';
?>

<main class="container">
  <h1 class="centered brand-name" style="text-align: center; margin-bottom: 2rem; color: var(--google-blue);">Contact
    Information</h1>

  <h2>Home</h2>
  <div class="info-grid" style="margin-bottom: 1rem;">
    <address>
      Andrew DeFaria<br>
      <a href="https://maps.google.com/maps?q=33.1349083,-117.2172925&output=embed" target="_blank">
        2010 West San Marcos Blvd, Unit 33<br>
        San Marcos, California 92078
      </a>
    </address>

    <div class="label-grid">
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

  <h2>Employer</h2>
  <div class="info-grid" style="margin-bottom: 1rem;">
    <b>Senior Perl Developer</b><br>
    <address>
      <a href="https://www.wolterskluwer.com/" target="_blank">
        <img src="/Images/WoltersKluwer.png" alt="Wolters Kluwer"
          style="height: 40px; vertical-align: middle;">
      </a><br>
      <a href="https://maps.google.com/maps?q=2700+Lake+Cook+Road,+Riverwoods,+IL+60015" target="_blank">
        2700 Lake Cook Road<br>
        Riverwoods, Illinois 60015
      </a>
    </address>

    <div class="label-grid">
      <span class="standout">Email:</span>
      <span><a href="mailto:Andrew.DeFaria@wolterskluwer.com">Andrew.DeFaria@wolterskluwer.com</a></span>

      <span class="standout">Phone:</span>
      <span><i>What's a phone? :-)</i></span>
    </div>
  </div>

  <h2>Other</h2>
  <div>
    <p><strong>Podcast OPML:</strong> <a href="/podcasts.opml">Podcasts.opml</a></p>
  </div>

  <h2>Social Networks</h2>
  <div>
    <p><i>"Facebook seems like an incredible waste of time"</i> - Betty White - 2010</p>
  </div>

</main>

<style>
  h2 {
    color: var(--google-blue);
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid var(--muted-color);
    padding-bottom: 0.4rem;
  }

  address {
    line-height: 1.4;
    font-style: normal;
  }

  .info-grid a {
    color: var(--google-blue);
  }

  .label-grid {
    display: grid;
    grid-template-columns: 130px 1fr;
    gap: 0.3rem 1rem;
    align-items: baseline;
  }

  .standout {
    font-weight: bold;
    color: var(--google-green);
  }
</style>

<?php include 'includes/footer.php'; ?>
