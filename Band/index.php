<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cast of Shadows - Andrew DeFaria</title>
  <meta name="last-modified" content="<?php echo date("F d Y @ g:i a", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/Band/band.css">
  <script src="/Band/band.js"></script>
</head>

<body>

  <header class="band-header">
    <div class="band-header-inner">
      <a href="/Band/index.php" class="band-brand">
        <div class="band-badge">&#9835;</div>
        <div>
          <span class="band-brand-title">Cast of Shadows</span>
          <span class="band-brand-subtitle">Bay Area Progressive &amp; Modern Rock Trio (1991&ndash;1994)</span>
        </div>
      </a>
      <ul class="band-nav">
        <li><a href="/Band/index.php" class="active">Overview &amp; Album</a></li>
        <li><a href="/Band/cosprofile.html">Band Profile</a></li>
        <li><a href="/Band/andy.html">Andy</a></li>
        <li><a href="/Band/mike.html">Mike</a></li>
        <li><a href="/Band/scott.html">Scott</a></li>
        <li><a href="/Band/BottomsUp.php">Bottoms Up</a></li>
      </ul>
    </div>
  </header>

  <main class="band-container">

    <!-- Hero & Band Lineup Card -->
    <div class="band-card">
      <div style="text-align: center;">
        <div class="band-logo-banner">
          <img src="/Band/CastOfShadows.jpg" alt="Cast of Shadows">
        </div>
      </div>

      <div class="band-grid-2" style="align-items: center; margin-bottom: 1.5rem;">
        <div style="text-align: center;">
          <img src="/Band/Images/NewClothes.jpg" alt="Cast of Shadows Band Members" style="max-width: 100%; border-radius: 12px; border: 1px solid var(--card-border); box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
          <div style="font-size: 0.82rem; color: var(--muted-color); margin-top: 0.4rem;">Left to right: Andy DeFaria, Mike Fraser, and Scott Dinn</div>
        </div>
        <div>
          <h2 style="margin-top: 0;">The Band</h2>
          <p><b>Cast of Shadows</b> was formed in October 1991 and performed Top 40 Modern Rock and original compositions across the San Francisco Bay Area club circuit until April 1994. As a high-tech power trio, we integrated live guitar, bass, and drums with an Atari STacy MIDI sequencer driving three synthesizers, samplers, and an automated light show.</p>
          <p style="margin-bottom: 0;">Click any band member below to view their profile and stage gear:</p>
        </div>
      </div>

      <div class="band-trio-grid">
        <div class="band-member-card">
          <img src="/Band/Images/andy.jpg" alt="Andy DeFaria">
          <div class="band-member-name">Andy DeFaria</div>
          <div class="band-member-role">Electric &amp; Acoustic Guitars, Backing Vocals</div>
          <a class="band-btn" href="/Band/andy.html" style="margin-top: auto;">View Profile &rarr;</a>
        </div>

        <div class="band-member-card">
          <img src="/Band/Images/Mike.jpg" alt="Mike Fraser">
          <div class="band-member-name">Mike Fraser</div>
          <div class="band-member-role">Bass, Keyboards, Acoustic Guitar, Lead Vocals</div>
          <a class="band-btn" href="/Band/mike.html" style="margin-top: auto;">View Profile &rarr;</a>
        </div>

        <div class="band-member-card">
          <img src="/Band/Images/scott.jpg" alt="Scott Dinn">
          <div class="band-member-name">Scott Dinn</div>
          <div class="band-member-role">Drums, Electronic Percussion, Backing Vocals</div>
          <a class="band-btn" href="/Band/scott.html" style="margin-top: auto;">View Profile &rarr;</a>
        </div>
      </div>
    </div>

    <!-- Face the Time Album + Interactive Audio Player -->
    <div id="album" class="band-card accent-yellow">
      <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
        <h2 style="margin: 0;">Face the Time &mdash; Original Album (1993)</h2>
        <span style="font-size: 0.85rem; color: var(--muted-color);">&copy; 1993 Hop-A-Long Productions &bull; Click any track to play</span>
      </div>

      <!-- Sticky Audio Player Bar -->
      <div class="band-player-bar">
        <div class="band-now-playing">
          <span class="band-now-label">&#9654; Now Playing</span>
          <span class="band-now-title" id="band-now-playing-title">Street Magic &mdash; Cast of Shadows</span>
        </div>
        <audio id="band-audio-player" class="band-audio-el" controls preload="none" src="/Band/Street%20Magic.mp3"></audio>
      </div>

      <div class="band-album-layout">
        <div class="band-covers">
          <img src="/Band/Images/FaceTheTimeCover.jpg" alt="Face the Time Front Cover">
          <img src="/Band/Images/FaceTheTimeBack.jpg" alt="Face the Time Back Cover">
        </div>

        <div>
          <table class="band-track-table">
            <thead>
              <tr>
                <th class="band-track-num">#</th>
                <th>Track Title</th>
                <th class="band-track-time">Length</th>
                <th class="band-track-dl">MP3</th>
              </tr>
            </thead>
            <tbody>
              <tr class="band-track-row playing" onclick="playBandTrack('/Band/Street%20Magic.mp3', 'Street Magic — Cast of Shadows', this)">
                <td class="band-track-num">01</td>
                <td><b>Street Magic</b></td>
                <td class="band-track-time">4:06</td>
                <td class="band-track-dl"><a href="/Band/Street%20Magic.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Face%20the%20Time.mp3', 'Face the Time — Cast of Shadows', this)">
                <td class="band-track-num">02</td>
                <td><b>Face the Time</b></td>
                <td class="band-track-time">5:30</td>
                <td class="band-track-dl"><a href="/Band/Face%20the%20Time.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Drugstore%20Novels.mp3', 'Drugstore Novels — Cast of Shadows', this)">
                <td class="band-track-num">03</td>
                <td><b>Drugstore Novels</b></td>
                <td class="band-track-time">4:32</td>
                <td class="band-track-dl"><a href="/Band/Drugstore%20Novels.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Cryin%27%20in%20the%20Rain.mp3', 'Cryin\' in the Rain — Cast of Shadows', this)">
                <td class="band-track-num">04</td>
                <td><b>Cryin' in the Rain</b></td>
                <td class="band-track-time">4:30</td>
                <td class="band-track-dl"><a href="/Band/Cryin%27%20in%20the%20Rain.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Remember.mp3', 'Remember — Cast of Shadows', this)">
                <td class="band-track-num">05</td>
                <td><b>Remember</b></td>
                <td class="band-track-time">4:25</td>
                <td class="band-track-dl"><a href="/Band/Remember.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Reach%20Beyond%20the%20Past.mp3', 'Reach Beyond the Past — Cast of Shadows', this)">
                <td class="band-track-num">06</td>
                <td><b>Reach Beyond the Past</b></td>
                <td class="band-track-time">4:38</td>
                <td class="band-track-dl"><a href="/Band/Reach%20Beyond%20the%20Past.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Lifeline.mp3', 'Lifeline — Cast of Shadows', this)">
                <td class="band-track-num">07</td>
                <td><b>Lifeline</b></td>
                <td class="band-track-time">4:36</td>
                <td class="band-track-dl"><a href="/Band/Lifeline.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Silent%20Rage.mp3', 'Silent Rage — Cast of Shadows', this)">
                <td class="band-track-num">08</td>
                <td><b>Silent Rage</b></td>
                <td class="band-track-time">4:42</td>
                <td class="band-track-dl"><a href="/Band/Silent%20Rage.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Breakaway.mp3', 'Breakaway — Cast of Shadows', this)">
                <td class="band-track-num">09</td>
                <td><b>Breakaway</b></td>
                <td class="band-track-time">4:16</td>
                <td class="band-track-dl"><a href="/Band/Breakaway.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Love%20Beyond%20Our%20Needs.mp3', 'Love Beyond Our Needs — Cast of Shadows', this)">
                <td class="band-track-num">10</td>
                <td><b>Love Beyond Our Needs</b></td>
                <td class="band-track-time">3:59</td>
                <td class="band-track-dl"><a href="/Band/Love%20Beyond%20Our%20Needs.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/The%20Reunion.mp3', 'The Reunion — Cast of Shadows', this)">
                <td class="band-track-num">11</td>
                <td><b>The Reunion</b></td>
                <td class="band-track-time">3:57</td>
                <td class="band-track-dl"><a href="/Band/The%20Reunion.mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Reach%20Beyond%20the%20Past%20(Remix).mp3', 'Reach Beyond the Past (Remix) — Cast of Shadows', this)">
                <td class="band-track-num">12</td>
                <td><b>Reach Beyond the Past (Remix)</b></td>
                <td class="band-track-time">4:38</td>
                <td class="band-track-dl"><a href="/Band/Reach%20Beyond%20the%20Past%20(Remix).mp3" download onclick="event.stopPropagation();" title="Download MP3">&#11015;</a></td>
              </tr>
            </tbody>
          </table>

          <p style="font-size: 0.88rem; color: var(--muted-color); margin-top: 1rem;">
            If you would like a physical copy of the CD (complete with full lyric booklet and band photos), <a href="mailto:andrew@defaria.com">email me</a>.
          </p>
        </div>
      </div>
    </div>

    <!-- Latest Work & Bunnies Photo Card -->
    <div class="band-card accent-blue">
      <div class="band-grid-2" style="align-items: center;">
        <div>
          <h2>Latest Work &amp; Studio Covers</h2>
          <p>Here are several studio cover recordings and MIDI arrangements I worked on (click any track to play in the player above):</p>
          <table class="band-track-table">
            <tbody>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Cryin%27%20in%20the%20Rain%20(MIDI).mp3', 'Cryin\' in the Rain (MIDI) — Cast of Shadows', this)">
                <td><b>Cryin' in the Rain (MIDI)</b> <span style="color:var(--muted-color);">(Cast of Shadows)</span></td>
                <td class="band-track-time">2.1 MB</td>
                <td class="band-track-dl"><a href="/Band/Cryin%27%20in%20the%20Rain%20(MIDI).mp3" download onclick="event.stopPropagation();">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Reelin%20in%20the%20Years.mp3', 'Reelin\' in the Years (Steely Dan Cover)', this)">
                <td><b>Reelin' in the Years</b> <span style="color:var(--muted-color);">(Steely Dan)</span></td>
                <td class="band-track-time">2.2 MB</td>
                <td class="band-track-dl"><a href="/Band/Reelin%20in%20the%20Years.mp3" download onclick="event.stopPropagation();">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Boys%20Are%20Back%20in%20Town.mp3', 'The Boys Are Back in Town (Thin Lizzy Cover)', this)">
                <td><b>Boys Are Back in Town</b> <span style="color:var(--muted-color);">(Thin Lizzy)</span></td>
                <td class="band-track-time">2.2 MB</td>
                <td class="band-track-dl"><a href="/Band/Boys%20Are%20Back%20in%20Town.mp3" download onclick="event.stopPropagation();">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/Black%20Dog.mp3', 'Black Dog (Led Zeppelin Cover)', this)">
                <td><b>Black Dog</b> <span style="color:var(--muted-color);">(Led Zeppelin)</span></td>
                <td class="band-track-time">2.5 MB</td>
                <td class="band-track-dl"><a href="/Band/Black%20Dog.mp3" download onclick="event.stopPropagation();">&#11015;</a></td>
              </tr>
              <tr class="band-track-row" onclick="playBandTrack('/Band/The%20Wanton%20Song.mp3', 'The Wanton Song (Led Zeppelin Cover)', this)">
                <td><b>The Wanton Song</b> <span style="color:var(--muted-color);">(Led Zeppelin)</span></td>
                <td class="band-track-time">3.8 MB</td>
                <td class="band-track-dl"><a href="/Band/The%20Wanton%20Song.mp3" download onclick="event.stopPropagation();">&#11015;</a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div style="text-align: center;">
          <img src="/Band/Images/WithBunnies.jpg" alt="Cast of Shadows with Playboy Bunnies" style="max-width: 100%; border-radius: 12px; border: 1px solid var(--card-border); box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
        </div>
      </div>
    </div>

    <!-- Accomplishments & New Directions Grid -->
    <div class="band-grid-2">
      <div class="band-card accent-green" style="margin-bottom: 0;">
        <h2>Band Accomplishments</h2>
        <p><b>Cast of Shadows</b> played Top 40 Modern Rock, and as a working band we achieved a number of proud milestones:</p>
        <ul>
          <li>Wrote, recorded, and produced our own 12-track CD, <i><a href="/Band/facethetime.html">Face the Time</a></i>.</li>
          <li>Played over 100 dates at Bay Area clubs including Boswells, McNeil's, Pioneer Saloon, Carlos Murphy's, Sports Page, Cheers, Lord John's Inn, Toon's, Mountain Charley's, Britannia Arms, Cabaret, The Huddle, Doc Ricketts, Fanny &amp; Alexander's, and Fogg's.</li>
          <li>Made and sold custom band T-shirts, stickers, and buttons.</li>
          <li>Purchased our own full PA system and stage lighting rig.</li>
          <li>Integrated our 3-piece band with MIDI controlling 3 synthesizers as well as the automated light show.</li>
          <li>Obtained professional management (Thrill Entertainment Group) and roadies.</li>
          <li>Managed to get the band business in the black and draw a monthly salary!</li>
        </ul>
        <div style="text-align: center; margin-top: 1rem;">
          <img src="/Band/Images/8X10Picture.jpg" alt="Cast of Shadows 8x10 Promo Photo" style="max-width: 100%; border-radius: 10px; border: 1px solid var(--card-border);">
        </div>
      </div>

      <div class="band-card accent-red" style="margin-bottom: 0; display: flex; flex-direction: column;">
        <h2>After Cast of Shadows &amp; New Directions</h2>
        <p>After Cast of Shadows broke up, Scott Dinn and I teamed up with Steve Sampson and Steve Burgio to form the high-energy rock dance band <b><i><a href="/Band/BottomsUp.php">Bottoms Up</a></i></b>, gigging around the Bay Area for about a year.</p>
        <p>As always, I enjoy connecting with competent musicians to jam and collaborate with. My musical interests span a wide range:</p>
        <ul>
          <li><b>Jazz Fusion &amp; Instrumental:</b> Spyro Gyra, Béla Fleck, Steve Morse, Steve Vai</li>
          <li><b>Hard Rock &amp; Metal:</b> Ozzy Osbourne, King's X, Led Zeppelin</li>
          <li><b>70s Progressive Rock:</b> Yes, Kansas, Pink Floyd, Rush</li>
          <li><b>Modern &amp; Alternative Rock:</b> Oingo Boingo, Gin Blossoms, Thin Lizzy, Steely Dan</li>
        </ul>
        <p>I play electric and acoustic guitar and sing backing vocals, with pro gear, reliable transportation, and decades of live stage experience.</p>
        <div style="margin-top: auto; padding-top: 0.75rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
          <a class="band-btn" href="/Band/BottomsUp.php">Explore Bottoms Up &rarr;</a>
          <a class="band-btn" href="/music" target="_top">Back to Music Hub &rarr;</a>
        </div>
      </div>
    </div>

  </main>

</body>

</html>
