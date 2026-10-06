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
$nav = getMediaNavigation($video);
$nextUrl = $nav['next'];
$prevUrl = $nav['prev'];
$description = getMediaDescription($video);
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
        const videoID = document.getElementById('video');
        const videoFile = videoID.querySelector('source').getAttribute('src');
        const nextUrl = "<?php echo htmlspecialchars($nextUrl, ENT_QUOTES); ?>";
        const prevUrl = "<?php echo htmlspecialchars($prevUrl, ENT_QUOTES); ?>";
        const mediaTitle = "<?php echo htmlspecialchars($title, ENT_QUOTES); ?>";

        // Ensure playback always starts from 0 when link is clicked
        videoID.currentTime = 0;
        videoID.addEventListener('loadedmetadata', () => {
            videoID.currentTime = 0;
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

        // --- Media Session API ---
        if ('mediaSession' in navigator) {
            navigator.mediaSession.metadata = new MediaMetadata({
                title: mediaTitle,
                artist: 'DeFaria.com',
                album: 'Video Playback'
            });

            navigator.mediaSession.setActionHandler('play', () => { videoID.play(); });
            navigator.mediaSession.setActionHandler('pause', () => { videoID.pause(); });

            if (prevUrl) {
                navigator.mediaSession.setActionHandler('previoustrack', () => { window.location.href = prevUrl; });
            }
            if (nextUrl) {
                navigator.mediaSession.setActionHandler('nexttrack', () => { window.location.href = nextUrl; });
            }
        }

        let startTime = 0;
        let totalTimeWatched = 0;
        let isPlaying = false;
        let videoStarted = false;
        let videoEnded = false;
        let isSeeking = false;
        let seekTimeout = null;

        videoID.addEventListener('play', () => {
            isPlaying = true;
            requestWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'playing';
            }
            if (!videoStarted) {
                videoStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(videoID.currentTime) + ' seconds');
            }
        });

        videoID.addEventListener('pause', () => {
            releaseWakeLock();
            if ('mediaSession' in navigator) {
                navigator.mediaSession.playbackState = 'paused';
            }
            if (isPlaying && !isSeeking && !videoEnded) {
                totalTimeWatched = videoID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeWatched) + ' seconds');
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
                logmsg('Seeked to ' + Math.round(videoID.currentTime) + ' seconds');
                if (isPlaying) {
                    totalTimeWatched += videoID.currentTime - startTime;
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
            logmsg('Ended   @ ' + Math.round(videoID.currentTime) + ' seconds');
            if (nextUrl) {
                window.location.href = nextUrl;
            }
        });

        function logmsg(msg) {
            const fileType = 'Video';
            const xhr = new XMLHttpRequest();
            const IPAddr = "<?php echo $displayIP; ?>";
            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: videoFile,
                msg: msg,
            };

            xhr.open('POST', '/php/log_action.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(data));
        }

        window.addEventListener('beforeunload', (event) => {
            releaseWakeLock();
            if (!videoEnded) {
                totalTimeWatched += videoID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeWatched) + ' seconds');
            }
        });
    </script>
</body>

</html>