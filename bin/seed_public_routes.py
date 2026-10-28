#!/usr/bin/env python3
"""
seed_public_routes.py

Crawls defaria.com via localhost HTTP/HTTPS, recursively discovering internal
`<a href="...">` hyperlinks and referenced assets to seed the public allowlist.
Outputs /opt/defaria.com/etc/public_routes.json for the Nginx/PHP auth gateway.
"""

import sys
import os
import re
import json
import ssl
import urllib.request
import urllib.parse
from datetime import datetime

DOCUMENT_ROOT = "/opt/defaria.com"
OUTPUT_FILE = os.path.join(DOCUMENT_ROOT, "etc", "public_routes.json")
BASE_URL = "https://localhost"
HOST_HEADER = "defaria.com"

# Standard prefixes that are always public to render public pages and the login shell
DEFAULT_PUBLIC_PREFIXES = [
    "/css/",
    "/JavaScript/",
    "/Icons/",
    "/Logos/",
    "/auth/",
]

# Standard exact files required for infrastructure/browsers
DEFAULT_PUBLIC_EXACT = [
    "/",
    "/index.php",
    "/welcome.php",
    "/login",
    "/login.php",
    "/logout",
    "/logout.php",
    "/404.php",
    "/favicon.ico",
    "/robots.txt",
]

# Seed URLs to begin crawling
INITIAL_QUEUE = [
    "/",
    "/welcome.php",
    "/personal",
    "/personal.php",
    "/professional",
    "/professional.php",
    "/music",
    "/music.php",
    "/projects",
    "/projects.php",
    "/blogs",
    "/blogs.php",
    "/misc",
    "/misc.php",
    "/contact",
    "/contact.php",
    "/addresses",
    "/addresses.php",
    "/family",
    "/Family/",
    "/Family/index.php",
    "/jokes",
    "/Jokes/",
    "/Jokes/index.php",
    "/quotes",
    "/libertarian.php",
    "/computers",
    "/Computers/",
    "/Computers/index.php",
    "/vette",
    "/Vette/",
    "/Vette/index.html",
    "/band",
    "/Band/",
    "/Band/index.php",
    "/resume",
    "/clearscm",
    "/maps",
    "/mapsmobile",
]

# Explicit private directories and pages that must never be public without authentication
EXPLICIT_PRIVATE_PATHS = [
    "/Dad",
    "/Dad/",
    "/Danielle",
    "/Danielle/",
    "/Olga",
    "/Olga/",
    "/GD",
    "/GD/",
    "/cvsadm",
    "/cvsadm/",
    "/NJ80",
    "/NJ80/",
    "/downloads",
    "/downloads/",
    "/privacy",
    "/privacy/",
    "/secrets.yml",
    "/upload",
    "/upload/",
    "/upload.php",
    "/ytdownload",
    "/ytdownload/",
    "/yt",
    "/yt/",
    "/spleeter",
    "/webmonitor",
    "/php/logviewer.php",
    "/jobhunt",
    "/jobhunt/",
    "/reachout",
    "/reachout/",
]

def is_explicit_private(path):
    """Check if a path matches or is nested within an explicit private path."""
    if not path:
        return False
    path_clean = path.rstrip("/")
    for p in EXPLICIT_PRIVATE_PATHS:
        p_clean = p.rstrip("/")
        if path == p or path_clean == p_clean or path.startswith(p_clean + "/"):
            return True
    return False

def normalize_path(path):
    """Normalize a path by stripping hashes and ensuring leading slash."""
    if not path:
        return "/"
    # Strip URL fragment (#anchor)
    path = path.split("#")[0].strip()
    if not path:
        return ""
    if not path.startswith("/"):
        path = "/" + path
    return path

def is_external_or_special(href):
    """Determine if a link points to an external site or special protocol."""
    href = href.strip()
    if href.startswith(("mailto:", "javascript:", "tel:", "data:", "#")):
        return True
    parsed = urllib.parse.urlparse(href)
    if parsed.scheme and parsed.netloc:
        if parsed.netloc not in ("defaria.com", "www.defaria.com", "localhost"):
            return True
    return False

def quote_path_for_request(path):
    """Properly quote special chars and spaces in URL path for urllib."""
    parts = urllib.parse.urlsplit(path)
    quoted_path = urllib.parse.quote(parts.path)
    if parts.query:
        return f"{quoted_path}?{parts.query}"
    return quoted_path

def fetch_url(ctx, path):
    """Fetch URL content and headers from local server."""
    quoted = quote_path_for_request(path)
    url = urllib.parse.urljoin(BASE_URL, quoted)
    req = urllib.request.Request(
        url,
        headers={
            "Host": HOST_HEADER,
            "User-Agent": "DeFariaAllowlistCrawler/1.0"
        }
    )
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            content_type = resp.headers.get("Content-Type", "")
            status = resp.status
            body = resp.read()
            return status, content_type, body
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get("Content-Type", ""), None
    except Exception as e:
        print(f"  [!] Request error for {path}: {e}")
        return None, "", None

def extract_links_and_assets(html_content, current_path):
    """Extract all href links, iframe sources, images, scripts, and audio/video from HTML."""
    if not html_content:
        return set()
    
    found_urls = set()
    
    # 1. <a href="...">
    for match in re.finditer(r'<a\s+(?:[^>]*?\s+)?href=["\']([^"\']+)["\']', html_content, re.IGNORECASE):
        href = match.group(1).strip()
        if not is_external_or_special(href):
            found_urls.add(href)
            
    # 2. <iframe src="..."> or <frame src="...">
    for match in re.finditer(r'<(?:iframe|frame)\s+(?:[^>]*?\s+)?src=["\']([^"\']+)["\']', html_content, re.IGNORECASE):
        src = match.group(1).strip()
        if not is_external_or_special(src):
            found_urls.add(src)

    # 3. Referenced static assets: <img src="...">, <link href="...">, <script src="...">, <source src="...">
    for match in re.finditer(r'<(?:img|script|source|audio|video)\s+(?:[^>]*?\s+)?src=["\']([^"\']+)["\']', html_content, re.IGNORECASE):
        src = match.group(1).strip()
        if not is_external_or_special(src):
            found_urls.add(src)

    for match in re.finditer(r'<link\s+(?:[^>]*?\s+)?href=["\']([^"\']+)["\']', html_content, re.IGNORECASE):
        href = match.group(1).strip()
        if not is_external_or_special(href):
            found_urls.add(href)

    normalized = set()
    for item in found_urls:
        parsed = urllib.parse.urlsplit(item)
        target_path = parsed.path
        if not target_path and parsed.query:
            target_path = current_path
        elif not target_path:
            continue
        
        # Resolve relative links against current path
        if not target_path.startswith("/"):
            target_path = urllib.parse.urljoin(current_path, target_path)

        norm = normalize_path(target_path)
        if norm:
            # Unquote percent-encoding for storing canonical path
            unquoted_norm = urllib.parse.unquote(norm)
            normalized.add(unquoted_norm)
            
    return normalized

def crawl():
    print("=" * 65)
    print(" Starting defaria.com Public Route Spider")
    print("=" * 65)
    
    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE

    queue = list(INITIAL_QUEUE)
    visited = set()
    public_paths = set(DEFAULT_PUBLIC_EXACT)

    while queue:
        current = queue.pop(0)
        norm_current = normalize_path(current)
        if not norm_current or norm_current in visited:
            continue

        visited.add(norm_current)
        print(f"[*] Crawling: {norm_current}")

        status, content_type, body = fetch_url(ctx, norm_current)
        
        if status in (200, 301, 302) and not is_explicit_private(norm_current):
            public_paths.add(norm_current)
            # Variations: with / without trailing slash, .php
            if not norm_current.endswith("/") and not "." in os.path.basename(norm_current):
                p1 = norm_current + "/"
                p2 = norm_current + ".php"
                if not is_explicit_private(p1): public_paths.add(p1)
                if not is_explicit_private(p2): public_paths.add(p2)
            elif norm_current.endswith(".php"):
                base_clean = norm_current[:-4]
                p1 = base_clean
                p2 = base_clean + "/"
                if not is_explicit_private(p1): public_paths.add(p1)
                if not is_explicit_private(p2): public_paths.add(p2)
            elif norm_current.endswith("/"):
                base_clean = norm_current[:-1]
                p1 = base_clean
                p2 = base_clean + ".php"
                if not is_explicit_private(p1): public_paths.add(p1)
                if not is_explicit_private(p2): public_paths.add(p2)

        if status == 200 and "text/html" in content_type and body:
            try:
                html_text = body.decode("utf-8", errors="ignore")
                discovered = extract_links_and_assets(html_text, norm_current)
                for link in discovered:
                    if is_explicit_private(link):
                        continue
                    # Skip assets covered by standard public prefixes
                    if any(link.startswith(p) for p in DEFAULT_PUBLIC_PREFIXES):
                        public_paths.add(link)
                        continue
                    if link not in visited and link not in queue:
                        # Only follow text/html links further in queue if not a binary asset
                        ext = os.path.splitext(link)[1].lower()
                        if ext in (".png", ".jpg", ".jpeg", ".gif", ".svg", ".ico", ".pdf", ".mp3", ".mp4", ".zip", ".tar", ".gz", ".docx"):
                            public_paths.add(link)
                        else:
                            queue.append(link)
            except Exception as e:
                print(f"  [!] Failed parsing HTML for {norm_current}: {e}")

    # Build output payload (strictly filtering out any explicit private paths)
    sorted_public_paths = sorted([p for p in public_paths if not is_explicit_private(p)])
    
    data = {
        "generated_at": datetime.now().isoformat(),
        "description": "Public allowlist generated by crawl of defaria.com internal hyperlinks and assets.",
        "public_exact_paths": sorted_public_paths,
        "public_prefixes": sorted(DEFAULT_PUBLIC_PREFIXES),
        "explicit_private_paths": EXPLICIT_PRIVATE_PATHS
    }

    os.makedirs(os.path.dirname(OUTPUT_FILE), exist_ok=True)
    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)

    print("\n" + "=" * 65)
    print(f" Crawl Complete: {len(sorted_public_paths)} public paths seeded.")
    print(f" Configuration written to: {OUTPUT_FILE}")
    print("=" * 65)

    # Perform filesystem audit
    audit_filesystem(sorted_public_paths)

def audit_filesystem(public_paths):
    print("\n--- Filesystem Audit against Public Allowlist ---")
    entries = os.listdir(DOCUMENT_ROOT)
    public_items = []
    private_items = []

    for item in sorted(entries):
        if item.startswith("."):
            continue
        item_path = "/" + item
        # Check if matched by exact public path or prefix
        is_public = False
        if item_path in public_paths or (item_path + "/") in public_paths:
            is_public = True
        elif any(item_path.startswith(p.rstrip("/")) for p in DEFAULT_PUBLIC_PREFIXES):
            is_public = True
        
        # Check case-insensitively for directories like Family, Jokes, Vette, Computers
        if not is_public:
            for p in public_paths:
                if p.lower().strip("/") == item.lower():
                    is_public = True
                    break

        if is_public:
            public_items.append(item)
        else:
            private_items.append(item)

    print(f"\n[PUBLIC by Hyperlink Seeding ({len(public_items)})]:")
    for it in public_items:
        print(f"  + {it}")

    print(f"\n[PRIVATE by Default-Deny ({len(private_items)})]:")
    for it in private_items:
        print(f"  - {it}  <-- LOCKED (requires login)")
    print("-" * 65)

if __name__ == "__main__":
    crawl()
