<?php

include("../../config.php");

if(isset($_POST["playlistId"]) && isset($_POST["songId"])) {
	$playlistId = $_POST["playlistId"];
	$songId = $_POST["songId"];

	$query = mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistid='$playlistId' AND songid='$songId'");

} else {
	echo "PlaylistId or songId was not passed into deletePlaylist.php";
}

?>