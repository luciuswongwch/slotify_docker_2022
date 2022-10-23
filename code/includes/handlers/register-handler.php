<?php

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);
	return $inputText;
}

function sanitizeFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));
	return $inputText;
}

if(isset($_POST["registerButton"])) {
	// Register button was pressed
	$username = $_POST["username"];
	$username = sanitizeFormUsername($username);

	$firstName = $_POST["firstName"];
	$firstName = sanitizeFormString($firstName);

	$lastName = $_POST["lastName"];
	$lastName = sanitizeFormString($lastName);

	$password = $_POST["password"];
	$password = sanitizeFormPassword($password);

	$password2 = $_POST["password2"];
	$password2 = sanitizeFormPassword($password2);

	$wasSuccessful = $account->register($username, $firstName, $lastName, $password, $password2);

	if($wasSuccessful == true) {
		$_SESSION["userLoggedIn"] = $username;
		header("Location: index.php");
	}
}

?>