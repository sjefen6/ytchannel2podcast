<?php
date_default_timezone_set("Europe/Oslo");
require_once("../config.php");
require_once("lang.php");
require_once("ytutils.php");
require ('lib/Slim/Slim.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

define("YTCHANNEL", "UCC2Oj_f9yMs2ruifDG14Upg"); //Alumninati
define("PLAYLISTID", "PLI8IDHXhP2solA6p4xv1JUHtecnj7OUUl"); //Alumninati (spillelisten, må legges til manuelt)
define("MAXLIVE", 5); // Max of each live and upcoming
define("LANGUAGE", "en"); // Max of each live and upcoming

$app->get('/', function () {
	header('Content-Type: text/html; charset=utf-8');
    $live = getLiveVideos("live");
	$upcoming = getLiveVideos("upcoming");
	$video = getPlaylistVideos();

	if(count($live) > 0){
		$first = array_shift($live);
	} elseif (count($video) > 0) {
		$first = array_shift($video);
	} else {
		$first = null;
	}

	include('tpl/index.phtml');
});

$app->get('/watch/:videoid', function ($videoid) {
	header('Content-Type: text/html; charset=utf-8');
    $live = getLiveVideos("live");
	$upcoming = getLiveVideos("upcoming");
	$video = getPlaylistVideos();

	$first = getVideo($videoid);

	include('tpl/index.phtml');
});

$app->get('/:list(/:page)', function ($list, $page = null) {
	if ($list != "videos"){
		$live = $live = getLiveVideos("live");
	}
	echo "It works!";
})->conditions(array('list' => 'live|upcoming|videos'));

$app->get('/feed/:format', function ($format) {
	header('Content-Type: application/rss+xml; charset=utf-8');
	$video = getVideos();

	include('tpl/rss.phtml');
})->conditions(array('format' => 'audio|video'));

$app->run();
?>
