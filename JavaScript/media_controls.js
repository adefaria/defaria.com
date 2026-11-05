/**
 * media_controls.js
 * Comprehensive media player keyboard shortcuts, A-B looping, toast feedback,
 * and help dialog (?).
 * Ported and adapted from /opt/songbook/web/songbook.js.
 */

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.initMediaControls = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {

    function formatTime(seconds) {
        if (isNaN(seconds) || seconds === null) return "not set";
        const total = Math.max(0, Math.floor(seconds));
        const mins = Math.floor(total / 60);
        const secs = total % 60;
        return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }

    function parseTime(input) {
        if (typeof input === 'number') return input;
        if (!input) return null;
        input = String(input).trim();
        if (!input || input.toLowerCase() === 'not set') return null;

        const parts = input.split(':').map(p => p.trim());
        if (parts.length === 1) {
            const secs = parseFloat(parts[0]);
            return isNaN(secs) ? null : Math.max(0, secs);
        } else if (parts.length === 2) {
            const mins = parseFloat(parts[0]);
            const secs = parseFloat(parts[1]);
            if (isNaN(mins) || isNaN(secs)) return null;
            return Math.max(0, mins * 60 + secs);
        } else if (parts.length === 3) {
            const hours = parseFloat(parts[0]);
            const mins = parseFloat(parts[1]);
            const secs = parseFloat(parts[2]);
            if (isNaN(hours) || isNaN(mins) || isNaN(secs)) return null;
            return Math.max(0, hours * 3600 + mins * 60 + secs);
        }
        return null;
    }

    return function initMediaControls(options) {
        options = options || {};
        const media = options.media || document.querySelector('audio, video');
        if (!media) {
            console.warn('initMediaControls: No audio or video element found.');
            return null;
        }

        const skipSeconds = options.skipSeconds || 10;
        const enableFullscreen = !!options.enableFullscreen;
        const enableMute = !!options.enableMute;
        const markAElement = options.markA || document.getElementById('mark-a');
        const markBElement = options.markB || document.getElementById('mark-b');
        const markABtn = options.markABtn || document.getElementById('btn-mark-a');
        const markBBtn = options.markBBtn || document.getElementById('btn-mark-b');
        const clearBtn = options.clearBtn || document.getElementById('clear-loop-button');
        const helpButton = options.helpButton || document.getElementById('help-button');
        const onDownload = typeof options.onDownload === 'function' ? options.onDownload : null;

        let startTime = 0;
        let endTime = 0;
        let isMarkASet = false;
        let isMarkBSet = false;
        let loopInterval = null;
        let toastTimeout = null;

        // --- Prevent browser player controls from holding focus ---
        media.addEventListener('focus', function () {
            this.blur();
        });

        // --- Toast feedback container ---
        let toastEl = document.getElementById('media-toast');
        if (!toastEl) {
            toastEl = document.createElement('div');
            toastEl.id = 'media-toast';
            toastEl.className = 'media-toast';
            document.body.appendChild(toastEl);
        }

        function showToast(msg) {
            if (!toastEl) return;
            toastEl.textContent = msg;
            toastEl.classList.add('visible');
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toastEl.classList.remove('visible');
            }, 1400);
        }

        // --- Help Modal Dialog ---
        let helpModal = document.getElementById('media-help-modal');
        function buildHelpModal() {
            if (helpModal) return helpModal;
            helpModal = document.createElement('div');
            helpModal.id = 'media-help-modal';
            helpModal.className = 'media-help-overlay';

            const videoRows = enableFullscreen ? '<tr><td><kbd>F</kbd></td><td>Toggle Fullscreen</td></tr>' : '';
            const muteRows = enableMute ? '<tr><td><kbd>M</kbd></td><td>Toggle Mute</td></tr>' : '';

            helpModal.innerHTML = `
                <div class="media-help-card" role="dialog" aria-modal="true" aria-labelledby="media-help-title">
                    <div class="media-help-header">
                        <h3 id="media-help-title">Keyboard Shortcuts</h3>
                        <button type="button" class="media-help-close" title="Close (Esc)">&times;</button>
                    </div>
                    <div class="media-help-body">
                        <table class="media-help-table">
                            <tbody>
                                <tr><td><kbd>Space</kbd></td><td>Play / Pause</td></tr>
                                <tr><td><kbd>&larr;</kbd> / <kbd>&rarr;</kbd></td><td>Skip ${skipSeconds}s back / forward</td></tr>
                                <tr><td><kbd>0</kbd> – <kbd>9</kbd></td><td>Jump to 0% – 90% of duration</td></tr>
                                <tr><td><kbd>A</kbd></td><td>Set Mark A (loop start)</td></tr>
                                <tr><td><kbd>B</kbd></td><td>Set Mark B (loop end)</td></tr>
                                <tr><td><kbd>C</kbd></td><td>Clear A-B loop markers</td></tr>
                                <tr><td><kbd>R</kbd></td><td>Return to start (or Mark A) &amp; play</td></tr>
                                <tr><td><kbd>D</kbd></td><td>Download media file</td></tr>
                                ${videoRows}
                                ${muteRows}
                                <tr><td><kbd>?</kbd></td><td>Toggle this Help screen</td></tr>
                                <tr><td><kbd>Esc</kbd></td><td>Close Help screen</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            document.body.appendChild(helpModal);

            const closeBtn = helpModal.querySelector('.media-help-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => toggleHelpModal(false));
            }
            helpModal.addEventListener('click', (e) => {
                if (e.target === helpModal) {
                    toggleHelpModal(false);
                }
            });

            return helpModal;
        }

        function toggleHelpModal(show) {
            const modal = buildHelpModal();
            const shouldShow = typeof show === 'boolean' ? show : !modal.classList.contains('visible');
            if (shouldShow) {
                modal.classList.add('visible');
            } else {
                modal.classList.remove('visible');
            }
        }

        if (helpButton) {
            helpButton.addEventListener('click', (e) => {
                e.preventDefault();
                toggleHelpModal(true);
            });
        }

        function updateMarkerUI() {
            if (markAElement) {
                if (markAElement.querySelector('input')) return;
                if (isMarkASet) {
                    markAElement.textContent = formatTime(startTime);
                    markAElement.classList.add('set');
                } else {
                    markAElement.textContent = 'not set';
                    markAElement.classList.remove('set');
                }
            }
            if (markBElement) {
                if (markBElement.querySelector('input')) return;
                if (isMarkBSet && endTime > 0 && endTime <= (media.duration || Infinity)) {
                    markBElement.textContent = formatTime(endTime);
                    markBElement.classList.add('set');
                } else {
                    markBElement.textContent = 'not set';
                    markBElement.classList.remove('set');
                }
            }
        }

        function checkLoop() {
            if (isMarkBSet && endTime > 0 && (!isMarkASet || startTime < endTime) && media.currentTime >= endTime) {
                media.currentTime = isMarkASet ? startTime : 0;
            }
        }

        media.addEventListener('loadedmetadata', function () {
            if (endTime === 0 || endTime > media.duration) {
                endTime = media.duration || 0;
            }
        });

        media.addEventListener('play', function () {
            clearInterval(loopInterval);
            loopInterval = setInterval(checkLoop, 200);
        });

        media.addEventListener('pause', function () {
            clearInterval(loopInterval);
        });

        let activeEditFinish = null;

        function cancelActiveEdit() {
            if (activeEditFinish) {
                activeEditFinish(false, true);
                activeEditFinish = null;
            }
        }

        function resetLoop() {
            cancelActiveEdit();
            startTime = 0;
            endTime = media.duration || 0;
            isMarkASet = false;
            isMarkBSet = false;
            updateMarkerUI();
        }

        function setMarkA(val) {
            const newTime = typeof val === 'number' ? val : media.currentTime;
            if (isMarkBSet && newTime >= endTime) {
                isMarkASet = false;
                startTime = 0;
                isMarkBSet = false;
                endTime = media.duration || 0;
                updateMarkerUI();
                showToast('Invalid range: Mark A & B unset');
                return;
            }
            startTime = newTime;
            isMarkASet = true;
            updateMarkerUI();
            showToast(`Mark A: ${formatTime(startTime)}`);
        }

        function setMarkB(val) {
            const newTime = typeof val === 'number' ? val : media.currentTime;
            if (isMarkASet && newTime <= startTime) {
                isMarkASet = false;
                startTime = 0;
                isMarkBSet = false;
                endTime = media.duration || 0;
                updateMarkerUI();
                showToast('Invalid range: Mark A & B unset');
                return;
            }
            endTime = newTime;
            isMarkBSet = true;
            updateMarkerUI();
            showToast(`Mark B: ${formatTime(endTime)}`);
        }

        function toggleMarkA() {
            cancelActiveEdit();
            if (isMarkASet) {
                isMarkASet = false;
                startTime = 0;
                updateMarkerUI();
                showToast('Mark A unset');
            } else {
                setMarkA(media.currentTime);
            }
        }

        function toggleMarkB() {
            cancelActiveEdit();
            if (isMarkBSet) {
                isMarkBSet = false;
                endTime = media.duration || 0;
                updateMarkerUI();
                showToast('Mark B unset');
            } else {
                setMarkB(media.currentTime);
            }
        }

        function clearLoop() {
            resetLoop();
            showToast('A-B Loop cleared');
        }

        function startEditingMarker(isMarkerA) {
            const el = isMarkerA ? markAElement : markBElement;
            if (!el || el.querySelector('input')) return;

            cancelActiveEdit();

            let initialText = '';
            if (isMarkerA) {
                initialText = isMarkASet ? formatTime(startTime) : (media.currentTime > 0 ? formatTime(media.currentTime) : '00:00');
            } else {
                initialText = (isMarkBSet && endTime > 0 && endTime <= (media.duration || Infinity))
                    ? formatTime(endTime)
                    : (media.currentTime > 0 ? formatTime(media.currentTime) : '00:00');
            }

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'mark-val-input';
            input.value = initialText;
            input.title = 'Enter time (e.g. 9, 0:09, 1:30)';

            el.textContent = '';
            el.appendChild(input);
            input.focus();
            input.select();

            let committed = false;

            function finish(save, wasCancelled) {
                if (committed) return;
                committed = true;
                activeEditFinish = null;

                const raw = input.value.trim();
                if (input.parentNode) {
                    input.parentNode.removeChild(input);
                }

                if (!wasCancelled) {
                    if (save) {
                        if (!raw) {
                            if (isMarkerA) {
                                isMarkASet = false;
                                startTime = 0;
                                showToast('Mark A unset');
                            } else {
                                isMarkBSet = false;
                                endTime = media.duration || 0;
                                showToast('Mark B unset');
                            }
                        } else {
                            const parsed = parseTime(raw);
                            if (parsed !== null && !isNaN(parsed)) {
                                const targetTime = Math.min(media.duration || Infinity, Math.max(0, parsed));
                                if (isMarkerA) {
                                    setMarkA(targetTime);
                                } else {
                                    setMarkB(targetTime);
                                }
                            } else {
                                if (isMarkerA) {
                                    isMarkASet = false;
                                    startTime = 0;
                                    showToast('Invalid time: Mark A unset');
                                } else {
                                    isMarkBSet = false;
                                    endTime = media.duration || 0;
                                    showToast('Invalid time: Mark B unset');
                                }
                            }
                        }
                    }
                }
                updateMarkerUI();
            }

            activeEditFinish = finish;

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    finish(true, false);
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    finish(false, true);
                }
                e.stopPropagation();
            });

            input.addEventListener('blur', function () {
                finish(true, false);
            });

            input.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        if (markAElement) {
            markAElement.title = 'Click to edit time';
            markAElement.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                startEditingMarker(true);
            });
        }

        if (markBElement) {
            markBElement.title = 'Click to edit time';
            markBElement.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                startEditingMarker(false);
            });
        }

        if (markABtn) {
            markABtn.addEventListener('click', function (e) {
                e.preventDefault();
                toggleMarkA();
            });
        }

        if (markBBtn) {
            markBBtn.addEventListener('click', function (e) {
                e.preventDefault();
                toggleMarkB();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('mousedown', function () {
                clearLoop();
            });
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                clearLoop();
            });
        }

        // --- Keyboard Handler ---
        function handleKeyDown(e) {
            // Escape key closes help modal if open
            if (e.code === 'Escape') {
                if (helpModal && helpModal.classList.contains('visible')) {
                    e.preventDefault();
                    toggleHelpModal(false);
                    return;
                }
            }

            // Ignore when typing inside form elements
            const target = e.target;
            const tag = target && target.tagName ? target.tagName.toLowerCase() : '';
            if (tag === 'input' || tag === 'textarea' || (target && target.isContentEditable)) {
                return;
            }

            // Ignore Ctrl, Alt, Meta combinations
            if (e.ctrlKey || e.altKey || e.metaKey) {
                return;
            }

            // Help key "?" (Shift + Slash or key '?')
            if (e.key === '?' || (e.shiftKey && (e.code === 'Slash' || e.key === '/'))) {
                e.preventDefault();
                toggleHelpModal();
                return;
            }

            // Digit keys 0-9: jump to percentage
            if (/^[0-9]$/.test(e.key)) {
                e.preventDefault();
                const digit = parseInt(e.key, 10);
                const fraction = digit * 0.10;
                if (media.duration && !isNaN(media.duration)) {
                    media.currentTime = media.duration * fraction;
                } else if (digit === 0) {
                    media.currentTime = 0;
                }
                media.muted = false;
                media.play().catch(() => {});
                showToast(`Jump to ${digit * 10}%`);
                return;
            }

            switch (e.code) {
                case 'Space':
                    e.preventDefault();
                    media.muted = false;
                    if (media.paused) {
                        if (isMarkASet && media.currentTime < startTime) {
                            media.currentTime = startTime;
                        } else if (media.ended || (media.duration && media.currentTime >= media.duration && !isMarkASet)) {
                            media.currentTime = 0;
                        }
                        media.play().catch(() => {});
                        showToast('Play');
                    } else {
                        media.pause();
                        showToast('Pause');
                    }
                    break;

                case 'ArrowLeft':
                    e.preventDefault();
                    media.currentTime = Math.max(0, media.currentTime - skipSeconds);
                    media.muted = false;
                    if (media.paused) media.play().catch(() => {});
                    showToast(`-${skipSeconds}s`);
                    break;

                case 'ArrowRight':
                    e.preventDefault();
                    media.currentTime = Math.min(media.duration || Infinity, media.currentTime + skipSeconds);
                    media.muted = false;
                    if (media.paused) media.play().catch(() => {});
                    showToast(`+${skipSeconds}s`);
                    break;

                case 'KeyA': // Toggle Mark A
                    e.preventDefault();
                    toggleMarkA();
                    break;

                case 'KeyB': // Toggle Mark B
                    e.preventDefault();
                    toggleMarkB();
                    break;

                case 'KeyC': // Clear A-B Loop
                    e.preventDefault();
                    clearLoop();
                    break;

                case 'KeyR': // Return to start and play
                    e.preventDefault();
                    if (isMarkASet) {
                        media.currentTime = startTime;
                        showToast(`Return to Mark A (${formatTime(startTime)})`);
                    } else {
                        media.currentTime = 0;
                        showToast('Return to start');
                    }
                    media.muted = false;
                    const playPromise = media.play();
                    if (playPromise !== undefined) {
                        playPromise.catch((err) => {
                            console.warn('Playback on Return failed:', err);
                        });
                    }
                    break;

                case 'KeyD': // Download
                    e.preventDefault();
                    if (onDownload) {
                        showToast('Downloading...');
                        onDownload();
                    } else {
                        const link = document.getElementById('download-link');
                        if (link) {
                            showToast('Downloading...');
                            link.click();
                        }
                    }
                    break;

                case 'KeyF': // Fullscreen (for video)
                    if (enableFullscreen) {
                        e.preventDefault();
                        if (!document.fullscreenElement) {
                            const req = media.requestFullscreen || media.webkitRequestFullscreen || media.msRequestFullscreen;
                            if (req) {
                                req.call(media).catch(() => {});
                                showToast('Fullscreen');
                            }
                        } else {
                            if (document.exitFullscreen) {
                                document.exitFullscreen().catch(() => {});
                                showToast('Exit Fullscreen');
                            }
                        }
                    }
                    break;

                case 'KeyM': // Mute / Unmute
                    if (enableMute) {
                        e.preventDefault();
                        media.muted = !media.muted;
                        showToast(media.muted ? 'Muted' : 'Unmuted');
                    }
                    break;
            }
        }

        // --- Tap / Click on page to Play/Pause (matching Songbook behavior) ---
        function handleBodyClick(e) {
            const target = e.target;
            const tagName = target.tagName ? target.tagName.toLowerCase() : '';

            // Do NOT toggle if clicking interactive elements, controls, markers, or dialogs
            if (tagName === 'a' ||
                tagName === 'input' ||
                tagName === 'button' ||
                tagName === 'select' ||
                tagName === 'textarea' ||
                tagName === 'audio' ||
                target.closest('.nav-controls') ||
                target.closest('.loop-markers') ||
                target.closest('#media-help-modal') ||
                target.closest('.media-help-card') ||
                target.closest('.media-toast')
            ) {
                return;
            }

            // For video elements: if clicking native controls area at the bottom, let native controls handle it
            if (tagName === 'video') {
                if (e.offsetY > target.clientHeight - 60) {
                    return;
                }
            }

            // If user has selected text on the page (e.g. title or description), don't toggle
            const selection = window.getSelection ? window.getSelection().toString() : '';
            if (selection && selection.length > 0) {
                return;
            }

            if (media.paused) {
                media.muted = false;
                media.play().catch(() => {});
                showToast('Play');
            } else {
                media.pause();
                showToast('Pause');
            }
        }

        document.addEventListener('keydown', handleKeyDown);
        document.body.addEventListener('click', handleBodyClick);

        updateMarkerUI();

        return {
            resetLoop: resetLoop,
            showToast: showToast,
            toggleHelp: toggleHelpModal,
            destroy: function () {
                document.removeEventListener('keydown', handleKeyDown);
                document.body.removeEventListener('click', handleBodyClick);
                clearInterval(loopInterval);
            }
        };
    };
}));
