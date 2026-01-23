<?php include 'includes/frame_header.php'; ?>
<style>
    .welcome-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .signature {
        font-family: var(--font-fancy);
        font-size: 2rem;
        margin-top: 2rem;
    }
</style>

<main class="container">
    <h1 class="page-title">Welcome</h1>

    <div class="welcome-content">
        <p>This is my personal website. For a long time I had a pretty plain web site. Some people said it looked like
            it was designed in the 90s and they were right. But I'm not selling anything here, except myself to show my
            skills with computers and my musical career. The redesign is definitely not 90s.I also have a few utilities
            and the like.</p>

        <p>Select a tab to the left and then explore the cards contained therein - enjoy!</p>

        <div class="signature">
            <?php
            $text = "Andrew DeFaria";
            $colors = ['--google-blue', '--google-red', '--google-yellow', '--google-green'];
            $colorIdx = 0;
            for ($i = 0; $i < strlen($text); $i++) {
                $char = $text[$i];
                if (trim($char) === '') {
                    echo $char;
                    continue;
                }
                echo '<span style="color: var(' . $colors[$colorIdx % count($colors)] . ')">' . $char . '</span>';
                $colorIdx++;
            }
            ?>
        </div>
    </div>
</main>

<?php // Footer handled by shell ?>