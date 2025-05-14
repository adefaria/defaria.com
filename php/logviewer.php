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
            box-sizing: border-box;
            /* Ensures padding is included in the height */
            max-height: 80vh;
            line-height: 1.2;
            /* Explicit line-height for better calculation consistency */
            background-color: #000;
            color: #0f0;
        }

        /* Common styles for action buttons */
        .action-button {
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
        }

        #refresh-button {
            background-color: #4CAF50;
            /* Green */
        }

        #clear-log-button {
            background-color: #f44336;
            /* Red */
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <h1>Playback Log</h1>
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <button id="refresh-button" class="action-button">Refresh Log</button>
        <button id="clear-log-button" class="action-button">Empty Log</button>
    </div>
    <div id="log-container"><?php
    $logFile = '/web/pm/playback.log';
    // Check if the log file exists and is readable.
    if (file_exists($logFile) && is_readable($logFile)) {
        // Read the log file into an array of lines.
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Display all lines, each in a div for consistent handling with JS
        foreach ($lines as $line) {
            // Wrap each line in a div. Ensure htmlspecialchars is used.
            echo "<div>" . htmlspecialchars($line) . "</div>";
        }
    } else {
        echo "Error: Log file '$logFile' does not exist or is not readable.";
    }
    ?>
    </div>

    <script>
        const logContainer = document.getElementById('log-container');
        const refreshButton = document.getElementById('refresh-button');
        const clearLogButton = document.getElementById('clear-log-button');
        const linesInViewport = 25; // Desired number of text lines in the viewport

        function calculateAndSetContainerHeight(container, numTextLines) {
            if (!container || numTextLines <= 0) return;

            let singleLineHeight = 0;
            const computedContainerStyle = window.getComputedStyle(container);

            // Get the computed font-size of the container itself.
            // This should resolve to a pixel value (e.g., "16px").
            const containerFontSizePxString = computedContainerStyle.fontSize;
            const containerFontSizePx = parseFloat(containerFontSizePxString);

            console.log(`Container computed font-size string: ${containerFontSizePxString}`);

            if (isNaN(containerFontSizePx) || containerFontSizePx <= 0) {
                console.error("Could not determine container font size in pixels. Aborting height calculation.");
                // As a last resort, try to set a reasonable fixed height if font size fails
                // This is a fallback to prevent the container from having zero or undefined height.
                // 16px (typical default font) * 1.2 line-height * 25 lines + 20px padding
                container.style.height = `${(16 * 1.2 * 25) + 20}px`;
                console.log(`Fell back to a default calculated height: ${container.style.height}`);
                return;
            }

            // The line-height is set to 1.2 (unitless) in CSS for #log-container.
            // This means 1.2 * font-size.
            singleLineHeight = Math.round(containerFontSizePx * 1.2);
            console.log(`Calculated singleLineHeight based on container font-size (${containerFontSizePx}px) and line-height 1.2: ${singleLineHeight}px`);

            // Ensure singleLineHeight is a positive integer, as offsetHeight would be.
            singleLineHeight = Math.max(1, Math.round(singleLineHeight));
            if (singleLineHeight <= 0) {
                console.error("Calculated singleLineHeight is not positive. Aborting.");
                // Fallback height if singleLineHeight is invalid
                container.style.height = `${(16 * 1.2 * 25) + 20}px`;
                console.log(`Fell back to a default calculated height due to invalid singleLineHeight: ${container.style.height}`);
                return;
            }

            // This is the height needed for the text content itself
            const contentHeightForText = singleLineHeight * numTextLines;
            console.log(`Calculated contentHeightForText for ${numTextLines} lines: ${contentHeightForText}px`);

            let targetHeight;
            // If box-sizing is border-box, the style.height includes padding.
            // So, we need to add padding to the contentHeightForText.
            if (computedContainerStyle.boxSizing === 'border-box') {
                const paddingTop = parseFloat(computedContainerStyle.paddingTop) || 0;
                const paddingBottom = parseFloat(computedContainerStyle.paddingBottom) || 0;
                targetHeight = contentHeightForText + paddingTop + paddingBottom;
                console.log(`Box-sizing: border-box. PaddingTop: ${paddingTop}px, PaddingBottom: ${paddingBottom}px. TargetHeight: ${targetHeight}px`);
            } else { // content-box (default)
                targetHeight = contentHeightForText;
                console.log(`Box-sizing: content-box. TargetHeight: ${targetHeight}px`);
            }

            container.style.height = targetHeight + 'px';
            // Note: The CSS 'max-height: 80vh' will still cap this if targetHeight is larger.
            console.log(`Set container style.height to: ${targetHeight}px`);

            // For debugging, let's see what the browser says its height is AFTER we set it.
            // And also check scrollHeight vs clientHeight
            requestAnimationFrame(() => {
                const newComputedStyle = window.getComputedStyle(container);
                console.log(`After setting style.height, container computed CSS height: ${newComputedStyle.height}, clientHeight: ${container.clientHeight}px, scrollHeight: ${container.scrollHeight}px`);
                if (container.scrollHeight > container.clientHeight) {
                    console.log("Content is overflowing the container (scrollbar expected).");
                } else {
                    console.log("Content fits within the container (no scrollbar expected).");
                }
            });
        }

        function scrollToBottom(container) {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        function fetchLogData() {
            // Clear the log container immediately at the start of a refresh
            logContainer.innerHTML = '';

            fetch(`/php/getLogData.php`, { cache: 'no-store' }) // Fetch all lines, ensuring no cache is used
                .then(response => {
                    if (!response.ok) {
                        // Handle HTTP errors (e.g., 404, 500) before trying to parse JSON
                        throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
                    }
                    return response.json(); // Proceed to parse JSON only if response is ok
                })
                .then(data => {
                    // logContainer is already cleared at this point.
                    if (data.error) {
                        console.error('Application error from getLogData.php:', data.error);
                        logContainer.textContent = `Error loading log: ${data.error}`; // Display error
                        return;
                    }

                    if (data.lines && data.lines.length > 0) {
                        // Append new lines to the log container
                        data.lines.forEach(line => {
                            const lineElement = document.createElement('div');
                            lineElement.textContent = line; // PHP already did htmlspecialchars
                            logContainer.appendChild(lineElement);
                        });
                    } else {
                        // If no lines and no error, it means the log is empty.
                        // logContainer is already empty, which is correct.
                    }
                })
                .catch(error => {
                    // Catches network errors, HTTP errors thrown above, or JSON parsing errors
                    console.error('Failed to fetch or process log data:', error);
                    logContainer.textContent = `Failed to load log data. Error: ${error.message}`;
                })
                .finally(() => {
                    // Always scroll to bottom after attempting to fetch and process data
                    scrollToBottom(logContainer);
                });
        }

        async function clearLogFile() {
            if (!confirm('Are you sure you want to clear the log file? This action cannot be undone.')) {
                return;
            }
            try {
                const response = await fetch('/php/clearLog.php', { method: 'POST' });
                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'Log file action completed.');
                    fetchLogData(); // Refresh the log view to show it's empty
                } else {
                    alert('Error: ' + (result.error || 'Unknown error occurred while clearing log.'));
                }
            } catch (error) {
                console.error('Error calling clearLog.php:', error);
                alert('Failed to communicate with the server to clear the log file. See console for details.');
            }
        }

        // Initial scroll on page load
        document.addEventListener('DOMContentLoaded', () => {
            calculateAndSetContainerHeight(logContainer, linesInViewport);
            scrollToBottom(logContainer); // Scroll after initial PHP content is rendered and height is set
        });

        refreshButton.addEventListener('click', fetchLogData);
        clearLogButton.addEventListener('click', clearLogFile);
    </script>
</body>

</html>