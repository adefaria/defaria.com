import os
import re

def migrate_file(filepath):
    with open(filepath, 'r', encoding='latin-1') as f:
        content = f.read()

    # Skip if already migrated (check for iframe-body or content-container)
    if 'iframe-body' in content:
        print(f"Skipping {filepath} (already migrated)")
        return

    # Extract Title
    title_match = re.search(r'<title>(.*?)</title>', content, re.IGNORECASE)
    title = title_match.group(1) if title_match else "Page"

    # Extract Heading (H1 inside div.heading)
    h1_match = re.search(r'<div class="heading">.*?<h1.*?>(.*?)</h1>', content, re.IGNORECASE | re.DOTALL)
    if not h1_match:
         h1_match = re.search(r'<h1.*?>(.*?)</h1>', content, re.IGNORECASE | re.DOTALL)
    
    page_heading = h1_match.group(1).strip() if h1_match else title

    # Extract Content div
    content_match = re.search(r'<div id="content">(.*?)</div>\s*</body>', content, re.IGNORECASE | re.DOTALL)
    if not content_match:
        # Fallback: look for body content if div#content missing
        content_match = re.search(r'<body>(.*?)</body>', content, re.IGNORECASE | re.DOTALL)
    
    if not content_match:
        print(f"Could not parse content for {filepath}")
        return

    raw_body_content = content_match.group(1)

    # Clean up legacy calls
    # Remove navigation_bar call
    raw_body_content = re.sub(r'<\?php\s*navigation_bar\s*\(.*?\)\s*;?\s*\?>', '', raw_body_content, flags=re.IGNORECASE)
    # Remove copyright call
    raw_body_content = re.sub(r'<\?php\s*copyright\s*\(.*?\)\s*;?\s*\?>', '', raw_body_content, flags=re.IGNORECASE)
    # Remove oneliner calls if any in content
    raw_body_content = re.sub(r'<\?php\s*oneliner\s*\(.*?\)\s*;?\s*\?>', '', raw_body_content, flags=re.IGNORECASE)
    
    # Check for legacy includes relative path fix
    # Jokes/ and Family/ are deep. site-functions is in ../php/ or ../../php/ depending on depth?
    # Actually, legacy jokes used `include "site-functions.php"`. 
    # Jokes is /opt/defaria.com/Jokes/
    # site-functions is /opt/defaria.com/php/site-functions.php (?)
    # If legacy used "site-functions.php", maybe include path was set.
    # New template uses `../php/site-functions.php` for subdirs like Jokes/.
    include_path = "../php/site-functions.php"
    if "Family/Christmas" in filepath: # Deep nesting?
         include_path = "../../php/site-functions.php"

    new_content = f"""<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{title}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css?v=2">

  <?php include "{include_path}" ?>
  <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
</head>

<body class="iframe-body">

  <div class="content-container">
    <div class="heading">
      <h1 class="centered brand-name" style="text-align: center; margin-bottom: 2rem;">{page_heading}</h1>
    </div>

    {raw_body_content.strip()}

  </div>
</body>

</html>"""

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print(f"Migrated {filepath}")

def process_directory(directory):
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith(".php") and file != "index.php": # Skip index.php as I handled/will handle it manually
                migrate_file(os.path.join(root, file))

if __name__ == "__main__":
    process_directory("/opt/defaria.com/Jokes")
    process_directory("/opt/defaria.com/Family")
