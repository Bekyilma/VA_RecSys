<?php
session_start();

define('VERSION', '1.0');
define('DEBUG', FALSE);

// App data. Available at https://project-banana.eu/va-recsys/appdata.zip
define('DB_FILE', 'story_groups_filtered.csv');
define('IMAGES_DIR', 'paintings');
define('THUMBS_DIR', 'thumbnails');

// Log files to store user's data.
// Will be automatically created.
define('DEMOGRAPHICS_FILE', 'user-demographics.ndjson');
define('PREFERENCES_FILE', 'user-preferences.ndjson');
define('RATINGS_FILE', 'user-ratings.ndjson');

// Remote machine with all running services.
$target_ip = 'http://10.186.2.13'; // lambda-ilias
$target_ip = 'http://10.244.0.19'; // banana
$target_ip = 'http://localhost';

// We'll use a dedicated service per RecSys condition,
// but we could have independent machines if need be.
$_SERVICES = array(
  'lda'             => $target_ip . ':10501/retrieval',
  'bert'            => $target_ip . ':10502/retrieval',
  'lda_bert'        => $target_ip . ':10503/retrieval',
  'resnet'          => $target_ip . ':10504/retrieval',
  'lda25_resnet75'  => $target_ip . ':10505/retrieval',
  'lda50_resnet50'  => $target_ip . ':10506/retrieval',
  'lda75_resnet25'  => $target_ip . ':10507/retrieval',
  'bert25_resnet75' => $target_ip . ':10508/retrieval',
  'bert50_resnet50' => $target_ip . ':10509/retrieval',
  'bert75_resnet25' => $target_ip . ':10510/retrieval',
  'clip'            => $target_ip . ':10511/retrieval',
  'blip'            => $target_ip . ':10512/retrieval'
);

if (isset($_GET['restart'])) {
    session_destroy();
    $_SESSION = array();
}

if (isset($_GET['refresh'])) {
    $_SESSION['preferences'] = array();
    $_SESSION['conditions'] = array();
    $_SESSION['registered'] = FALSE;
}

// --- BEGIN Prolific IDs ---
if (isset($_GET['pid']) && !isset($_SESSION['pid'])) {
    $_SESSION['pid'] = filter_var($_GET['pid'], FILTER_SANITIZE_STRING);
}

if (isset($_GET['sid']) && !isset($_SESSION['sid'])) {
    $_SESSION['sid'] = filter_var($_GET['sid'], FILTER_SANITIZE_STRING);
}

if (isset($_GET['tid']) && !isset($_SESSION['tid'])) {
    $_SESSION['tid'] = filter_var($_GET['tid'], FILTER_SANITIZE_STRING);
}
// --- END Prolific IDs ---

if (empty($_SESSION['preferences'])) {
    $_SESSION['preferences'] = array();
}

if (empty($_SESSION['conditions']) && empty($_SESSION['preferences'])) {
    $_SESSION['conditions'] = array_keys($_SERVICES);
    shuffle($_SESSION['conditions']);
}

// Print session info in debug mode?
//var_dump($_SESSION);


// Main functions -------------------------------------------------------------

function _e($str) {
    echo $str;
}

function redirect($url) {
    $url = 'index.php';
    if (isset($_GET['debug'])) {
        $url .= '?debug';
    }

    header('Location: ' . $url);
}

function imgpath($image_id, $thumbnail = FALSE) {
    $dir = $thumbnail ? THUMBS_DIR : IMAGES_DIR;
    return $dir . '/' . $image_id . '.jpg';
}

function http_request($url, $data = NULL, $headers = NULL) {
    $options = array(
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_SSL_VERIFYHOST => FALSE,
      CURLOPT_HEADER         => FALSE,
    );

    // XXX: Don't use `array_merge()` since it resets all keys!
    if (!empty($data)) {
        $options += array(
          CURLOPT_POST       => TRUE,
          CURLOPT_POSTFIELDS => $data,
        );
    }

    // XXX: Don't use `array_merge()` since it resets all keys!
    if (!empty($headers)) {
        $options += array(
          CURLOPT_HTTPHEADER => $headers,
        );
    }

    $ch = curl_init();
    if (!$ch) {
        die(_('Cannot establish the service connection. Please try again later.'));
    }

    curl_setopt_array($ch, $options);

    $content = curl_exec($ch);
    if (empty($content)) {
        die(curl_error($ch));
    }

    curl_close($ch);

    return $content;
}

function fake_http_request($url, $data = NULL, $headers = NULL) {
    $images = read_images(IMAGES_DIR);
    $keys = array_rand($images, 9);
    $res = array();
    foreach ($keys as $k) {
        $res[] = $images[$k];
    }

    return json_encode($res);
}

function read_images($dir) {
    $entries = array();
    $files = new DirectoryIterator($dir);
    foreach ($files as $f) {
        if (!$f->isFile()) continue;
        if ($f->getExtension() != 'jpg') continue;
        // Retrieve image IDs only.
        $entries[] = $f->getBasename('.jpg');
    }

    return $entries;
}

function read_categories($csv_file) {
    $entries = array();
    $lines = file($csv_file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $num => $line) {
        // Ignore header.
        if ($num == 0) continue;

        list($iid, $group) = explode(',', $line);

        if (!array_key_exists($group, $entries)) $entries[$group] = array();

        $entries[$group][] = $iid;
    }

    return $entries;
}

function load_elicitations() {
    $entries = read_categories(DB_FILE);
    $iids = array();
    // Load one image at random from each group.
    foreach ($entries as $group => $images) {
        $key = array_rand($images, 1);
        $iids[] = $images[$key];
    }

    return $iids;
}

function load_recommendations($condition) {
    global $_SERVICES;

    if (!array_key_exists($condition, $_SERVICES)) {
        die(sprintf(_('Service "%s" not available. Please try again later.'), $condition));
    }

    // Perform HTTP request to a given RecSys service.
    $url = $_SERVICES[$condition];

    // Exit early in debug mode, since we're mocking the server response.
    if (DEBUG) return json_decode(fake_http_request($url));

    $res = http_request(
        $url,
        json_encode($_SESSION['preferences']),
        array('Content-Type: application/json')
    );

    $dat = json_decode($res);
    if (json_last_error() > 0) {
        print_r($res);
        die(json_last_error_msg());
    }

    return $dat;
}
