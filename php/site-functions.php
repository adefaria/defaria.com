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
  if (strpos($_SERVER['REQUEST_URI'], '/songbook') !== false || strpos($_SERVER['REQUEST_URI'], '/songs') !== false || strpos($_SERVER['REQUEST_URI'], 'webchord.cgi') !== false) {
    $search_html = <<<HTML
    <form method="get" action="/songbook/search.php" style="display: inline-flex; align-items: center; margin: 0;">
      <input type="text" name="q" class="search-input" placeholder="Search title or lyrics" onclick="this.value=''">
    </form>
HTML;
  }

  print <<<END
<footer class="copyright" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px;">
  <div style="display: flex; align-items: center; gap: 15px;">
    <button class="footer-nav-btn left" onclick="history.back()" aria-label="Previous Page" title="Go Back">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
    </button>
    
    $search_html
  </div>

  <div style="flex-grow: 1; text-align: center;">
    <div class="footer-line"><span id="footer-mod-date">This page was last modified: $mod_time</span></div>
    <div class="footer-line">Copyright &copy; $year_str - All rights reserved <a href="mailto:$email">$email</a></div>
    <div class="footer-line">Website by Andrew DeFaria with the help of his AI friend - Gemini</div>
  </div>

  <button class="footer-nav-btn right" onclick="history.forward()" aria-label="Next Page" title="Go Forward">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
  </button>
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