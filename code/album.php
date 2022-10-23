<?php include("includes/includedFiles.php"); ?>

<?php

if(isset($_GET["id"])) {
	$albumId = $_GET["id"];
} else {
	header("Location: index.php");
}

$album = new Album($con, $albumId);

?>

<div class="entityInfo">
	<div class="leftSection"><img src="<?php echo $album->getArtworkPath(); ?>"></div>
	<div class="rightSection">
		<h2><?php echo $album->getTitle(); ?></h2>
		<p>By <?php echo $album->getArtist(); ?></p>
		<p><?php echo $album->getNumberOfSongs(); ?> songs</p>
	</div>
</div>

<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
		$songIdArray = $album->getSongIds();

		$i = 1;
		foreach($songIdArray as $songId) {
			$albumSong = new Song($con, $songId);

			echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $songId . "\", tempPlaylist, true)'>
						<span class='trackNumber'>$i.</span>
					</div>
					<div class='trackInfo'>
						<span class='trackName'>" . $albumSong->getTitle() . "</span>
						<span class='artistName'>" .  $albumSong->getArtist() . "</span>
					</div>
					<div class='trackOptions'>
						<input type='hidden' class='songId' value='" . $songId . "'>
						<img class='optionButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					</div>
					<div class='trackDuration'>
						<span class='duration'>" . $albumSong->getDuration() . "</span>
					</div>
				</li>";

			$i = $i + 1;
		}

		?>

		<script>
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
			tempPlaylist = JSON.parse(tempSongIds);
		</script>

	</ul>
</div>

<div class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistsDropDown($con, $userLoggedIn->getUsername()); ?>
</div>