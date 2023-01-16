<?php
if (empty($_POST)) exit;

require 'config.php';

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
} else {
    $ip = 'NA';
}

$gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
$age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
$visit_profile = filter_var($_POST['visit_profile'], FILTER_SANITIZE_STRING);
$visit_popular = filter_var($_POST['visit_popular'], FILTER_SANITIZE_NUMBER_INT);

$entry = array(
    'user' => session_id(),
    'time' => time(),
    'ip' => $ip,
    'gender' => $gender,
    'age' => (int) $age,
    'visit_profile' => $visit_profile,
    'visit_popular' => (int) $visit_popular,
    'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
    'ua' => $_SERVER['HTTP_USER_AGENT']
);

foreach ($entry as $val) {
    if (empty($val)) {
        die(_('Some fields are missing. Please go back and try again.'));
    }
}

// Add Prolific info.
if (!empty($_SESSION['pid'])) $entry['pid'] = $_SESSION['pid'];
if (!empty($_SESSION['sid'])) $entry['sid'] = $_SESSION['sid'];
if (!empty($_SESSION['tid'])) $entry['tid'] = $_SESSION['tid'];

// Flag debug users.
if (DEBUG) $entry['debug'] = TRUE;

$res = file_put_contents(DEMOGRAPHICS_FILE, json_encode($entry).PHP_EOL, FILE_APPEND);
if (!$res) die(_('Cannot write data. Please try again later.'));

// All good by now.
$_SESSION['registered'] = TRUE;

// At this point we can advance to the next step.
redirect('index.php');
