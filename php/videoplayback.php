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
            font-size: 1.5rem;
            text-align: center;
            word-break: break-word;
        }

        .nav-controls {
            display: flex;
            gap: 15px;
            margin: 10px 0 15px 0;
        }

        .nav-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .nav-button:hover:not(.disabled) {
            background-color: #0056b3;
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
        <video id="video" controls autoplay playsinline>
            <source src="<?php echo htmlspecialchars($video); ?>">
            Your browser does not support the video tag.
        </video>
        <h2 class="media-title" id="media-title"><?php echo htmlspecialchars($title); ?></h2>

        <div class="nav-controls">
            <a id="prev-button" href="<?php echo !empty($prevUrl) ? htmlspecialchars($prevUrl) : '#'; ?>" class="nav-button prev-button<?php echo empty($prevUrl) ? ' disabled' : ''; ?>">&laquo; Previous</a>
            <a id="next-button" href="<?php echo !empty($nextUrl) ? htmlspecialchars($nextUrl) : '#'; ?>" class="nav-button next-button<?php echo empty($nextUrl) ? ' disabled' : ''; ?>">Next &raquo;</a>
        </div>

        <div class="media-description" id="media-description" style="<?php echo empty($description) ? 'display:none;' : ''; ?>">
            <?php echo $description; ?>
        </div>
    </div>

    <script>
        const videoID = document.getElementById('video');
        const titleEl = document.getElementById('media-title');
        const descEl = document.getElementById('media-description');
        const prevBtn = document.getElementById('prev-button');
        const nextBtn = document.getElementById('next-button');

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
            if (nextUrl) {
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

        window.addEventListener('pagehide', handlePageUnload);
        window.addEventListener('beforeunload', handlePageUnload);
    </script>
</body>

</html>