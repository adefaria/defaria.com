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
$nav = getMediaNavigation($audio);
$nextUrl = $nav['next'];
$prevUrl = $nav['prev'];
$description = getMediaDescription($audio);
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
        <h2 class="media-title"><?php echo htmlspecialchars($title); ?></h2>

        <div class="nav-controls">
            <?php if (!empty($prevUrl)): ?>
                <a href="<?php echo htmlspecialchars($prevUrl); ?>" class="nav-button prev-button">&laquo; Previous</a>
            <?php else: ?>
                <span class="nav-button disabled">&laquo; Previous</span>
            <?php endif; ?>

            <?php if (!empty($nextUrl)): ?>
                <a href="<?php echo htmlspecialchars($nextUrl); ?>" class="nav-button next-button">Next &raquo;</a>
            <?php else: ?>
                <span class="nav-button disabled">Next &raquo;</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($description)): ?>
            <div class="media-description">
                <?php echo $description; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const audioID = document.getElementById('audio');
        const audioFile = audioID.querySelector('source').getAttribute('src');
        const nextUrl = "<?php echo htmlspecialchars($nextUrl, ENT_QUOTES); ?>";
        const prevUrl = "<?php echo htmlspecialchars($prevUrl, ENT_QUOTES); ?>";
        const mediaTitle = "<?php echo htmlspecialchars($title, ENT_QUOTES); ?>";

        // Ensure playback always starts from 0 when link is clicked
        audioID.currentTime = 0;
        audioID.addEventListener('loadedmetadata', () => {
            audioID.currentTime = 0;
        });

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
        if ('mediaSession' in navigator) {
            navigator.mediaSession.metadata = new MediaMetadata({
                title: mediaTitle,
                artist: 'DeFaria.com',
                album: 'Audio Playback'
            });

            navigator.mediaSession.setActionHandler('play', () => { audioID.play(); });
            navigator.mediaSession.setActionHandler('pause', () => { audioID.pause(); });

            if (prevUrl) {
                navigator.mediaSession.setActionHandler('previoustrack', () => { window.location.href = prevUrl; });
            }
            if (nextUrl) {
                navigator.mediaSession.setActionHandler('nexttrack', () => { window.location.href = nextUrl; });
            }
        }

        let startTime = 0;
        let totalTimeListened = 0;
        let isPlaying = false;
        let audioStarted = false;
        let audioEnded = false;
        let isSeeking = false;
        let seekTimeout = null;

        audioID.addEventListener('play', () => {
            isPlaying = true;
            requestWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'playing';
            }
            if (!audioStarted) {
                audioStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(audioID.currentTime) + ' seconds');
            }
        });

        audioID.addEventListener('pause', () => {
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'paused';
            }
            if (isPlaying && !isSeeking && !audioEnded) {
                totalTimeListened = audioID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeListened) + ' seconds');
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
                logmsg('Seeked to ' + Math.round(audioID.currentTime) + ' seconds');
                if (isPlaying) {
                    totalTimeListened += audioID.currentTime - startTime;
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
            logmsg('Ended   @ ' + Math.round(audioID.currentTime) + ' seconds');
            if (nextUrl) {
                window.location.href = nextUrl;
            }
        });

        function logmsg(msg) {
            const fileType = 'Audio';
            const xhr = new XMLHttpRequest();
            const IPAddr = "<?php echo $displayIP; ?>";

            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: audioFile,
                msg: msg,
            };

            xhr.open('POST', '/php/log_action.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(data));
        }

        window.addEventListener('beforeunload', (event) => {
            releaseWakeLock();
            if (!audioEnded) {
                totalTimeListened += audioID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeListened) + ' seconds');
            }
        });
    </script>
</body>

</html>