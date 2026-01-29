# Bugs and Todo List

Use this file to track bugs, issues, and feature requests.

- [ ] Temporary files needs to be stylized
- [ ] Copyright's last modified line needs to reflect the actual last modified date
- [ ] Quotes should be single spaced and not as colorful
- [ ] ClearSCM needs to be internalized
- [ ] Upload a file doesn't work
- [ ] Add back/forward buttons to the bottom of the page
- [ ] Youtube error text is white in light mode - should be dark
- [ ] Youtube download page fails to download (cookies?) with:
```
Error executing yt-dlp: WARNING: [youtube] No supported JavaScript runtime could be found. Only deno is enabled by default; to use anothe[youtube] 53LZ0-m-8Vg: Downloading android sdkless player API JSON [youtube] 53LZ0-m-8Vg: Downloading web safari player API JSON [youtube] 53LZ0-m-8Vg: Downloading m3u8 information [info] 53LZ0-m-8Vg: Downloading 1 format(s): 399+140 ng one WARNING: [youtube] 53LZ0-m-8Vg: Some web_safari client https formats have been skipped as they are missing a url. YouTube is forcing SABR streaming for this client. See https://github.com/yt-dlp/yt-dlp/issues/12482 for more details WARNING: [youtube] 53LZ0-m-8Vg: Some web client https formats have been skipped as they are missing a url. YouTube is forcing SABR streaming for this client. See https://github.com/yt-dlp/yt-dlp/issues/12482 for more details ERROR: unable to download video data: HTTP Error 403: Forbidden
```
- [ ] Upload page fails stating simply upload failed. File is actually uploaded.
- [ ] Upload page has a dark mode and light mode. Can we set it to agree with our current light/dark mode setting?

## Closed
- [x] MAPS titles should be the same font/color as the MAPS 4.0 title
- [x] Spleeter "refused to connect" (Fixed via server config + embedding)
- [x] Contact Info alignment and labeling
- [x] Google Maps links fail to load in iframe (refused to connect), need target=_blank
- [x] Clicking https://defaria.com in Contact Info nests the page (needs target=_top)
- [x] Email check form ("Type your email address...") is missing/hidden on mobile layout
