<?php
// include anketa data
include ('data_source.txt');
#print_r($anketa);

// fill array with unique artists
$artists = array();
foreach ($anketa as $user => $values) {
	foreach ($values as $artist) {
		if (!array_key_exists($artist, $artists)) {
			$artists[$artist] = array();
		}
	}
}

// make array for artists and add people to it
foreach ($anketa as $user => $values) {
	foreach ($values as $artist) {
		$artists[$artist][] = $user;
	}
}
#print_r($artists);

// artist's chart - who's most popular
$artists_list = array_keys($artists);
$artists_chart = array_flip($artists_list);
foreach ($artists_list as $art) {
	$artists_chart[$art] = sizeof($artists[$art]);
}
unset($artists_chart['Bad Sector']);
arsort($artists_chart);
$artists_chart = array_slice($artists_chart, 2,20);
#print_r($artists_chart);

// combine and 
$top = array();
foreach ($artists_chart as $art => $count) {
	foreach ($artists_chart as $art2 => $count2) {
		// skip self relations
		if ($art == $art2) { continue; }
		// test combos for dupes
		$combo = $art.' | '.$art2;
		$combo2 = $art2.' | '.$art;
		if (array_key_exists($combo, $top) || array_key_exists($combo2, $top)) { continue; }
		// merge voters
		$result = array_merge($artists[$art], $artists[$art2]);
		$result = array_unique($result);
		// count uniq voters and take percentage
		$total_voters = sizeof($anketa);
		$top[$combo] = round(sizeof($result)/($total_voters/100));
	}
}
arsort($top);
print_r(array_slice($top, 0,50));
?>