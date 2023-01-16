<?php
if (empty($_POST)) exit;

require 'config.php';

$condition = filter_var($_POST['condition'], FILTER_SANITIZE_STRING);
$accuracy = filter_var($_POST['accuracy'], FILTER_SANITIZE_NUMBER_INT);
$diversity = filter_var($_POST['diversity'], FILTER_SANITIZE_NUMBER_INT);
$novelty = filter_var($_POST['novelty'], FILTER_SANITIZE_NUMBER_INT);
$serendipity = filter_var($_POST['serendipity'], FILTER_SANITIZE_NUMBER_INT);

$entry = array(
    'user' => session_id(),
    'time' => time(),
    'iids' => $_POST['iids'],
    'condition' => $condition,
    'accuracy' => (int) $accuracy,
    'diversity' => (int) $diversity,
    'novelty' => (int) $novelty,
    'serendipity' => (int) $serendipity
);

foreach ($entry as $val) {
    if (empty($val)) {
        var_dump($entry);
        die(_('Some fields are missing. Please go back and try again.'));
    }
}

if (!empty($_SESSION['pid'])) $entry['pid'] = $_SESSION['pid'];
if (!empty($_SESSION['sid'])) $entry['sid'] = $_SESSION['sid'];
if (!empty($_SESSION['tid'])) $entry['tid'] = $_SESSION['tid'];

$res = file_put_contents(RATINGS_FILE, json_encode($entry).PHP_EOL, FILE_APPEND);
if (!$res) die(_('Cannot write data. Please try again later.'));

// NB: We always read the first condition so remove it from the list.
array_shift($_SESSION['conditions']);

// At this point we can advance to the next trial.
redirect('index.php');
