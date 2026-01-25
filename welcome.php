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
        margin-top: 1rem;
        /* Reduced from 2rem */
    }

    @media (max-width: 768px) {
        .desktop-msg {
            display: none;
        }

        .mobile-msg {
            display: inline !important;
        }

        .mobile-email-form {
            display: block;
        }
    }

    @media (min-width: 769px) {
        .mobile-msg {
            display: none;
        }

        .mobile-email-form {
            display: none;
        }
    }
</style>

<main class="container">
    <h1 class="page-title">Welcome</h1>

    <div class="welcome-content">
        <p>This is my personal website. For a long time I had a pretty plain web site. Some people said it looked like
            it was designed in the 90s and they were right. But I'm not selling anything here, except myself to show my
            skills with computers and my musical career. The redesign is definitely not 90s. I also have a few utilities
            and the like.</p>

        <p>Select a tab <span class="desktop-msg">to the left</span><span class="mobile-msg">below</span> and then
            explore the cards contained therein - enjoy!</p>

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

        <!-- Mobile-only Email Form -->
        <div class="mobile-email-form">
            <script src="/maps/JavaScript/CheckAddress.js" type="text/javascript"></script>
            <form method="post" action="javascript:void(0);" name="address"
                onsubmit="return checkaddress(this, 'andrew');" class="email-form"
                style="display: inline-block; margin-top: 0.5rem;">
                <label for="mobile-email" class="email-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Can you
                    email me?</label>
                <input type="text" id="mobile-email" name="email" value="Enter email address"
                    onfocus="if(this.value=='Enter email address') this.value='';"
                    onblur="if(this.value=='') this.value='Enter email address';" class="email-input-box"
                    style="padding: 8px; width: 100%; border-radius: 4px; border: 1px solid var(--text-color); box-sizing: border-box; margin-bottom: 0.5rem;">
                <button type="submit"
                    style="padding: 8px 16px; background-color: var(--surface-color); color: var(--text-color); border: 1px solid var(--text-color); border-radius: 4px; cursor: pointer;">Check</button>
            </form>
        </div>
    </div>
</main>

<?php // Footer handled by shell ?>