<?php
/**
 * Authentication check for admin pages.
 * Include at the top of every admin page.
 */
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}