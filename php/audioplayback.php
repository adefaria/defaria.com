<?php
require_once realpath(__DIR__ . '/ip_mapping.php');
require_once realpath(__DIR__ . '/media_functions.php');

if (isset($_GET['audio'])) {
    $audio = $_GET['audio'];
} else {
    echo "No audio file provided.";
    exit;
}

$IPAddr = $_SERVER["REMOTE_ADDR"];
$ipMapping = loadIpMapping($ipMappingFile);
$displayIP = replaceIpWithText($IPAddr, $ipMapping);

$title = getMediaTitle($audio);
$fileName = basename($audio);
$nav = getMediaNavigation($audio);
$nextUrl = $nav['next'];
$prevUrl = $nav['prev'];
$description = getMediaDescription($audio);

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'audio' => $audio,
        'fileName' => $fileName,
        'mimeType' => getAudioMimeType($audio),
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
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background-color: white;
            color: black;
            font-family: sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark-mode {
            background-color: black;
            color: white;
        }

        .player-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            max-width: 600px;
        }

        audio {
            width: 100%;
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
            width: 100%;
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
        <audio id="audio" controls autoplay>
            <source src="<?php echo htmlspecialchars($audio); ?>" type="<?php echo htmlspecialchars(getAudioMimeType($audio)); ?>">
            Your browser does not support the audio tag.
        </audio>
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
        const audioID = document.getElementById('audio');
        const titleEl = document.getElementById('media-title');
        const descEl = document.getElementById('media-description');
        const prevBtn = document.getElementById('prev-button');
        const nextBtn = document.getElementById('next-button');

        let audioFile = <?php echo json_encode($audio); ?>;
        let currentFileName = <?php echo json_encode($fileName); ?>;
        let nextUrl = <?php echo json_encode($nextUrl); ?>;
        let prevUrl = <?php echo json_encode($prevUrl); ?>;
        let mediaTitle = <?php echo json_encode($title); ?>;

        const supportedAudioExts = ['mp3', 'm4a', 'wav', 'ogg', 'flac', 'aac'];

        // Ensure playback starts from 0 without triggering a spurious seek event on metadata load
        if (audioID.currentTime !== 0) {
            audioID.currentTime = 0;
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

        // --- Media Session API (Mobile Background Audio & Lock Screen Controls) ---
        function updateMediaSession() {
            if (!('mediaSession' in navigator)) return;
            navigator.mediaSession.metadata = new MediaMetadata({
                title: mediaTitle,
                artist: 'DeFaria.com',
                album: 'Audio Playback'
            });

            navigator.mediaSession.setActionHandler('play', () => { audioID.play(); });
            navigator.mediaSession.setActionHandler('pause', () => { audioID.pause(); });
            navigator.mediaSession.setActionHandler('previoustrack', prevUrl ? () => { navigateTrack(prevUrl, false); } : null);
            navigator.mediaSession.setActionHandler('nexttrack', nextUrl ? () => { navigateTrack(nextUrl, false); } : null);
        }

        updateMediaSession();

        let startTime = 0;
        let totalTimeListened = 0;
        let isPlaying = false;
        let audioStarted = false;
        let audioEnded = false;
        let isSeeking = false;
        let seekTimeout = null;
        let hasProgressed = false;
        let bailLogged = false;

        function resetTrackState() {
            clearTimeout(seekTimeout);
            startTime = 0;
            totalTimeListened = 0;
            isPlaying = false;
            audioStarted = false;
            audioEnded = false;
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
                    if (supportedAudioExts.includes(ext)) {
                        if (!isAutoAdvance && audioStarted && !audioEnded && !bailLogged) {
                            bailLogged = true;
                            if (isPlaying) {
                                totalTimeListened += Math.max(0, audioID.currentTime - startTime);
                            }
                            logmsg('user bailed @ ' + formatTime(totalTimeListened));
                        }

                        // Record access in monitorFile background mode without full page reload
                        fetch(monitorUrl + '&bg=1', { keepalive: true }).catch(() => {});

                        const resp = await fetch('/php/audioplayback.php?ajax=1&audio=' + encodeURIComponent(targetPath));
                        if (resp.ok) {
                            const data = await resp.json();
                            resetTrackState();
                            audioFile = data.audio;
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

                            history.pushState({ audio: data.audio }, data.title, '/php/audioplayback.php?audio=' + encodeURIComponent(data.audio));

                            // Reuse the same blessed <audio> element so iOS Safari allows continuous autoplay
                            const sourceEl = audioID.querySelector('source');
                            if (sourceEl) {
                                sourceEl.setAttribute('src', data.audio);
                                sourceEl.setAttribute('type', data.mimeType);
                            }
                            audioID.src = data.audio;
                            audioID.load();
                            audioID.play().catch(() => {});
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

        audioID.addEventListener('timeupdate', () => {
            if (audioID.currentTime >= 1) {
                hasProgressed = true;
            }
        });

        audioID.addEventListener('play', () => {
            isPlaying = true;
            startTime = audioID.currentTime;
            requestWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'playing';
            }
            if (!audioStarted) {
                audioStarted = true;
                logmsg('Started ' + currentFileName);
            } else {
                logmsg('Resumed @ ' + formatTime(audioID.currentTime));
            }
        });

        audioID.addEventListener('pause', () => {
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'paused';
            }
            if (isPlaying && !isSeeking && !audioEnded && !audioID.ended) {
                totalTimeListened += Math.max(0, audioID.currentTime - startTime);
                logmsg('Paused  @ ' + formatTime(audioID.currentTime));
            }
            isPlaying = false;
        });

        audioID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout);
        });

        audioID.addEventListener('seeked', () => {
            clearTimeout(seekTimeout);
            seekTimeout = setTimeout(() => {
                isSeeking = false;
                const currentRounded = Math.round(audioID.currentTime);
                if (currentRounded === 0 && !hasProgressed) {
                    return;
                }
                if (currentRounded > 0) {
                    hasProgressed = true;
                }
                logmsg('Seeked to ' + formatTime(audioID.currentTime));
                if (isPlaying) {
                    totalTimeListened += Math.max(0, audioID.currentTime - startTime);
                    startTime = audioID.currentTime;
                }
            }, 200);
        });

        audioID.addEventListener('ended', () => {
            audioEnded = true;
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'none';
            }
            logmsg('Ended ' + currentFileName + ' @ ' + formatTime(audioID.currentTime));
            if (nextUrl) {
                navigateTrack(nextUrl, true);
            }
        });

        function logmsg(msg) {
            const fileType = 'Audio';
            const IPAddr = "<?php echo $displayIP; ?>";

            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: audioFile,
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
            if (audioStarted && !audioEnded) {
                if (isPlaying) {
                    totalTimeListened += Math.max(0, audioID.currentTime - startTime);
                }
                logmsg('user bailed @ ' + formatTime(totalTimeListened));
            }
        }

        window.addEventListener('pagehide', handlePageUnload);
        window.addEventListener('beforeunload', handlePageUnload);
    </script>
</body>

</html>