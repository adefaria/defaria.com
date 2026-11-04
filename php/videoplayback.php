<?php
require_once realpath(__DIR__ . '/ip_mapping.php');
require_once realpath(__DIR__ . '/media_functions.php');

if (isset($_GET['video'])) {
    $video = $_GET['video'];
} else {
    echo "No video file provided.";
    exit;
}

$IPAddr = $_SERVER["REMOTE_ADDR"];
$ipMapping = loadIpMapping($ipMappingFile);
$displayIP = replaceIpWithText($IPAddr, $ipMapping);

$title = getMediaTitle($video);
$fileName = basename($video);
$nav = getMediaNavigation($video);
$nextUrl = $nav['next'];
$prevUrl = $nav['prev'];
$description = getMediaDescription($video);

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'video' => $video,
        'fileName' => $fileName,
        'title' => $title,
        'nextUrl' => $nextUrl,
        'prevUrl' => $prevUrl,
        'description' => $description,
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <script src="/JavaScript/media_controls.js"></script>
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: white;
            color: black;
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: black;
            color: white;
        }

        .player-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        video {
            width: 100vw;
            max-height: 80vh;
            object-fit: contain;
            background-color: black;
        }

        .media-title {
            margin: 15px 20px 10px 20px;
            font-size: 2.5rem;
            text-align: center;
            word-break: break-word;
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0 10px 0;
        }

        .nav-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .nav-button:hover:not(.disabled) {
            background-color: #0056b3;
        }

        .autoplay-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            user-select: none;
        }

        .autoplay-label input[type="checkbox"] {
            cursor: pointer;
            width: 16px;
            height: 16px;
            margin: 0;
        }

        .nav-button.disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            pointer-events: none;
        }

        body.dark-mode .nav-button.disabled {
            background-color: #444;
            color: #888;
        }

        .media-description {
            width: 80%;
            max-width: 700px;
            margin: 10px 20px 30px 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: left;
            word-break: break-word;
        }

        body.dark-mode .media-description {
            border-top-color: #444;
        }

        .loop-markers {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 10px 0 5px 0;
            font-size: 0.95rem;
            color: #555;
        }

        body.dark-mode .loop-markers {
            color: #aaa;
        }

        .mark-label {
            font-weight: bold;
        }

        .mark-link {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
            font-weight: bold;
        }

        .mark-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        body.dark-mode .mark-link {
            color: #4da3ff;
        }

        body.dark-mode .mark-link:hover {
            color: #80bfff;
        }

        .clear-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 22px;
            padding: 0 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background: #fff;
            color: #555;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .clear-btn:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        body.dark-mode .clear-btn {
            background: #2b2b2b;
            color: #aaa;
            border-color: #444;
        }

        body.dark-mode .clear-btn:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .mark-val {
            font-family: monospace;
            padding: 2px 6px;
            border-radius: 3px;
            background-color: #eee;
            color: #777;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .mark-val:hover {
            outline: 1px dashed #999;
        }

        .mark-val.set {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        body.dark-mode .mark-val {
            background-color: #333;
            color: #aaa;
        }

        body.dark-mode .mark-val:hover {
            outline: 1px dashed #666;
        }

        body.dark-mode .mark-val.set {
            background-color: #28a745;
            color: white;
        }

        .mark-val-input {
            width: 55px;
            font-family: monospace;
            font-size: 0.9rem;
            padding: 1px 3px;
            border: 1px solid #007bff;
            border-radius: 3px;
            text-align: center;
            background: #fff;
            color: #222;
            outline: none;
            box-sizing: border-box;
            vertical-align: middle;
        }

        body.dark-mode .mark-val-input {
            background: #2b2b2b;
            color: #eee;
            border-color: #4da3ff;
        }

        .media-toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }


        .media-help-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .media-help-overlay.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .media-help-card {
            background: #fff;
            color: #222;
            border-radius: 8px;
            padding: 20px 24px;
            max-width: 480px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            transform: scale(0.95);
            transition: transform 0.2s ease;
        }

        .media-help-overlay.visible .media-help-card {
            transform: scale(1);
        }

        body.dark-mode .media-help-card {
            background: #1e1e1e;
            color: #e0e0e0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
            border: 1px solid #333;
        }

        .media-help-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        body.dark-mode .media-help-header {
            border-bottom-color: #333;
        }

        .media-help-header h3 {
            margin: 0;
            font-size: 1.25rem;
        }

        .media-help-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #888;
            cursor: pointer;
            padding: 0 4px;
        }

        .media-help-close:hover {
            color: #111;
        }

        body.dark-mode .media-help-close:hover {
            color: #fff;
        }

        .media-help-table {
            width: 100%;
            border-collapse: collapse;
        }

        .media-help-table td {
            padding: 6px 4px;
            font-size: 0.95rem;
        }

        .media-help-table td:first-child {
            width: 35%;
            white-space: nowrap;
        }

        .media-help-table kbd {
            display: inline-block;
            padding: 2px 7px;
            font-family: monospace;
            font-size: 0.85rem;
            font-weight: bold;
            border-radius: 4px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        body.dark-mode .media-help-table kbd {
            background-color: #2c2c2c;
            border-color: #444;
            color: #ddd;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
        }

        .help-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid #ccc;
            background: #f8f8f8;
            color: #666;
            font-size: 0.8rem;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            margin-left: 6px;
            transition: all 0.2s;
        }

        .help-btn:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        body.dark-mode .help-btn {
            background: #2b2b2b;
            color: #aaa;
            border-color: #444;
        }

        body.dark-mode .help-btn:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .media-toast.visible {
            opacity: 1;
            transform: translateX(-50%) translateY(5px);
        }
    </style>
    <script>
        function updateTheme() {
            try {
                const parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                if (parentTheme === 'dark') {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
            } catch (e) {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.body.classList.add('dark-mode');
                }
            }
        }
        document.addEventListener('DOMContentLoaded', updateTheme);
        if (window !== window.top) {
            try {
                const observer = new MutationObserver(updateTheme);
                observer.observe(window.parent.document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
            } catch (e) { }
        }
    </script>
</head>

<body>
    <div class="player-container">
        <h2 class="media-title" id="media-title"><?php echo htmlspecialchars($title); ?></h2>

        <div class="nav-controls">
            <a id="prev-button" href="<?php echo !empty($prevUrl) ? htmlspecialchars($prevUrl) : '#'; ?>" class="nav-button prev-button<?php echo empty($prevUrl) ? ' disabled' : ''; ?>">&laquo; Previous</a>
            <label class="autoplay-label" for="autoplay_toggle">
                <input type="checkbox" id="autoplay_toggle" name="autoplay_toggle">
                Autoplay
            </label>
            <a id="next-button" href="<?php echo !empty($nextUrl) ? htmlspecialchars($nextUrl) : '#'; ?>" class="nav-button next-button<?php echo empty($nextUrl) ? ' disabled' : ''; ?>">Next &raquo;</a>
        </div>

        <video id="video" controls autoplay playsinline>
            <source src="<?php echo htmlspecialchars($video); ?>">
            Your browser does not support the video tag.
        </video>
        <div class="loop-markers" id="loop-markers">
            <span><a href="#" class="mark-link" id="btn-mark-a" title="Set Mark A (A)"><span class="mark-label">Mark A:</span></a> <span id="mark-a" class="mark-val">not set</span></span>
            <span><a href="#" class="mark-link" id="btn-mark-b" title="Set Mark B (B)"><span class="mark-label">Mark B:</span></a> <span id="mark-b" class="mark-val">not set</span></span>
            <button type="button" id="clear-loop-button" class="clear-btn" title="Clear A-B Loop (C)">Clear</button>
            <button type="button" id="help-button" class="help-btn" title="Keyboard Shortcuts (?)">?</button>
        </div>

        <div class="media-description" id="media-description" style="<?php echo empty($description) ? 'display:none;' : ''; ?>">
            <?php echo $description; ?>
        </div>
    </div>

    <script>
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        const videoID = document.getElementById('video');
        const titleEl = document.getElementById('media-title');
        const descEl = document.getElementById('media-description');
        const prevBtn = document.getElementById('prev-button');
        const nextBtn = document.getElementById('next-button');
        const autoplayToggle = document.getElementById('autoplay_toggle');
        let autoplayEnabled = getCookie('media_autoplay') !== 'false' && getCookie('media_autoadvance') !== 'false';

        if (autoplayToggle) {
            autoplayToggle.checked = autoplayEnabled;
            autoplayToggle.addEventListener('change', (e) => {
                autoplayEnabled = e.target.checked;
                setCookie('media_autoplay', autoplayEnabled ? 'true' : 'false', 365);
                setCookie('media_autoadvance', autoplayEnabled ? 'true' : 'false', 365);
            });
        }

        let videoFile = <?php echo json_encode($video); ?>;
        let currentFileName = <?php echo json_encode($fileName); ?>;
        let nextUrl = <?php echo json_encode($nextUrl); ?>;
        let prevUrl = <?php echo json_encode($prevUrl); ?>;
        let mediaTitle = <?php echo json_encode($title); ?>;

        const supportedVideoExts = ['mp4', 'webm', 'ogv', 'mkv', 'mov'];

        // Ensure playback starts from 0 without triggering a spurious seek event on metadata load
        if (videoID.currentTime !== 0) {
            videoID.currentTime = 0;
        }

        function formatTime(seconds) {
            const totalSeconds = Math.max(0, Math.round(seconds || 0));
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        // --- Screen Wake Lock API ---
        let wakeLock = null;
        async function requestWakeLock() {
            if ('wakeLock' in navigator && wakeLock === null) {
                try {
                    wakeLock = await navigator.wakeLock.request('screen');
                } catch (err) {}
            }
        }

        function releaseWakeLock() {
            if (wakeLock !== null) {
                wakeLock.release().then(() => { wakeLock = null; }).catch(() => {});
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible' && isPlaying) {
                requestWakeLock();
            }
        });

        // --- Media Session API ---
        function updateMediaSession() {
            if (!('mediaSession' in navigator)) return;
            navigator.mediaSession.metadata = new MediaMetadata({
                title: mediaTitle,
                artist: 'DeFaria.com',
                album: 'Video Playback'
            });

            navigator.mediaSession.setActionHandler('play', () => { videoID.play(); });
            navigator.mediaSession.setActionHandler('pause', () => { videoID.pause(); });
            navigator.mediaSession.setActionHandler('previoustrack', prevUrl ? () => { navigateTrack(prevUrl, false); } : null);
            navigator.mediaSession.setActionHandler('nexttrack', nextUrl ? () => { navigateTrack(nextUrl, false); } : null);
        }

        updateMediaSession();

        let startTime = 0;
        let totalTimeWatched = 0;
        let isPlaying = false;
        let videoStarted = false;
        let videoEnded = false;
        let isSeeking = false;
        let seekTimeout = null;
        let hasProgressed = false;
        let bailLogged = false;

        function resetTrackState() {
            clearTimeout(seekTimeout);
            startTime = 0;
            totalTimeWatched = 0;
            isPlaying = false;
            videoStarted = false;
            videoEnded = false;
            isSeeking = false;
            seekTimeout = null;
            hasProgressed = false;
            bailLogged = false;
            if (window.mediaControls) {
                window.mediaControls.resetLoop();
            }
        }

        function updateNavButtons() {
            if (prevUrl) {
                prevBtn.href = prevUrl;
                prevBtn.classList.remove('disabled');
            } else {
                prevBtn.href = '#';
                prevBtn.classList.add('disabled');
            }
            if (nextUrl) {
                nextBtn.href = nextUrl;
                nextBtn.classList.remove('disabled');
            } else {
                nextBtn.href = '#';
                nextBtn.classList.add('disabled');
            }
        }

        async function navigateTrack(monitorUrl, isAutoAdvance) {
            if (!monitorUrl) return;
            try {
                const parsed = new URL(monitorUrl, window.location.origin);
                const targetPath = parsed.searchParams.get('u');
                if (targetPath) {
                    const ext = targetPath.split('.').pop().toLowerCase();
                    if (supportedVideoExts.includes(ext)) {
                        if (!isAutoAdvance && videoStarted && !videoEnded && !bailLogged) {
                            bailLogged = true;
                            if (isPlaying) {
                                totalTimeWatched += Math.max(0, videoID.currentTime - startTime);
                            }
                            logmsg('user bailed @ ' + formatTime(totalTimeWatched));
                        }

                        // Record access in monitorFile background mode without full page reload
                        fetch(monitorUrl + '&bg=1', { keepalive: true }).catch(() => {});

                        const resp = await fetch('/php/videoplayback.php?ajax=1&video=' + encodeURIComponent(targetPath));
                        if (resp.ok) {
                            const data = await resp.json();
                            resetTrackState();
                            videoFile = data.video;
                            currentFileName = data.fileName;
                            nextUrl = data.nextUrl;
                            prevUrl = data.prevUrl;
                            mediaTitle = data.title;

                            document.title = data.title;
                            titleEl.textContent = data.title;
                            if (data.description) {
                                descEl.innerHTML = data.description;
                                descEl.style.display = '';
                            } else {
                                descEl.innerHTML = '';
                                descEl.style.display = 'none';
                            }
                            updateNavButtons();
                            updateMediaSession();

                            history.pushState({ video: data.video }, data.title, '/php/videoplayback.php?video=' + encodeURIComponent(data.video));

                            // Reuse the same blessed <video> element so iOS Safari allows continuous autoplay
                            const sourceEl = videoID.querySelector('source');
                            if (sourceEl) {
                                sourceEl.setAttribute('src', data.video);
                            }
                            videoID.src = data.video;
                            videoID.load();
                            videoID.play().catch(() => {});
                            return;
                        }
                    }
                }
            } catch (err) {}

            window.location.href = monitorUrl;
        }

        prevBtn.addEventListener('click', (e) => {
            if (!prevUrl) {
                e.preventDefault();
                return;
            }
            e.preventDefault();
            navigateTrack(prevUrl, false);
        });

        nextBtn.addEventListener('click', (e) => {
            if (!nextUrl) {
                e.preventDefault();
                return;
            }
            e.preventDefault();
            navigateTrack(nextUrl, false);
        });

        videoID.addEventListener('timeupdate', () => {
            if (videoID.currentTime >= 1) {
                hasProgressed = true;
            }
        });

        videoID.addEventListener('play', () => {
            isPlaying = true;
            startTime = videoID.currentTime;
            requestWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'playing';
            }
            if (!videoStarted) {
                videoStarted = true;
                logmsg('Started ' + currentFileName);
            } else {
                logmsg('Resumed @ ' + formatTime(videoID.currentTime));
            }
        });

        videoID.addEventListener('pause', () => {
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'paused';
            }
            if (isPlaying && !isSeeking && !videoEnded && !videoID.ended) {
                totalTimeWatched += Math.max(0, videoID.currentTime - startTime);
                logmsg('Paused  @ ' + formatTime(videoID.currentTime));
            }
            isPlaying = false;
        });

        videoID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout);
        });

        videoID.addEventListener('seeked', () => {
            clearTimeout(seekTimeout);
            seekTimeout = setTimeout(() => {
                isSeeking = false;
                const currentRounded = Math.round(videoID.currentTime);
                if (currentRounded === 0 && !hasProgressed) {
                    return;
                }
                if (currentRounded > 0) {
                    hasProgressed = true;
                }
                logmsg('Seeked to ' + formatTime(videoID.currentTime));
                if (isPlaying) {
                    totalTimeWatched += Math.max(0, videoID.currentTime - startTime);
                    startTime = videoID.currentTime;
                }
            }, 200);
        });

        videoID.addEventListener('ended', () => {
            videoEnded = true;
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'none';
            }
            logmsg('Ended ' + currentFileName + ' @ ' + formatTime(videoID.currentTime));
            if (nextUrl && autoplayEnabled) {
                navigateTrack(nextUrl, true);
            }
        });

        function logmsg(msg) {
            const fileType = 'Video';
            const IPAddr = "<?php echo $displayIP; ?>";
            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: videoFile,
                msg: msg,
            };

            const payload = JSON.stringify(data);
            if (navigator.sendBeacon) {
                const blob = new Blob([payload], { type: 'application/json' });
                if (navigator.sendBeacon('/php/log_action.php', blob)) {
                    return;
                }
            }

            fetch('/php/log_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: payload,
                keepalive: true
            }).catch(() => {});
        }

        function handlePageUnload() {
            if (bailLogged) return;
            bailLogged = true;
            releaseWakeLock();
            if (videoStarted && !videoEnded) {
                if (isPlaying) {
                    totalTimeWatched += Math.max(0, videoID.currentTime - startTime);
                }
                logmsg('user bailed @ ' + formatTime(totalTimeWatched));
            }
        }

        function downloadCurrentMedia() {
            if (!videoFile) return;
            const a = document.createElement('a');
            a.href = videoFile;
            a.download = currentFileName || '';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        window.mediaControls = initMediaControls({
            media: videoID,
            markA: document.getElementById('mark-a'),
            markB: document.getElementById('mark-b'),
            markABtn: document.getElementById('btn-mark-a'),
            markBBtn: document.getElementById('btn-mark-b'),
            clearBtn: document.getElementById('clear-loop-button'),
            helpButton: document.getElementById('help-button'),
            skipSeconds: 10,
            enableFullscreen: true,
            enableMute: true,
            onDownload: downloadCurrentMedia
        });

        window.addEventListener('pagehide', handlePageUnload);
        window.addEventListener('beforeunload', handlePageUnload);
    </script>
</body>

</html>