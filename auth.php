<?php
session_start();

if (!isset($_SESSION['firebase_uid'])) {
    header("Location: login.php");
    exit;
}
