<?php
session_start();

// Unset all session variables
$_SESSION = [];

// If you want to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Clear the cookies that you might have set for login
if (isset($_COOKIE['email'])) {
    setcookie('email', '', time() - 3600, '/'); // Empty email cookie
}
if (isset($_COOKIE['uniid'])) {
    setcookie('uniid', '', time() - 3600, '/'); // Empty uniid cookie
}

// Clear the cache
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Redirect to login page after logging out
header("Location: login.php");
exit;
