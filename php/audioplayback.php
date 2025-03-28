<!DOCTYPE html>
<html>

<?php
if (isset($_GET['audio'])) {
    $audio = $_GET['audio'];
} else {
    echo "No audio file provided.";
    exit;
}

$IPAddr = $_SERVER["REMOTE_ADDR"];
?>

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
            background-color: black;
        }

        audio {
            width: 80%;
            max-width: 500px;
        }

        #resumeButton {
            display: none;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <audio id="audio" controls autoplay>
        <?php
        $src = "<source src=\"$audio\" type=\"audio/mpeg\">";
        echo $src;
        ?>
        Your browser does not support the audio tag.
    </audio>
    <button id="resumeButton">Resume Playback</button>
    <script>
        const audioID = document.getElementById('audio');
        const audioFile = audioID.querySelector('source').getAttribute('src');
        const resumeButton = document.getElementById('resumeButton');

        let startTime = 0;
        let totalTimeListened = 0;
        let isPlaying = false;
        let audioStarted = false;
        let audioEnded = false;
        let isSeeking = false;
        let seekTimeout = null;

        audioID.addEventListener('canplay', () => {
            debug('canplay @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
            let lastCurrentTime = localStorage.getItem('lastCurrentTime');
            debug('canplay lastCurrentTime: ' + lastCurrentTime);
            // Check if the video has been re-initialized (currentTime reset to 0)
            if (audioID.currentTime === 0 && lastCurrentTime > 0) {
                debug('Video was re-initialized. Attempting to resume from ' + Math.round(lastCurrentTime) + ' seconds');
                audioID.currentTime = lastCurrentTime; // Reset the currentTime
                audioID.play().catch(error => {
                    logmsg('Error attempting to resume playback: ' + error);
                });
            }
        });

        audioID.addEventListener('canplaythrough', () => {
            debug('canplaythrough @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
        });

        audioID.addEventListener('waiting', () => {
            debug('waiting @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
        });

        audioID.addEventListener('stalled', () => {
            debug('stalled @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
        });

        audioID.addEventListener('error', () => {
            debug('error @ ' + Math.round(audioID.currentTime) + ' seconds, code: ' + audioID.error.code + ', message: ' + audioID.error.message + ', readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
        });

        audioID.addEventListener('ratechange', () => {
            debug('ratechange @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
        });

        audioID.addEventListener('play', () => {
            isPlaying = true;
            debug('play - currentTime: ' + Math.round(audioID.currentTime) + ', readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
            if (!audioStarted) {
                audioStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(audioID.currentTime) + ' seconds');
            }
            localStorage.setItem('lastCurrentTime', audioID.currentTime);
            debug('play localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
            resumeButton.style.display = 'none';
        });

        audioID.addEventListener('pause', () => {
            debug('pause - currentTime: ' + Math.round(audioID.currentTime) + ', readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
            if (isPlaying && !isSeeking && !audioEnded) {
                totalTimeListened = audioID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeListened) + ' seconds');
            }
            localStorage.setItem('lastCurrentTime', audioID.currentTime);
            debug('pause localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
            isPlaying = false;
        });

        audioID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout);
            debug('seeking @ ' + Math.round(audioID.currentTime) + ' seconds, readyState: ' + audioID.readyState + ', networkState: ' + audioID.networkState + ', paused: ' + audioID.paused + ', ended: ' + audioID.ended + ', seeking: ' + audioID.seeking + ', duration: ' + audioID.duration);
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
            localStorage.removeItem('lastCurrentTime');
        });

        document.addEventListener('visibilitychange', () => {
            debug("visibilityState: " + document.visibilityState);
            if (document.visibilityState === 'hidden') {
                localStorage.setItem('lastCurrentTime', audioID.currentTime);
                debug('visibilitychange - hidden - localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
                //audioID.pause();
            } else if (document.visibilityState === 'visible') {
                let lastCurrentTime = localStorage.getItem('lastCurrentTime');
                debug('visibilitychange - visible - localStorage.lastCurrentTime: ' + lastCurrentTime);
                if (lastCurrentTime > 0) {
                    resumeButton.style.display = 'block';
                }
            }
        });

        resumeButton.addEventListener('click', () => {
            let lastCurrentTime = localStorage.getItem('lastCurrentTime');
            if (lastCurrentTime > 0) {
                audioID.currentTime = lastCurrentTime;
                audioID.play().catch(error => {
                    logmsg('Error attempting to resume playback: ' + error);
                });
            }
        });

        function logmsg(msg) {
            const fileType = 'Audio';
            const xhr = new XMLHttpRequest();
            const IPAddr = "<?php echo $IPAddr; ?>";

            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: audioFile,
                msg: msg,
            };

            xhr.open('POST', 'https://defaria.com:3000/log-playback', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(data));
        }

        function debug(msg) {
            logmsg("DEBUG: " + msg);
        }

        window.addEventListener('beforeunload', (event) => {
            event.preventDefault();
            event.returnValue = '';

            if (!audioEnded) {
                totalTimeListened += audioID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeListened) + ' seconds');
            }
        });
    </script>
</body>

</html>