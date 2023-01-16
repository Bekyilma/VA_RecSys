<?php
if (empty($_POST)) exit;

require 'config.php';

$prefs = array();
foreach ($_POST['prefs'] as $key => $val) {
    $prefs[$key] = (int) $val;
}

$entry = array(
    'user' => session_id(),
    'time' => time(),
    'prefs' => $prefs
);

foreach ($entry as $val) {
    if (empty($val)) {
        die('Some fields are missing. Please go back and try again.');
    }
}

if (!empty($_SESSION['pid'])) $entry['pid'] = $_SESSION['pid'];
if (!empty($_SESSION['sid'])) $entry['sid'] = $_SESSION['sid'];
if (!empty($_SESSION['tid'])) $entry['tid'] = $_SESSION['tid'];

$res = file_put_contents(PREFERENCES_FILE, json_encode($entry).PHP_EOL, FILE_APPEND);
if (!$res) die('Cannot write data. Please try again later.');

// All good by now.
$_SESSION['preferences'] = $prefs;

// At this point we can advance to the next step.
redirect('index.php');
