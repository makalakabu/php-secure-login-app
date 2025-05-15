<?php
// Set HTTP security headers
header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none';");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>