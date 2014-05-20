<?php

define('GIFBOMB_COUNT', 5); // Number of GIFs to reply with during #gifbomb

// ----- END OF CONFIGURATION ----- //

require_once 'GifBot/Gifable.php';
require_once 'GifBot/Giphy.php';
require_once 'GifBot/Gif.php';

$gif = new GifBot\Giphy;

// Get request
$trigger = trim($_POST['trigger_word']);
$term    = trim(substr($_POST['text'], strlen($trigger) + 1));

// Respond with random GIF if no search term is provided
if ($term == '') {
    sendResponse($gif->random()->getUrl());
}

// Search
$gifs  = $gif->search($term);
$count = count($gifs);

// Build reponse
$response = '';
if ($count) {
    if ($trigger == '#gifbomb') {
        for ($count = 0; $count < GIFBOMB_COUNT; $count++) {
            $response .= $gifs[rand(0, $count - 1)]->getUrl() . ' ';
        }
    } else {
        $response = $gifs[rand(0, $count - 1)]->getUrl();
    }
} else {
    $response = "No image found for '" . $term . "'. Enjoy this random GIF instead. " . $gif->random()->getUrl();
}

// Respond
sendResponse($response);

exit;

/**
 * Send JSON Response
 *
 * @param $response
 */
function sendResponse($response)
{
    header('Content-Type: application/json');
    die(json_encode(array(
        'text' => $response
    )));
}