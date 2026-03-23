<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy the session entirely

// Send them back to the main landing page
header("Location: ../index.php");
exit;
?>