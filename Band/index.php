<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast of Shadows - Musical Career</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&family=Dancing+Script:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?v=2">

    <?php include "../php/site-functions.php" ?>
    <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">

    <style>
        /* Page Specific Styles */
        .band-body {
            background-color: #000;
            background-image: url('Images/CastOfShadowsBackdrop.jpg');
            background-repeat: repeat;
            /* Legacy behavior usually repeat */
            color: #fff;
        }

        .band-content {
            background: rgba(0, 0, 0, 0.7);
            /* Darken text areas for readability against backdrop */
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .member-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
        }

        .songs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .songs-grid {
                grid-template-columns: 1fr;
            }
        }

        .album-box {
            background: rgba(51, 0, 51, 0.8);
            /* Matches BGCOLOR="#330033" */
            border: 2px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
        }

        a {
            color: #66FFFF;
        }

        /* match alink="#66FFFF" or similar bright color for dark bg */
        a:hover {
            color: #fff;
        }

        h2,
        h3 {
            font-family: 'Outfit', sans-serif;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }
    </style>
</head>

<body class="iframe-body band-body">

    <div class="content-container">

        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="CastOfShadows.jpg" alt="Cast of Shadows" style="max-width: 100%; height: auto;">
        </div>

        <div class="band-content">
            <div class="member-grid">
                <div>
                    <img src="Images/NewClothes.jpg" alt="Band Members" style="border-radius: 8px; max-width: 100%;">
                </div>
                <div style="text-align: center;">
                    <h3>BAND MEMBERS</h3>
                    <p>(from left to right)</p>
                    <hr style="width: 50%; border-color: rgba(255,255,255,0.2);">
                    <ul style="list-style: none; padding: 0; font-size: 1.2rem;">
                        <li><a href="/">Andy DeFaria</a></li>
                        <li><a href="/Music/index.php?q=f&f=%2FRock%2FMike+Fraser">Mike Fraser</a></li>
                        <li><a href="http://www.webthumper.com/author.html">Scott Dinn</a></li>
                    </ul>
                </div>
            </div>

            <p style="text-align: center;">
                Visit the <a href="/Music/index.php?q=f&f=%2FRock%2FCast+of+Shadows">Cast of Shadows Music Archive</a>.
            </p>
        </div>

        <!-- MP3 Section -->
        <div class="band-content">
            <div class="songs-grid">
                <div>
                    <h3>Latest Work</h3>
                    <p>Here are a few songs I've been working on lately.</p>
                    <ul class="file-list" style="list-style: none; padding: 0;">
                        <li><a href="Cryin' in the Rain (MIDI).mp3">Cryin' in the Rain</a> (Cast of Shadows) - 2.1 meg
                        </li>
                        <li><a href="Reelin in the Years.mp3">Reelin in the Years</a> (Steely Dan) - 2.2 meg</li>
                        <li><a href="Boys Are Back in Town.mp3">Boys Are Back in Town</a> (Thin Lizzy) - 2.2 meg</li>
                        <li><a href="Black Dog.mp3">Black Dog</a> (Led Zepplin) - 2.5 meg</li>
                        <li><a href="The Wanton Song.mp3">The Wanton Song</a> (Led Zepplin) - 3.8 meg</li>
                    </ul>
                </div>
                <div style="text-align: center;">
                    <img src="Images/WithBunnies.jpg" alt="With Bunnies" style="border-radius: 8px; max-width: 100%;">
                </div>
            </div>
        </div>

        <!-- Face The Time Album -->
        <div class="album-box band-content">
            <div class="songs-grid">
                <div style="text-align: center;">
                    <img src="Images/FaceTheTimeCover.jpg" alt="Face The Time Cover"
                        style="max-width: 100%; border-radius: 4px;">
                </div>
                <div>
                    <h2 style="text-align: center; border: none;">Face the Time</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <th style="padding: 0.5rem; text-align: left;">Title</th>
                                <th style="padding: 0.5rem; text-align: right;">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="Street Magic.mp3">Street Magic</a></td>
                                <td align="right">4:06</td>
                            </tr>
                            <tr>
                                <td><a href="Face the Time.mp3">Face The Time</a></td>
                                <td align="right">5:30</td>
                            </tr>
                            <tr>
                                <td><a href="Drugstore Novels.mp3">Drugstore Novels</a></td>
                                <td align="right">4:32</td>
                            </tr>
                            <tr>
                                <td><a href="Cryin' in the Rain.mp3">Cryin' in the Rain</a></td>
                                <td align="right">4:30</td>
                            </tr>
                            <tr>
                                <td><a href="Remember.mp3">Remember</a></td>
                                <td align="right">4:25</td>
                            </tr>
                            <tr>
                                <td><a href="Reach Beyond the Past.mp3">Reach Beyond the Past</a></td>
                                <td align="right">4:38</td>
                            </tr>
                            <tr>
                                <td><a href="Lifeline.mp3">Lifeline</a></td>
                                <td align="right">4:36</td>
                            </tr>
                            <tr>
                                <td><a href="Silent Rage.mp3">Silent Rage</a></td>
                                <td align="right">4:42</td>
                            </tr>
                            <tr>
                                <td><a href="Breakaway.mp3">Breakaway</a></td>
                                <td align="right">4:16</td>
                            </tr>
                            <tr>
                                <td><a href="Love Beyond Our Needs.mp3">Love Beyond Our Needs</a></td>
                                <td align="right">3:59</td>
                            </tr>
                            <tr>
                                <td><a href="The Reunion.mp3">The Reunion</a></td>
                                <td align="right">3:57</td>
                            </tr>
                            <tr>
                                <td><a href="Reach Beyond the Past (Remix).mp3">Reach Beyond the Past (Remix)</a></td>
                                <td align="right">4:38</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="text-align: center; font-size: 0.9rem; margin-top: 1rem; color: #ccc;">All songs are
                        &copy; Copyright Hop-A-Long Productions 1993</p>
                </div>
            </div>
        </div>

        <div class="band-content">
            <p style="text-align: center;">
                If you would like to receive a copy of these songs on a tape, <a href="mailto:andrew@defaria.com">email
                    me</a>.<br>
                I have about 30 CD's left that I would be willing to sell for $15.<br>
                The CD has a booklet of all the lyrics and pictures of the band.
            </p>

            <hr>

            <p><b>Cast of Shadows</b> played Top 40 Modern Rock, but we have recently broken up. I'm proud to say that
                as a band we had a number of accomplishments including:</p>
            <ul>
                <li>Wrote, recorded and produced our own CD called <i><a href="facethetime.html">Face the Time</a></i>.
                </li>
                <li>Played over 100 dates at local area clubs such as: Boswells, McNeil's, Pioneer Saloon, Carlos
                    Murphy's, Sport's Page, Cheer's, Lord John's Inn, Toon's, Mountain Charlies, Britiania Arms,
                    Cabaret, The Huddle, Doc Ricketts, Fanny & Alexander's, Foggs.</li>
                <li>Made and sold T-shirts, stickers and buttons.</li>
                <li>Bought our own PA and light show.</li>
                <li>Integrated our 3 piece band with MIDI controlling 3 synthesizers as well as the light show.</li>
                <li>Obtained management (Thrill Entertainment Group) and roadies.</li>
                <li>Managed to get the band business in the black and draw a monthly salary!</li>
            </ul>

            <div style="text-align: center; margin: 2rem 0;">
                <img src="Images/8X10Picture.jpg" alt="Band Picture" style="max-width: 100%; border-radius: 8px;">
            </div>

            <p>But, alas, all of this has fallen apart!</p>
            <p>After Cast of Shadows I formed the band named <b><i><a href="BottomsUp.html">Bottoms Up</a></i></b>. We
                gigged around for about a year. I am currently band less and available for hire!</p>

            <h2>New Directions</h2>
            <p>As always though, I'm looking for competent musicians to jam with. My interests are varied, ranging from
                Heavy Metal to Top 40. I like artists from the jazzy (Spyra Gyra, Bela Fleck) to the guitar hero types
                (Steve Morse, Steve Vai) to just Heavy Metal (Ozzy, Kings X) to various Top 40 and Modern Music (Oingo
                Boingo, Gin Blossom, etc) as well as old 70's style progressive (Yes, Kansas, Floyd).</p>

            <p>Ideally I'd like to join a working project because that's what I'm used to doing, but I've also
                entertained thoughts of joining or forming a band with competent musicians who wish to perform songs
                simply because they like the tunes.</p>

            <p>I also sing but am more of a backup singer than a lead singer. I have good transportation, equipment and
                I've been around. I know theory but am not a reader. If you got something interesting then contact me.
            </p>
        </div>

    </div>
</body>

</html>