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
            color: #0f0;
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
    <div id="log-container">
        <!-- Log content will be loaded here -->
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
                        logContainer.innerHTML = `<p style="color: red;">Error: ${data.error}</p>`;
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
                    logContainer.innerHTML = `<p style="color: red;">Error: ${error}</p>`;
                });
        }

        // Initial load of log data
        fetchLogData();

        refreshButton.addEventListener('click', fetchLogData);
    </script>
</body>
</html>
