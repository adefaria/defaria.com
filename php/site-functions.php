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

    // Use NOWDOC for JS to avoid PHP interpolation of ${} variables
    $footer_js = <<<'JS'
    (function() {
        function initFooterSearch() {
            const input = document.getElementById("footer-song-search");
            const results = document.getElementById("footer-song-results");
            
            if (!input || !results) {
                return;
            }

            // Close list when clicking outside
            document.addEventListener("click", function(e) {
                if (e.target !== input && e.target !== results && !results.contains(e.target)) {
                    results.classList.remove("show");
                }
            });

            input.addEventListener("input", function() {
                const query = this.value;
                if (!query) {
                    results.classList.remove("show");
                    return;
                }

                if (typeof allSongs === 'undefined' || !Array.isArray(allSongs)) {
                    console.error("allSongs not loaded yet or invalid", typeof allSongs);
                    return;
                }

                const lowerQuery = query.toLowerCase();
                const matches = allSongs.filter(song => 
                    song.title.toLowerCase().includes(lowerQuery) || 
                    (song.lyrics && song.lyrics.toLowerCase().includes(lowerQuery))
                );

                displayResults(matches, lowerQuery);
            });
            
            function displayResults(matches, query) {
                results.innerHTML = "";
                if (matches.length === 0) {
                    results.classList.remove("show");
                    return;
                }

                // Escape special characters for regex
                const safeQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${safeQuery})`, "gi");

                matches.forEach(song => {
                    const item = document.createElement("div");
                    item.className = "autocomplete-item";
                    
                    let displayHTML = "";
                    if (song.title.toLowerCase().includes(query)) {
                         displayHTML = song.title.replace(regex, "<strong>$1</strong>");
                    } else {
                         displayHTML = song.title;
                    }
                    if (!song.title.toLowerCase().includes(query) && song.lyrics && song.lyrics.toLowerCase().includes(query)) {
                        displayHTML += " <small style='opacity:0.7'><i>(Lyrics match)</i></small>";
                    }

                    item.innerHTML = displayHTML;
                    
                    item.addEventListener("click", function() {
                        const targetUrl = "/songs/webchord.cgi?chordpro=" + encodeURIComponent(song.file);
                        window.location.href = targetUrl;
                        
                        results.classList.remove("show");
                        input.value = ""; 
                    });

                    results.appendChild(item);
                });
                
                results.classList.add("show");
            }
        }

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", initFooterSearch);
        } else {
            initFooterSearch();
        }
    })();
JS;

    $song_search = <<<HTML
    <form method="get" action="/songs/search.php" class="footer-search-form" style="display: inline-flex; align-items: center; margin: 0; position: relative;">
      <input type="hidden" name="type" value="song">
      <input type="text" name="q" id="footer-song-search" class="uniform-input-width song-search-input" placeholder="Search song title / lyrics" autocomplete="off" style="background-color: var(--input-bg); color: var(--input-text); border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; min-width: 250px;">
      <div id="footer-song-results" class="footer-autocomplete-results"></div>
      $allSongsScript
    </form>
    <style>
    .footer-search-form {
        position: relative;
    }
    .footer-search-form .footer-autocomplete-results {
        position: absolute;
        bottom: 100%; /* Positions it directly above the input */
        left: 0;
        right: 0;
        z-index: 10000; /* Ensure it's above everything including footer and iframe */
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 8px 8px 0 0;
        max-height: 400px; /* More height */
        overflow-y: auto;
        display: none;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.2);
        text-align: left;
        margin-bottom: 5px; /* Tiny gap */
    }
    .footer-search-form .footer-autocomplete-results.show {
        display: block;
    }
    .footer-search-form .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        color: #333;
        border-bottom: 1px solid #eee;
        background: #fff;
        line-height: 1.4;
    }
    .footer-search-form .autocomplete-item:last-child {
        border-bottom: none;
    }
    .footer-search-form .autocomplete-item:hover,
    .footer-search-form .autocomplete-item.active {
        background-color: #e8f0fe;
        color: #1967d2;
    }
    /* Dark mode support if parent has data-theme */
    [data-theme="dark"] .footer-search-form .footer-autocomplete-results {
        background: #2d2d2d;
        border-color: #444;
        color: #e0e0e0;
    }
    [data-theme="dark"] .footer-search-form .autocomplete-item {
        background: #2d2d2d;
        color: #e0e0e0;
        border-color: #444;
    }
    [data-theme="dark"] .footer-search-form .autocomplete-item:hover {
        background: #3d3d3d;
        color: #fff;
    }
    </style>
    <script>
    $footer_js
    </script>
HTML;
  }

  print <<<END
<style>
@media (max-width: 768px) {
  /* Hide the copyright text cell (3rd column) */
  footer.copyright td:nth-child(3), footer.copyright .footer-line {
    display: none !important;
  }
  /* Ensure nav buttons are big enough */
  .footer-nav-btn {
    min-width: 44px !important;
    min-height: 44px !important;
  }
}
</style>
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