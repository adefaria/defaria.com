<!DOCTYPE html>
<html>

<head>
    <title>Playback Log Viewer</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: monospace;
            white-space: pre-wrap;
            background-color: #222;
            color: #eee;
            padding: 20px;
        }

        #log-container {
            border: 1px solid #555;
            padding: 10px;
            overflow-y: auto;
            max-height: 80vh;
            background-color: #000;
            /* Black background */
            color: #0f0;
            /* Green text */
        }

        #refresh-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Playback Log</h1>
    <button id="refresh-button">Refresh Log</button>
    <div id="log-container"><?php
    $logFile = '/web/pm/playback.log';
    $numLines = 25; // Number of lines to display
    
    // Check if the log file exists and is readable.
    if (file_exists($logFile) && is_readable($logFile)) {
        // Read the log file into an array of lines.
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Get the last $numLines lines.
        $lastLines = array_slice($lines, -$numLines);

        // Display the last lines.
        foreach ($lastLines as $line) {
            echo htmlspecialchars($line) . "<br>";
        }
    } else {
        echo "Error: Log file '$logFile' does not exist or is not readable.";
    }
    ?>
    </div>

    <script>
        const logContainer = document.getElementById('log-container');
        const refreshButton = document.getElementById('refresh-button');
        const logFile = '/web/pm/playback.log';
        const numLines = 25;

        function fetchLogData() {
            fetch(`/php/getLogData.php?lines=${numLines}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching log data:', data.error);
                        return;
                    }

                    if (data.lines && data.lines.length > 0) {
                        // Clear existing content
                        logContainer.innerHTML = '';

                        // Append new lines to the log container
                        data.lines.forEach(line => {
                            const lineElement = document.createElement('div');
                            lineElement.textContent = line;
                            logContainer.appendChild(lineElement);
                        });

                        // Scroll to the bottom to show new lines
                        logContainer.scrollTop = logContainer.scrollHeight;
                    }
                })
                .catch(error => {
                    console.error('Error fetching log data:', error);
                });
        }

        refreshButton.addEventListener('click', fetchLogData);
    </script>
</body>

</html>