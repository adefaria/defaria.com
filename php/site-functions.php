<?php
function menu()
{
  $main_links = "/web/index.links";
  $local_links = "index.links";

  if (is_readable($local_links)) {
    $links = @file($local_links)
      or die("Unable to open local links file ($local_links)");
  } else {
    $links = @file($main_links)
      or die("Unable to open main links file ($main_links)");
  } // if

  print "<div id=menu>\n";

  foreach ($links as $link) {
    $link_desc = explode("|", chop($link));
    print "<a href=\"" . $link_desc[0] . "\">" . $link_desc[1] . "</a><br>\n";
  } // foreach
  print "</div>\n";
} // menu

function search_box()
{
  print <<<END
<div id="search">
  <!-- Start: Search my site with Google -->
  <form method="get" action="https://www.google.com/search" name="search">
  <div>Search my site
  <input type="text" name="q" size="15" id="q" maxlength=255 value=""
    onclick="document.search.q.value='';">
  <input type="hidden" name="domains" value="defaria.com">
  <input type="hidden" name="sitesearch" value="defaria.com">
  </div>
  </form>
  <!-- End: Search my site with Google -->
</div>
<br>
END;
} // search_box

function navigation_bar($validated = "no")
{
  print "<div class=leftbar>\n";
  search_box();
  menu();
  print <<<END
  <script src="/maps/JavaScript/CheckAddress.js" type="text/javascript"></script>
  <div id="emailsearch">
  <form "method"=post action="javascript://" name="address" onsubmit="checkaddress(this,'andrew');">
    <a href="/maps/doc">Can you email me?</a>
    Type in your email address and hit enter to see.
    <input type="text" class="searchfield" id="emailsearchfield" name="email"
     size="18" maxlength="255" value="" onclick="document.address.email.value = '';">
  </form>
  </div>
</div>
END;
} // navigation_bar

function copyright(
  $start_year = "",
  $author = "Andrew DeFaria",
  $email = "Andrew@DeFaria.com",
  $home = ""
) {
  $today = getdate();
  $current_year = $today["year"];

  $this_file = $_SERVER['PHP_SELF'];

  // Handle user home web pages
  if (preg_match("/\/\~/", $this_file)) {
    $this_file = preg_replace("/\/\~(\w+)\/(\s*)/", "/home/$1/web$2/", $this_file);
  } elseif (file_exists("/web" . $this_file)) {
    $this_file = "/web" . $this_file;
  } elseif (file_exists("/opt/clearscm" . $this_file)) {
    $this_file = "/opt/clearscm" . $this_file;
  } // if

  $mod_time = date("F d Y @ g:i a", filemtime($this_file));

  $search_html = "";
  $is_webchord = (strpos($_SERVER['REQUEST_URI'], 'webchord.cgi') !== false) ||
    (isset($_SERVER['REDIRECT_URL']) && strpos($_SERVER['REDIRECT_URL'], 'webchord.cgi') !== false);

  // Check if we are in the songbook area
  $omni_search = "";
  $song_search = "";

  if (strpos($_SERVER['REQUEST_URI'], '/songbook') !== false || strpos($_SERVER['REQUEST_URI'], '/songs') !== false || $is_webchord) {
    // Generate the Omni Search form (Column 2)
    $omni_search = <<<HTML
    <form method="get" action="/songs/search.php" style="display: inline-flex; align-items: center; margin: 0;">
      <input type="hidden" name="type" value="omni">
      <input type="text" name="q" class="uniform-input-width" placeholder="Omni Search..." autocomplete="off" style="background-color: var(--input-bg); color: var(--input-text); border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; min-width: 250px;">
    </form>
HTML;

    // Generate the Song Search form (Column 4)
    global $songs;
    $mySongs = $songs; // pointer

    // If global songs not found, try to find them
    if (empty($mySongs)) {
      if (!function_exists('getSongs')) {
        if (file_exists("/opt/songbook/web/songbook.php")) {
          include_once "/opt/songbook/web/songbook.php";
        }
      }
      if (function_exists('getSongs')) {
        $mySongs = getSongs("/opt/songbook");
      }
    }

    $allSongsScript = "";

    if (!empty($mySongs) && is_array($mySongs)) {
      $js_songs = [];
      // Helper if function missing
      if (!function_exists('getSearchableLyrics')) {
        // Define local helper or skip lyrics? 
        // Ideally songbook.php is included so it exists.
        // If not, we skip lyrics to avoid fatal error.
      }

      foreach ($mySongs as $song_item) {
        $title = basename($song_item, ".pro");
        $lyrics = "";
        if (function_exists('getSearchableLyrics')) {
          $lyrics = getSearchableLyrics($song_item);
        }
        $js_songs[] = [
          'title' => $title,
          'file' => $song_item,
          'lyrics' => $lyrics
        ];
      }
      if (!empty($js_songs)) {
        $json = json_encode($js_songs);
        $allSongsScript = "<script>var allSongs = $json; console.log('allSongs loaded from footer: ' + allSongs.length);</script>";
      }
    }

    $song_search = <<<HTML
    <form method="get" action="/songs/search.php" class="footer-search-form" style="display: inline-flex; align-items: center; margin: 0; position: relative;">
      <input type="hidden" name="type" value="song">
      <input type="text" name="q" id="footer-song-search" class="uniform-input-width song-search-input" placeholder="Song Search..." autocomplete="off" style="background-color: var(--input-bg); color: var(--input-text); border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; min-width: 250px;">
      <div id="footer-song-results" class="autocomplete-results"></div>
      $allSongsScript
    </form>
    <style>
    .footer-search-form .autocomplete-results {
        position: absolute;
        bottom: 100%;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 8px 8px 0 0;
        max-height: 300px;
        overflow-y: auto;
        display: none;
        box-shadow: 0 -4px 6px rgba(0,0,0,0.1);
        text-align: left;
    }
    .footer-search-form .autocomplete-results.show {
        display: block;
    }
    .autocomplete-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
    .autocomplete-item:hover, .autocomplete-item.active {
        background-color: #f0f0f0;
    }
    </style>
HTML;
  }

  print <<<END
<footer class="copyright" style="padding: 10px 20px;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <!-- Column 1: Prev Arrow -->
      <td width="5%" align="left" valign="middle">
        <button class="footer-nav-btn left" onclick="history.back()" aria-label="Previous Page" title="Go Back">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
      </td>

      <!-- Column 2: Omni Search -->
      <td width="20%" align="center" valign="middle">
        $omni_search
      </td>

      <!-- Column 3: Copyright Block -->
      <td width="50%" align="center" valign="middle">
        <div class="footer-line"><span id="footer-mod-date">This page was last modified: $mod_time</span></div>
        <div class="footer-line">Copyright &copy; $current_year - All rights reserved <a href="mailto:$email">$email</a></div>
        <div class="footer-line">Website by Andrew DeFaria with the help of his AI friend - Gemini</div>
      </td>

      <!-- Column 4: Song Search -->
      <td width="20%" align="center" valign="middle">
        $song_search
      </td>

      <!-- Column 5: Next Arrow -->
      <td width="5%" align="right" valign="middle">
        <button class="footer-nav-btn right" onclick="history.forward()" aria-label="Next Page" title="Go Forward">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </td>
    </tr>
  </table>
</footer>
END;
} // copyright

function display_code($file)
{
  $code = @file($file)
    or die("Unable to open file ($file)");

  print "<div class=code>\n<table id=listing cellspacing=0 cellpadding=2 border=0>\n";

  $line_number = 1;

  foreach ($code as $line_of_code) {
    print "<tr>\n  <td align=right id=line-number><a name=line_$line_number></a>" .
      $line_number++ . "</td>\n";
    print "  <td id=code><tt>\n";
    for ($i = 0; $i < strlen($line_of_code); $i++) {
      if ($line_of_code[$i] == " ") {
        echo "&nbsp;";
      } else if ($line_of_code[$i] == "\t") {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      } else if ($line_of_code[$i] != "\n") {
        echo $line_of_code[$i];
      } // if
    } // foreach 
    print "</tt></td>\n</tr>\n";
  } // foreach

  print "</table>\n</div>";
} // display_code
?>