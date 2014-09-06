<?php
function getreq($url){
	$req = file_get_contents($url);
	return json_decode($req, true);
}

function htmlplz($str){
	$str = str_replace("\r\n", "\n", $str);
	$str = str_replace("\r", "\n", $str);
	$str = preg_replace("/\n{2,}/", "\n\n", $str);
	$str = preg_replace('/\n(\s*\n)+/', '</p><p>', $str);
	$str = preg_replace('/\n/', '<br>', $str);
	return '<p>'.$str.'</p>';
}

function videoembed($videoid){
	return "<iframe id=\"ytplayer\" type=\"text/html\" width=\"640\" height=\"390\"
		src=\"http://www.youtube.com/embed/" . $videoid . "?autoplay=0&amp;origin=http://alumninati.no\"
		frameborder=\"0\" allowfullscreen>
		</iframe><br>";
}

function getVideo($videoid){
	$req = getreq("https://www.googleapis.com/youtube/v3/videos?part=id%2C+snippet%2C+liveStreamingDetails&id=" . $videoid . "&key=" . API_KEY);
	$item = $req["items"][0];
	$type = ($item["snippet"]["liveBroadcastContent"] == "live" ? "live" : 
			($item["snippet"]["liveBroadcastContent"] == "upcoming" ? "upcoming" : "video" )
		);
	return array(
			"type" => $type,
			"videoid" => $videoid,
			"thumbnail" => $item["snippet"]["thumbnails"]["medium"]["url"],
			"title" => $item["snippet"]["title"],
			"description" => $item["snippet"]["description"],
			"publishedAt" => new DateTime($item["snippet"]["publishedAt"]),
			"scheduledStartTime" => ($type != "video" ? new DateTime($item["items"][0]["liveStreamingDetails"]["scheduledStartTime"]) : null )
		);
}

function getVideos($videoid){
	$videos = array();
	$req = getreq("https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=" . $videoid . "&key=" . API_KEY);
	$item = $req["items"][0];
	return array(
			"type" => "video",
			"videoid" => $item["snippet"]["resourceId"]["videoId"],
			"thumbnail" => $item["snippet"]["thumbnails"]["medium"]["url"],
			"title" => $item["snippet"]["title"],
			"description" => $item["snippet"]["description"],
			"publishedAt" => new DateTime($item["snippet"]["publishedAt"]),
			"scheduledStartTime" => null
		);
}

function getLiveVideos($eventType){
	$videos = array();
	$req = getreq("https://www.googleapis.com/youtube/v3/search?part=+id%2C+snippet&channelId=" . YTCHANNEL . "&eventType=" . $eventType . "&type=video&key=" . API_KEY);
	
	foreach($req["items"] as $item){
		$videoid = $item["id"]["videoId"];
		$live = getreq("https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=" . $videoid . "&key=" . API_KEY);
		$videos[] = array(
				"type" => $eventType,
				"videoid" => $videoid,
				"thumbnail" => $item["snippet"]["thumbnails"]["medium"]["url"],
				"title" => $item["snippet"]["title"],
				"description" => $item["snippet"]["description"],
				"publishedAt" => new DateTime($item["snippet"]["publishedAt"]),
				"scheduledStartTime" => new DateTime($live["items"][0]["liveStreamingDetails"]["scheduledStartTime"])
			);
	}
	
	return $videos;
}

function getPlaylistVideos(){
	$videos = array();
	$req = getreq("https://www.googleapis.com/youtube/v3/playlistItems?part=id%2C+snippet%2C+contentDetails%2C+status&playlistId=" . PLAYLISTID . "&key=" . API_KEY);
	
	foreach($req["items"] as $item){
		$videos[] = array(
				"type" => "video",
				"videoid" => $item["snippet"]["resourceId"]["videoId"],
				"thumbnail" => $item["snippet"]["thumbnails"]["medium"]["url"],
				"title" => $item["snippet"]["title"],
				"description" => $item["snippet"]["description"],
				"publishedAt" => new DateTime($item["snippet"]["publishedAt"]),
				"scheduledStartTime" => null
			);
	}
	
	return $videos;
}

function large($video){ ?>
	<h1><?= lang::g($video["type"] . "_header") . $video["title"] ?></h1> 
	Published:<?=$video["publishedAt"]->format(DateTime::RSS)?><br>
	<?= ($video["type"] != "video" ? "Scheduled:" . $video["scheduledStartTime"]->format(DateTime::RSS) . "<br>": "") ?>
	<?=videoembed($video["videoid"])?>
	<?=htmlplz($video["description"])?>
<?php
}

function upcoming($video){ ?>
	<a href="?video=<?=$video["videoid"]?>">
		<h1><?= lang::g($video["type"] . "_header") . $video["title"] ?></h1>
	</a> 
	<?= ($video["type"] != "video" ? "Scheduled:" . $video["scheduledStartTime"]->format(DateTime::RSS) . "<br>": "") ?>
	<?=htmlplz($video["description"])?>
<?php
}

function thumb($video){ ?>
	<img src="<?=$video["thumbnail"]?>">
	<h1><a href="/watch/<?= $video["videoid"] ?>"><?= lang::g($video["type"] . "_header") . $video["title"] ?></a></h1>
	<?=htmlplz($video["description"])?>
<?php
}
?>
