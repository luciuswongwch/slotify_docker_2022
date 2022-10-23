var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audio;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click) {
	var target = $(click.target);
	if(!target.hasClass("item") && !target.hasClass("optionButton")) {
		hideOptionsMenu();
	}
})

$(window).scroll(function() {
	hideOptionsMenu();
})


$(document).on("change", "select.item.playlist", function() {

	var playlistId = $(this).val();
	var songId = $(this).prev(".songId").val();

	$.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId }).done(function(error) {
		if(error) {alert(error); return}
		hideOptionsMenu();
		$(this).val("");
	});
});

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPasswordClass1).val();
	var newPassword2 = $("." + newPasswordClass2).val();
	$.post("includes/handlers/ajax/updatePassword.php",
		{ oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn })
	.done(function(response) {
		$("." + oldPasswordClass).nextAll(".message").text(response);
	})
}

function logout() {
	$.post("includes/handlers/ajax/logout.php", function() {
		location.reload();
	});
}

function openPage(url) {

	if(timer != null){
		clearTimeout(timer);
	}

	if(url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedURL = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedURL);
	$("body").scrollTop();
	history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
	var songId = $(button).prevAll(".songId").val();

	$.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId }).done(function(error) {
		if(error) { alert(error); return; }
		openPage("playlist.php?id=" + playlistId);
	})

}


function createPlaylist() {
	var popup = prompt("Please enter the name of your playlist");
	if(popup != null) {
		$.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn }).done(function(error) {
			if(error) {alert(error); return}
			openPage("yourMusic.php");
		});
	}
}

function deletePlaylist(playlistId) {
	var prompt = confirm("Are you sure you want to delete this playlist?");
	if (prompt == true) {
		$.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId }).done(function(error) {
			if(error) { alert(error); return; }
			openPage("yourMusic.php");
		})
	}
}

function hideOptionsMenu() {
	var menu = $(".optionsMenu");
	if(menu.css("display") != "none") {
		menu.css("display", "none");
	}
}

function showOptionsMenu(button) {
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);
	var scrollTop = $(window).scrollTop();  //Distance from top of window to top of document
	var elementOffset = $(button).offset().top;  //Distance from top of document
	var top = elementOffset - scrollTop;
	var left = $(button).position().left;
	menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });
}

function formatTime(totalSeconds) {
	var time = Math.round(totalSeconds);
	var minutes = Math.floor(time / 60);
	var seconds = time - 60 * minutes;

	var extraZero;

	if(seconds < 10) {
		extraZero = "0";
	} else {
		extraZero = "";
	}

	// var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audioElement) {
	$(".progressTime.current").text(formatTime(audioElement.currentTime));
	$(".progressTime.remaining").text(formatTime(audioElement.duration - audioElement.currentTime));

	var progress = audioElement.currentTime / audioElement.duration * 100;
	$(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audioElement) {
	var volume = audioElement.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio() {
	this.currentlyPlaying;
	this.audioElement = document.createElement("audio");

	this.audioElement.addEventListener("ended", function() {
		nextSong();
	});

	this.audioElement.addEventListener("canplay", function() {
		var duration = formatTime(this.duration);
		$(".progressTime.remaining").text(duration);
	});

	this.audioElement.addEventListener("timeupdate", function() {
		if(this.duration) {
			updateTimeProgressBar(this);
		}
	});

	this.audioElement.addEventListener("volumechange", function() {
		updateVolumeProgressBar(this);
	});

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audioElement.src = track.path;
	};

	this.setTime = function(seconds) {
		this.audioElement.currentTime = seconds;
	}

}