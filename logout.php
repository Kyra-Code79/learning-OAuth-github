<?php
session_start(); // Start the session

// Unset session variables for user and access token
unset($_SESSION['user']);
unset($_SESSION['access_token']);

// Destroy the session
session_destroy();

// Redirect to the login page (or wherever you'd like after logging out)
header("Location: login.php");
exit();
?>
