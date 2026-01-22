<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jokes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css?v=2">

  <?php include "../php/site-functions.php" ?>
  <?php include "../php/oneliner.php" ?>
  <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
</head>

<body class="iframe-body">

  <div class="content-container">
    <div class="heading">
      <h1 class="centered brand-name" style="text-align: center; margin-bottom: 2rem;">Taglines</h1>
    </div>

    <h2>Taglines</h2>

    <p>I like those funny one liners. You've probably seen many <i>Jokes
        lists</i> emailed from friends and family before. I collect
      them.</p>

    <p>At some point I had found a Perl script to generate a random
      funny line from a list kept in a datafile. Later I found <a href="http://tagzilla.mozdev.org/">Tagzilla</a> which
      works in
      conjunction with <a href="http://mozilla.org">Mozilla</a> (and <a
        href="http://www.mozilla.org/products/thunderbird/">Thunderbird</a>,
      which is what I use) to tack on a <i>tagline</i> to and outgoing
      email or news posting.</p>

    <p>Now I've organized my taglines in to a Tagzilla file
      format. Tagzilla also allows you to simply highlite a tagline that
      you see in email or on a web page and easily <i>steal</i> it into
      your Tagzilla file. Thus my taglines will grow over time. I also
      rewrote the Perl script to a PHP script called oneliner.php. It's
      what generates the oneliner's that you see on my site. For
      example:</p>

    <div class="box">

    </div>

    <p>So when I steal a tagline it's automatically eligible to be
      selected by the PHP script and my website. A simple parameterization
      allows me to dump all my taglines here:</p>

    <?php
    oneliner("yes");
    copyright();
    ?>

  </div>
</body>

</html>