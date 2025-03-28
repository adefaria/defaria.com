<!DOCTYPE html>
<html>

<?php
if (isset($_GET['video'])) {
    $video = $_GET['video'];
} else {
    echo "No video file provided.";
    exit;
}

$IPAddr = $_SERVER["REMOTE_ADDR"];
?>

<head>
    <title><?php echo basename($video); ?></title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: black;
        }

        video {
            width: 100vw;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <video id="video" controls width="100%" height="auto" autoplay>
        <?php $src = "<source src=\"$video\" type=\"video/mp4\">";
        echo $src; ?>
        Your browser does not support the video tag.
    </video>
    <script>
        const videoID = document.getElementById('video');
        const videoFile = videoID.querySelector('source').getAttribute('src');

        let startTime = 0;
        let totalTimeWatched = 0;
        let isPlaying = false;
        let videoStarted = false;
        let videoEnded = false;
        let isSeeking = false;
        let seekTimeout = null;

        function setVideoDimensions() {
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;
            const videoAspectRatio = 16 / 9;
            let videoWidth = screenWidth;
            let videoHeight = screenWidth / videoAspectRatio;

            if (videoHeight > screenHeight) {
                videoHeight = screenHeight;
                videoWidth = screenHeight * videoAspectRatio;
            }

            videoID.width = Math.round(videoWidth);
            videoID.height = Math.round(videoHeight);
        }

        setVideoDimensions();
        window.addEventListener('resize', setVideoDimensions);

        videoID.addEventListener('canplay', () => {
            debug('canplay @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
            let lastCurrentTime = localStorage.getItem('lastCurrentTime');
            debug('canplay lastCurrentTime: ' + lastCurrentTime);
            // Check if the video has been re-initialized (currentTime reset to 0)
            if (videoID.currentTime === 0 && lastCurrentTime > 0) {
                debug('Video was re-initialized. Attempting to resume from ' + Math.round(lastCurrentTime) + ' seconds');
                videoID.currentTime = lastCurrentTime; // Reset the currentTime
                videoID.play().catch(error => {
                    logmsg('Error attempting to resume playback: ' + error);
                });
            }
        });

        videoID.addEventListener('canplaythrough', () => {
            debug('canplaythrough @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
        });

        videoID.addEventListener('waiting', () => {
            debug('waiting @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
        });

        videoID.addEventListener('stalled', () => {
            debug('stalled @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
        });

        videoID.addEventListener('error', () => {
            debug('error @ ' + Math.round(videoID.currentTime) + ' seconds, code: ' + videoID.error.code + ', message: ' + videoID.error.message + ', readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
        });

        videoID.addEventListener('ratechange', () => {
            videoID.currentTime;
            debug('ratechange @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
        });

        videoID.addEventListener('play', () => {
            isPlaying = true;
            debug('play - currentTime: ' + Math.round(videoID.currentTime) + ', readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
            if (!videoStarted) {
                videoStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(videoID.currentTime) + ' seconds');
            }
            localStorage.setItem('lastCurrentTime', videoID.currentTime);
            debug('setItem localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
        });

        videoID.addEventListener('pause', () => {
            debug('pause - currentTime: ' + Math.round(videoID.currentTime) + ', readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
            if (isPlaying && !isSeeking && !videoEnded) {
                totalTimeWatched = videoID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeWatched) + ' seconds');
            }
            localStorage.setItem('lastCurrentTime', videoID.currentTime);
            debug('setItem localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
            isPlaying = false;
        });

        videoID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout);
            debug('seeking @ ' + Math.round(videoID.currentTime) + ' seconds, readyState: ' + videoID.readyState + ', networkState: ' + videoID.networkState + ', paused: ' + videoID.paused + ', ended: ' + videoID.ended + ', seeking: ' + videoID.seeking + ', duration: ' + videoID.duration);
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
            logmsg('Ended   @ ' + Math.round(videoID.currentTime) + ' seconds');
            localStorage.removeItem('lastCurrentTime');
        });

        document.addEventListener('visibilitychange', () => {
            debug("visibilityState: " + document.visibilityState);
            if (document.visibilityState === 'hidden') {
                //localStorage.setItem('lastCurrentTime', videoID.currentTime);
                //debug('setItem localStorage.lastCurrentTime: ' + localStorage.getItem('lastCurrentTime'));
                debug('visibilitychange - hidden');
                videoID.pause();
            } else if (document.visibilityState === 'visible') {
                let lastCurrentTime = localStorage.getItem('lastCurrentTime');
                debug('visibilitychange - visible - localStorage.lastCurrentTime: ' + lastCurrentTime);
                if (lastCurrentTime > 0) {
                    videoID.currentTime = lastCurrentTime;
                    debug('calling play with currentTime set to ' + videoID.currentTime);
                    videoID.play().catch(error => {
                        logmsg('Error attempting to resume playback: ' + error);
                    });
                }
            }
        });

        function logmsg(msg) {
            const fileType = 'Video';
            const xhr = new XMLHttpRequest();
            const IPAddr = "<?php echo $IPAddr; ?>";
            const data = {
                IPAddr: IPAddr,
                fileType: fileType,
                file: videoFile,
                msg: msg,
            };

            xhr.open('POST', 'https://defaria.com:3000/log-playback', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(data));
        }

        function debug(msg) {
            return;
            logmsg("DEBUG: " + msg);
        }

        window.addEventListener('beforeunload', (event) => {
            event.preventDefault();
            event.returnValue = '';

            if (!videoEnded) {
                totalTimeWatched += videoID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeWatched) + ' seconds');
            }
            debug('removing item lastCurrentTime');
            localStorage.setItem('lastCurrentTime', 0);
        });

    </script>
</body>

</html>