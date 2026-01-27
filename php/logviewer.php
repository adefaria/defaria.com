<!DOCTYPE html>
<html>

<head>
    <title>Playback Log Viewer</title>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: white;
            color: #333;
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        #log-container {
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            box-sizing: border-box;
            max-height: 80vh;
            line-height: 1.2;
            background-color: #f9f9f9;
            color: #333;
            font-family: monospace;
            white-space: pre-wrap;
            border-radius: 4px;
        }

        .button-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .action-button {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .action-button:hover {
            transform: scale(1.05);
        }

        #refresh-button {
            background-color: #4CAF50;
            /* Green */
        }

        #refresh-button:hover {
            background-color: #45a049;
        }

        #clear-log-button {
            background-color: #f44336;
            /* Red */
        }

        #clear-log-button:hover {
            background-color: #d32f2f;
        }

        /* Dark Mode */
        .dark-mode body {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        .dark-mode h1 {
            color: #e0e0e0 !important;
        }

        .dark-mode #log-container {
            background-color: #000;
            color: #0f0;
            /* Terminal look for logs in dark mode */
            border-color: #333;
        }
    </style>
</head>

<body>
    <h1>
        Playback Log (v2)
        <span id="connection-status" title="Connected"
            style="display:inline-block; width:10px; height:10px; border-radius:50%; background-color:#4CAF50; margin-left:10px;"></span>
    </h1>
    <div class="button-container">
        <button id="refresh-button" class="action-button">Refresh Log</button>
        <button id="clear-log-button" class="action-button">Empty Log</button>
    </div>
    <div id="log-container"></div>

    <script>
        const logContainer = document.getElementById('log-container');
        const refreshButton = document.getElementById('refresh-button');
        const clearLogButton = document.getElementById('clear-log-button');
        const statusDot = document.getElementById('connection-status');
        const linesInViewport = 25;
        let displayedLineCount = 0;
        let eventSource = null;

        // ... (theme sync omitted, assume existing) ...

        // ... (calculateHeight omitted) ...

        function setStatus(state) {
            if (!statusDot) return;
            if (state === 'connected') {
                statusDot.style.backgroundColor = '#4CAF50'; // Green
                statusDot.title = 'Connected';
            } else if (state === 'connecting') {
                statusDot.style.backgroundColor = '#FFC107'; // Yellow
                statusDot.title = 'Connecting...';
            } else {
                statusDot.style.backgroundColor = '#f44336'; // Red
                statusDot.title = 'Disconnected';
            }
        }

        // ... (rest of code needs verify) ...

        // Theme Sync Logic
        function updateTheme() {
            try {
                const parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                const isLight = parentTheme === 'light';

                if (isLight) {
                    document.documentElement.classList.remove('dark-mode');
                } else {
                    document.documentElement.classList.add('dark-mode');
                }
            } catch (e) {
                console.log('Cannot access parent theme, falling back to system preference.');
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.classList.remove('dark-mode');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', updateTheme);

        try {
            const observer = new MutationObserver(updateTheme);
            observer.observe(window.parent.document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        } catch (e) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateTheme);
        }

        function calculateAndSetContainerHeight(container, numTextLines) {
            if (!container || numTextLines <= 0) return;
            let singleLineHeight = 0;
            const computedContainerStyle = window.getComputedStyle(container);
            const containerFontSizePxString = computedContainerStyle.fontSize;
            const containerFontSizePx = parseFloat(containerFontSizePxString);

            if (isNaN(containerFontSizePx) || containerFontSizePx <= 0) {
                container.style.height = `${(16 * 1.2 * 25) + 20}px`;
                return;
            }
            singleLineHeight = Math.round(containerFontSizePx * 1.2);
            singleLineHeight = Math.max(1, Math.round(singleLineHeight));
            const contentHeightForText = singleLineHeight * numTextLines;
            let targetHeight;
            if (computedContainerStyle.boxSizing === 'border-box') {
                const paddingTop = parseFloat(computedContainerStyle.paddingTop) || 0;
                const paddingBottom = parseFloat(computedContainerStyle.paddingBottom) || 0;
                targetHeight = contentHeightForText + paddingTop + paddingBottom;
            } else {
                targetHeight = contentHeightForText;
            }
            container.style.height = targetHeight + 'px';
        }

        function scrollToBottom(container) {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        function connectEventSource() {
            if (eventSource) {
                eventSource.close();
            }

            eventSource = new EventSource('/php/logstream.php');
            setStatus('connecting');
            // logContainer.innerHTML = '<div>Connecting to log stream...</div>'; // Removed text clutter

            // Only clear log on explicit refresh or clear, not reconnect?
            // Actually, keep logic simple. If connecting, we wait for data.
            // If displayedLineCount is 0, maybe show "Waiting for logs..."?

            eventSource.onopen = function () {
                setStatus('connected');
            };

            eventSource.addEventListener('logupdate', function (event) {
                setStatus('connected');
                const data = JSON.parse(event.data);
                handleLogData(data);
            });

            eventSource.onerror = function (err) {
                // console.error("EventSource failed:", err);
                if (eventSource.readyState === EventSource.CONNECTING) {
                    setStatus('connecting');
                    // Silent retry
                } else if (eventSource.readyState === EventSource.CLOSED) {
                    setStatus('disconnected');
                    // Optional: Append error if permanent
                    // logContainer.innerHTML += '<div style="color:red">Connection closed.</div>'; 
                }
            };
        }

        function handleLogData(data) {
            if (!data || !data.type) return;

            if (logContainer.children.length === 1 &&
                (logContainer.firstChild.textContent.startsWith('Connecting') || logContainer.firstChild.textContent.startsWith('Log is currently empty'))) {
                logContainer.innerHTML = '';
            }

            switch (data.type) {
                case 'full_log':
                case 'truncated':
                    logContainer.innerHTML = '';
                    if (data.lines && data.lines.length > 0) {
                        data.lines.forEach(line => {
                            const lineElement = document.createElement('div');
                            lineElement.textContent = line;
                            logContainer.appendChild(lineElement);
                        });
                        displayedLineCount = data.lines.length;
                    } else {
                        logContainer.innerHTML = `<div>${data.message || 'Log is currently empty.'}</div>`;
                        displayedLineCount = 0;
                    }
                    scrollToBottom(logContainer);
                    return;
                case 'new_lines':
                    if (data.lines && data.lines.length > 0) {
                        data.lines.forEach(line => {
                            const lineElement = document.createElement('div');
                            lineElement.textContent = line;
                            logContainer.appendChild(lineElement);
                        });
                        displayedLineCount += data.lines.length;
                        scrollToBottom(logContainer);
                    }
                    return;
                case 'log_cleared_or_missing':
                    logContainer.innerHTML = `<div>${data.message || 'Log file cleared or is missing.'}</div>`;
                    displayedLineCount = 0;
                    return;
                case 'log_moved_or_deleted':
                case 'error':
                    logContainer.innerHTML += `<div style="color: red;">${data.message}</div>`;
                    if (eventSource && data.type === 'error') eventSource.close();
                    return;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            calculateAndSetContainerHeight(logContainer, linesInViewport);
            connectEventSource();
            // Re-calc height on resize in case font size changes
            window.addEventListener('resize', () => calculateAndSetContainerHeight(logContainer, linesInViewport));
        });

        refreshButton.addEventListener('click', () => {
            connectEventSource();
        });

        clearLogButton.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to clear the log file? This action cannot be undone.')) {
                return;
            }
            try {
                const response = await fetch('/php/clearLog.php', { method: 'POST' });
                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'Log file action completed.');
                    logContainer.innerHTML = '';
                    displayedLineCount = 0;
                } else {
                    alert('Error: ' + (result.error || 'Unknown error occurred while clearing log.'));
                }
            } catch (error) {
                alert('Failed to communicate with the server to clear the log file.');
            } finally {
                connectEventSource(); // Reconnect to get fresh state/file creation if needed
            }
        });
    </script>
</body>

</html>