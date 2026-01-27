<HTML>

<HEAD>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
    <TITLE>Bottoms Up</TITLE>
    <?php include '../includes/frame_header.php'; ?>
    <style>
        body {
            background-color: transparent !important;
            /* Override standard frame header standard if needed, though frame_header handles it */
            color: var(--on-surface);
            /* Dark mode compatible text */
            font-family: 'Inter', sans-serif;
            font-size: 1.2rem;
            /* Increased font size */
            line-height: 1.6;
            margin: 0;
            padding: 2rem 2rem 100px 2rem;
        }

        /* Ensure dark mode text is white if not handled by vars */
        :root[data-theme="dark"] body {
            color: #ffffff;
        }

        h1.fancy-title {
            font-family: 'Dancing Script', cursive;
            font-size: 4rem;
            /* Larger */
            color: var(--google-yellow);
            /* Match Music titles */
            text-align: center;
            margin-bottom: 2rem;
        }

        .content {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Table Layout for Band Members */
        .band-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 2rem;
            margin-top: 3rem;
        }

        .band-table td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }

        .band-member img {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 1rem auto;
        }

        .band-info {
            margin-top: 1rem;
        }

        a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        hr {
            border: 0;
            height: 1px;
            background: var(--outline-variant);
            margin: 3rem 0;
        }
    </style>
</HEAD>

<BODY>

    <div class="content">

        <h1 class="fancy-title">Bottoms Up</h1>

        <p>When asked the question, "What new talent is sweeping through the Bay Area club scene?", the band Bottoms
            Up quickly comes to mind. To some, it's no surprise considering the strength
            of this band's line-up. Former Cast of Shadows members Andy DeFaria and
            Scott Dinn have teamed up with Steve Burgio and with Steve Sampson of The
            Wave to form this high energy modern rock dance band.</p>

        <p>With each member boasting over 10 years stage experience and a list of credentials surpassing most
            local cover acts, the fan base is strong and growing. Sampson's success
            in The Wave has earned him credit as a supporting act for nationals such
            as Richard Marx, Bad English, and The Tubes. On the home front, DeFaria
            and Dinn have performed steadily throughout the Bay Area as leading members
            of the well established Cast of Shadows, and have earned a following that
            has held to this day.</p>

        <p>The music for Bottoms Up is consistent with the demands of today's club music scene and is dynamic
            and strong.</p>

        <p>Check out our album produced from a cassette recording off the soundboard: <a
                href="/php/downloaddir.php?dir=%2FMedia%2FBottoming+Out+in+Monterey" target="_blank">Bottoming Out in
                Monterey</a>.</p>

        <table class="band-table">
            <tr>
                <td>
                    <div class="band-member">
                        <img src="Images/andy.jpg" alt="Andrew DeFaria">
                        <div class="band-info">
                            <strong><a href="/" target="_top">Andrew DeFaria</a></strong><br>
                            Guitar/Vocals<br>
                            <small>Formally of <em>Cast of Shadows</em></small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="band-member">
                        <img src="Images/steves.jpg" alt="Steve Sampson">
                        <div class="band-info">
                            <strong>Steve Sampson</strong><br>
                            Keyboards/Vocals<br>
                            <small>Formally of <em>The Wave</em><br>Currently in <em>Dig This</em></small>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="band-member">
                        <img src="Images/steveb.jpg" alt="Steve Burgio">
                        <div class="band-info">
                            <strong>Steve Burgio</strong><br>
                            Bass/Vocals<br>
                            <small>Formally of <em>Nervus Rexx</em></small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="band-member">
                        <img src="Images/scott.jpg" alt="Scott Dinn">
                        <div class="band-info">
                            <strong><a href="http://www.webthumper.com" target="_blank">Scott Dinn</a></strong><br>
                            Drums/Vocals<br>
                            <small>Formally of <em>Cast of Shadows</em></small>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <?php include '../includes/footer.php'; ?>
</BODY>

</HTML>