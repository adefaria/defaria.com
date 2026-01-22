<?php
if (function_exists('copyright')) {
    copyright("1960");
} else {
    // Fallback if site-functions not included
    echo '<footer class="copyright"><p>&copy; ' . date("Y") . ' Andrew DeFaria. All rights reserved.</p></footer>';
}
?>
</body>

</html>