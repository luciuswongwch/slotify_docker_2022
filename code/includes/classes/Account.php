<?php
class Account {

	private $con;
	private $errorArray;
		
	public function __construct($con) {
		$this->con = $con;
		$this->errorArray = array();
	}

	public function login($un, $pw) {
		$pw = md5($pw);
		$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");
		if(mysqli_num_rows($query) == 1) {
			return true;
		} else {
			array_push($this->errorArray, Constants::$loginFailed);
			return false;
		}
	}

	public function register($un, $fn, $ln, $pw, $pw2) {
		$this->validateUsername($un);
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validatePasswords($pw, $pw2);

		if(empty($this->errorArray)) {
			//Insert into databaser
			return $this->insertUserDetails($un, $fn, $ln, $pw);
		} else {
			return false;
		}
	}

	public function getError($error) {
		if (!in_array($error, $this->errorArray)) {
			$error = "";
		}
		return "<span class='errorMessage'>$error</span>";
	}

	private function insertUserDetails($un, $fn, $ln, $pw) {
		$encryptedPw = md5($pw);
		$profilepic = "assets/images/profile-pics/head_emerald.png";
		$date = date("Y-m-d");

		$result = mysqli_query($this->con, "INSERT INTO users (username, firstName, lastName, password, signUpDate, profilePic) VALUES ('$un', '$fn', '$ln', '$encryptedPw', '$date', '$profilepic')");
	
		return $result;
	}

	private function validateUsername($un) {
		if(strlen($un) > 25 || strlen($un) < 5) {
			array_push($this->errorArray, Constants::$usernameCharacters);
			return;
		}

		$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");

		if(mysqli_num_rows($checkUsernameQuery) != 0) {
			array_push($this->errorArray, Constants::$usernameTaken);
			return;
		}
	}

	private function validateFirstName($fn) {
		if(strlen($fn) > 25 || strlen($fn) < 2) {
			array_push($this->errorArray, Constants::$firstNameCharacters);
			return;
		}

	}

	private function validateLastName($ln) {
		if(strlen($ln) > 25 || strlen($ln) < 2) {
			array_push($this->errorArray, Constants::$lastNameCharacters);
			return;
		}
	}

	private function validatePasswords($pw, $pw2) {
		if($pw != $pw2) {
			array_push($this->errorArray, Constants::$passwordsDoNotMatch);
			return;
		}

		if(preg_match("/[^A-Za-z0-9]/", $pw)) {
			array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
			return;
		}

		if(strlen($pw) > 30 || strlen($pw) < 5) {
			array_push($this->errorArray, Constants::$passwordCharacters);
			return;
		}
	}

}
?>