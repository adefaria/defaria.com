<?php $page_title = "404 - Lost in Cyberspace";
include 'includes/frame_header.php'; ?>
<style>
    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        overflow: hidden;
        /* For stars */
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .container-404 {
        position: relative;
        z-index: 10;
        max-width: 600px;
        padding: 2rem;
    }

    h1.glitch {
        font-family: 'Courier New', monospace;
        font-size: 8rem;
        font-weight: bold;
        color: var(--google-red);
        text-shadow: 2px 2px var(--google-blue), -2px -2px var(--google-green);
        margin: 0;
        animation: glitch 1s infinite alternate;
    }

    h2 {
        font-family: var(--font-heading);
        font-size: 2rem;
        margin-top: -1rem;
        color: var(--google-blue);
    }

    p {
        font-size: 1.2rem;
        color: var(--muted-color);
        margin: 2rem 0;
    }

    .home-btn {
        display: inline-block;
        padding: 1rem 2rem;
        background: transparent;
        border: 2px solid var(--google-green);
        color: var(--google-green);
        font-family: var(--font-heading);
        font-size: 1.2rem;
        text-decoration: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .home-btn:hover {
        background: var(--google-green);
        color: #000;
        box-shadow: 0 0 20px var(--google-green);
        transform: scale(1.05);
    }

    @keyframes glitch {
        0% {
            text-shadow: 2px 2px var(--google-blue), -2px -2px var(--google-green);
            transform: translate(0, 0);
        }

        25% {
            text-shadow: -2px 2px var(--google-blue), 2px -2px var(--google-green);
            transform: translate(-2px, 2px);
        }

        50% {
            text-shadow: 2px -2px var(--google-blue), -2px 2px var(--google-green);
            transform: translate(2px, -2px);
        }

        75% {
            text-shadow: -2px -2px var(--google-blue), 2px 2px var(--google-green);
            transform: translate(-2px, -2px);
        }

        100% {
            text-shadow: 2px 2px var(--google-blue), -2px -2px var(--google-green);
            transform: translate(0, 0);
        }
    }

    /* Starry Background */
    .star {
        position: absolute;
        background-color: var(--text-color);
        /* Adaptive color */
        border-radius: 50%;
        opacity: 0.8;
    }
</style>

<div class="container-404">
    <h1 class="glitch">404</h1>
    <h2>System Failure</h2>
    <p>The coordinates you entered led to a black hole. <br> This page has been lost in the void.</p>

    <a href="/welcome" target="_top" class="home-btn">Return to Base</a>
</div>

<script>
    // Create Stars
    function createStars() {
        const body = document.body;
        for (let i = 0; i < 100; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            let xy = Math.random() * 100;
            let duration = Math.random() * 1 + 0.5;
            let size = Math.random() * 2 + 1; // Slightly larger for visibility

            star.style.left = Math.random() * 100 + 'vw';
            star.style.top = Math.random() * 100 + 'vh';
            star.style.width = size + 'px';
            star.style.height = size + 'px';
            // Opacity for twinkling effect or depth
            star.style.opacity = Math.random() * 0.5 + 0.3;

           body.appendChild(star);
        }
    }
    createStars();
</script>

</body>

</html>