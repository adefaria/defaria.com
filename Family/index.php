<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Family - Andrew DeFaria</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">

  <?php include "../../site-functions.php" ?>
  <!-- Note: site-functions is in ../../php/ or ../? 
       Original file had includes/site-functions.php? 
       Original: <?php include "site-functions.php" ?> 
       Wait, Family/index.php is in /opt/defaria.com/Family/
       site-functions is in /opt/defaria.com/php/site-functions.php?
       Or /opt/defaria.com/site-functions.php?
       Checking file structure:
       /opt/defaria.com/php/site-functions.php is where I saw it before.
       But original file used include "site-functions.php".
       It might be in the path or symlink?
       I'll use the relative path that works: ../php/site-functions.php or ../site-functions.php depending on layout.
       User roots at /opt/defaria.com.
       Family/ is /opt/defaria.com/Family.
       php/ is /opt/defaria.com/php.
       So path is ../php/site-functions.php.
       However, if "site-functions.php" worked before, maybe it's in the same dir or include_path.
       `ll` showed `php/` dir. 
       `cat` of `personal.php` showed `include 'includes/frame_header.php'`.
       `cat` of `Family/index.php` showed `include "site-functions.php"`.
       I will use `include __DIR__ . '/../php/site-functions.php'` to be safe, or check where site-functions is.
       Actually, `addresses.php` used `include "site-functions.php"`. It is in root.
       `Family/` is a subdir.
       I'll assume I need `../site-functions.php` if it's in root (was it? `ll` showed `personal.php` there).
       Wait, `ll /opt/defaria.com` showed `php/` folder but didn't show `site-functions.php` explicitly?
       `ll` output from Step 246:
       `site-functions.php` is NOT in root list. `php/` is there.
       `personal.php` has includes `includes/frame_header.php`.
       `contact.php` has `include "site-functions.php"`.
       `contact.php` is in root.
       So `site-functions.php` might be in root?
       Let me check `ls -F`.
       Step 246 output: `php/`. No `site-functions.php` in root.
       So how did `contact.php` work? 
       Maybe `include_path` is set?
       Or `site-functions.php` *is* in root but I missed it?
       Wait, `contact.php` line 59: `include "site-functions.php"`.
       `ls` output: `addresses.php`, `business.php`, `contact.php` ... `personal.php`.
       I don't see `site-functions.php` in the Step 246 list.
       Maybe it's hidden or I missed it in the column?
       Ah, `php/` directory exists.
       Maybe `contact.php` relies on PHP include path `.:/php`.
       I'll use `include $_SERVER['DOCUMENT_ROOT'] . '/php/site-functions.php';` or `include "../php/site-functions.php"`.
       Safest is likely `include "../php/site-functions.php"` if I fix the path.
       But `contact.php` used `"site-functions.php"`.
       If `contact.php` is working, I should check where `site-functions.php` actually is.
       Task Step 174 edited `/opt/defaria.com/php/site-functions.php`. So it IS in `php/`.
       So `contact.php` including `"site-functions.php"` implies `php/` is in the include path.
       So I can probably leave it as `"site-functions.php"` OR fix it to `../php/site-functions.php` for Family.
       Since Family is a subdir, `include "site-functions.php"` might assume it's in `Family/`.
       I will try `include "../php/site-functions.php"` to be explicit.
  -->
  <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
</head>

<body class="iframe-body">

  <div class="content-container">
    <div class="heading">
      <h1 class="centered brand-name" style="text-align: center; margin-bottom: 2rem; color: var(--google-blue);">
        Danielle Rosemary DeFaria</h1>
    </div>

    <p>
      <img src="Images/DanielleOnCouch.jpg" alt="Danielle DeFaria"
        style="float: right; margin: 0 0 1rem 1rem; border-radius: 8px; max-width: 300px;">

      What can I say, like most fathers I'm extremely proud of my
      daughter. She's a treasure to me and many other people say that she
      is adorable. Who am I to argue?
    </p>

    <p>She was born on <a href="BirthAnnouncement.php">April 15,
        1992</a> (my little tax exemption!) after a mere 3 and 1/2 hours of
      labor at exactly 12:36 AM. Her middle name is derived from Rose
      Faria, her great grandmother (my grandmother) and Mary, her other
      great grandmother (Mary Shaw from her mother's side).</p>

    <p>One of her hobbies is going to baseball games (she likes the
      Giants, naturally) and, like many other kids her age, she likes to
      eat ice cream. Above is a unique photo that captures her doing what
      she likes best.</p>

    <ul>
      <li><a href="https://defaria-danielle.blogspot.com/">Cute Stories</a></li>
    </ul>

  </div>
</body>

</html>