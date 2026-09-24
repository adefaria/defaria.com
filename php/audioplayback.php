<?php
// Use __DIR__ and realpath() to construct the absolute path
require_once realpath(__DIR__ . '/ip_mapping.php');

if (isset($_GET['audio'])) {
    $audio = $_GET['audio'];
} else {
    echo "No audio file provided.";
    exit;
}

$IPAddr = $_SERVER["REMOTE_ADDR"];

// Load the IP mapping
$ipMapping = loadIpMapping($ipMappingFile);

// Replace IP with text if available
$displayIP = replaceIpWithText($IPAddr, $ipMapping);
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo basename($audio); ?></title>
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

        audio {
            width: 80%;
            max-width: 500px;
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
    <audio id="audio" controls autoplay>
        <?php
        $src = "<source src=\"$audio\" type=\"audio/mpeg\">";
        echo $src;
        ?>
        Your browser does not support the audio tag.
    </audio>

    <script>
        const audioID = document.getElementById('audio');
        const audioFile = audioID.querySelector('source').getAttribute('src');

        // Ensure any previous resume state is cleared so playback always starts from 0
        localStorage.removeItem('lastCurrentTime');

        let startTime = 0;
        let totalTimeListened = 0;
        let isPlaying = false;
        let audioStarted = false;
        let audioEnded = false;
        let isSeeking = false;
        let seekTimeout = null;

        audioID.addEventListener('play', () => {
            isPlaying = true;
            if (!audioStarted) {
                audioStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(audioID.currentTime) + ' seconds');
            }
        });

        audioID.addEventListener('pause', () => {
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
            logmsg('Ended   @ ' + Math.round(audioID.currentTime) + ' seconds');
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

        function debug(msg) {
            logmsg("DEBUG: " + msg);
        }

        window.addEventListener('beforeunload', (event) => {
            if (!audioEnded) {
                totalTimeListened += audioID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeListened) + ' seconds');
            }
        });
    </script>
</body>

</html>