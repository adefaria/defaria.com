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
    <title>Audio Playback</title>
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

        let startTime = 0;
        let totalTimeWatched = 0;
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
                isPlaying = false;
                totalTimeWatched += audioID.currentTime - startTime;
                logmsg('Paused  @ ' + Math.round(totalTimeWatched) + ' seconds');
            }

            isPlaying = false;
        });

        audioID.addEventListener('seeking', () => {
            isSeeking = true;
            clearTimeout(seekTimeout); // Clear any existing timeout
        });

        audioID.addEventListener('seeked', () => {
            clearTimeout(seekTimeout); // Clear any existing timeout
            seekTimeout = setTimeout(() => {
                // This code will run after a short delay (e.g., 200ms)
                isSeeking = false;
                logmsg('Seeked to ' + Math.round(audioID.currentTime) + ' seconds');
                if (isPlaying) {
                    totalTimeWatched += audioID.currentTime - startTime;
                    startTime = audioID.currentTime;
                }
            }, 200); // Adjust the delay (in milliseconds) as needed
        });

        audioID.addEventListener('ended', () => {
            audioEnded = true;

            logmsg('Ended   @ ' + Math.round(audioID.currentTime) + ' seconds');
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

        window.addEventListener('beforeunload', (event) => {
            // Cancel the event as stated by the standard.
            event.preventDefault();
            // Chrome requires returnValue to be set.
            event.returnValue = '';

            // Your code to handle the event (e.g., send data to server)
            logmsg('user bailed');
            if (!videoEnded) {
                totalTimeWatched += videoID.currentTime - startTime;
                logmsg('user bailed @ ' + Math.round(totalTimeWatched) + ' seconds');
            }
        });        
    </script>
</body>

</html>