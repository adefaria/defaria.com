<?php include 'includes/frame_header.php'; ?>
<style>
    /* Ensure the iframe takes up the full available height of the content area */
    body,
    html {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        /* Hide scrollbars on the wrapper itself */
    }

    .iframe-container {
        width: 100%;
        height: 100vh;
        /* Viewport height for the frame context */
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    iframe.embed-app {
        flex-grow: 1;
        border: none;
        width: 100%;
        height: 100%;
        display: block;
        /* Remove inline-block vertical alignment gaps */
    }
</style>

<div class="iframe-container">
    <iframe class="embed-app" src="https://upload.defariahome.com/file/upload"
        allow="clipboard-read; clipboard-write"></iframe>
</div>

<?php
// We generally don't include the standard footer here because the embedded app 
// likely has its own or needs the full height.
?>