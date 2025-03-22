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
    <title>Video Playback</title>
    <style>
        body {
            margin: 0;
            /* Remove default body margins */
            overflow: hidden;
            /* Hide scrollbars */
        }

        video {
            width: 100vw;
            /* Make video fill the viewport width */
            object-fit: contain;
            /* Maintain aspect ratio and fit within the container */
        }
    </style>
</head>

<body>
    <video id="video" controls width="100%" height="auto" autoplay>
        <?php $src = "<source src=\"$video\" type=\"video/mp4\">";
        echo $src; ?>;
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

            // Assume a 16:9 aspect ratio (most common for widescreen videos)
            const videoAspectRatio = 16 / 9;

            // Calculate video dimensions to fit within the screen while maintaining aspect ratio
            let videoWidth = screenWidth;
            let videoHeight = screenWidth / videoAspectRatio;

            if (videoHeight > screenHeight) {
                videoHeight = screenHeight;
                videoWidth = screenHeight * videoAspectRatio;
            }

            videoID.width = Math.round(videoWidth);
            videoID.height = Math.round(videoHeight);
        }

        // Call the function to set dimensions initially
        setVideoDimensions();

        // Call the function again if the window is resized
        window.addEventListener('resize', setVideoDimensions);

        videoID.addEventListener('play', () => {
            isPlaying = true;

            if (!videoStarted) {
                videoStarted = true;
                logmsg('Started for the first time @ ' + Math.round(startTime) + ' seconds');
            } else {
                logmsg('Resumed @ ' + Math.round(videoID.currentTime) + ' seconds');
            }
        });

        videoID.addEventListener('pause', () => {
            if (isPlaying && !isSeeking && !videoEnded) {
                totalTimeWatched = videoID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeWatched) + ' seconds');
            }

            isPlaying = false;
        });

        videoID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout); // Clear any existing timeout
        });

        videoID.addEventListener('seeked', () => {
            clearTimeout(seekTimeout); // Clear any existing timeout
            seekTimeout = setTimeout(() => {
                // This code will run after a short delay (e.g., 200ms)
                isSeeking = false;
                logmsg('Seeked to ' + Math.round(videoID.currentTime) + ' seconds');
                if (isPlaying) {
                    totalTimeWatched += videoID.currentTime - startTime;
                    startTime = videoID.currentTime;
                }
            }, 200); // Adjust the delay (in milliseconds) as needed
        });

        videoID.addEventListener('ended', () => {
            videoEnded = true;

            logmsg('Ended   @ ' + Math.round(videoID.currentTime) + ' seconds');
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

        window.addEventListener('beforeunload', (event) => {
            // Cancel the event as stated by the standard.
            event.preventDefault();
            // Chrome requires returnValue to be set.
            event.returnValue = '';

            if (!videoEnded) {
                totalTimeWatched += videoID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeWatched) + ' seconds');
            }
        });
    </script>
</body>

</html>