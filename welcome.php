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

    .mobile-email-input {
        background: var(--input-bg);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        color: var(--text-color);
        outline: none;
        transition: all 0.2s ease;
        width: 80%;
        margin: 0 auto;
        /* Center the input box itself */
        display: block;
        /* Ensure margin auto works */
        text-align: center;
        /* Center the placeholder/value text */
        box-sizing: border-box;
    }

    .mobile-email-input:focus {
        background: rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.05);
        /* Matching search widget focus */
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
            <script src="/JavaScript/UIUtils.js?v=<?php echo time(); ?>" type="text/javascript"></script>
            <script src="/maps/JavaScript/CheckAddress.js?v=<?php echo time(); ?>" type="text/javascript"></script>
            <form method="post" action="javascript:void(0);" name="address"
                onsubmit="return checkaddress(this, 'andrew');" class="email-form"
                style="display: flex; flex-direction: column; margin-top: 0.5rem; width: 100%;">
                <label for="mobile-email" class="email-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: bold; text-align: left;">Can you
                    email me?</label>
                <input type="text" id="mobile-email" name="email" value="Enter email address and hit return"
                    onfocus="if(this.value=='Enter email address and hit return') this.value='';"
                    onblur="if(this.value=='') this.value='Enter email address and hit return';"
                    class="mobile-email-input">
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>