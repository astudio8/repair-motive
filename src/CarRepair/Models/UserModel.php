<?php
namespace CarRepair\Models;

use CarRepair\Lib\Core,
    PDO,
    CarRepair\Controllers\Util;

class UserModel {

	protected $core;

	function __construct() {
		global $app;
		$this->app = $app;

		$this->core = Core::getInstance();
		$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//Set their Session ID
		if(isset($_SESSION['playerID'])) {

			$this->PID = $_SESSION['playerID'];
		} else {

			$this->PID = NULL;
		}

		$this->Util = new Util();

	}

	/*
	 * Get data for the logged in User
	 */
	public function myData ($id) {

		$sql = "SELECT * FROM users WHERE id = $id";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetch();
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get a single stat from a user
	 */
	public function getStat ($stat, $uid) {

		$sql = "SELECT $stat FROM users WHERE id = $uid";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {

			$r = $stmt->fetch();
		} else {

			$r = 0;
		}

		return $r[$stat];
	}

	/*
	 * Attempt to login a user
	 */
	public function login () {

		if (!$this->app->request->post('password') || !$this->app->request->post('email')){
			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Please fill in all fields.'
			);
			return $returnData;
		}

		//Hash the user's password
		$hashedPass = hash('sha256', $this->app->request->post('password'));
		$email 		= $this->app->request->post('email');

		$sql = "SELECT * FROM users WHERE email=:email AND password=:pass";
		$stmt = $this->core->dbh->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $hashedPass, PDO::PARAM_STR);

		if ($stmt->execute()) {

			$userInfo = $stmt->fetch();

			if(!empty($userInfo['email'])){

				$_SESSION['userID'] = $userInfo['id'];

				$returnData = array(
					'status' 	=> 'success',
					'msg' 		=> 'You have logged in.'
				);
				return $returnData;

			} else {

				$returnData = array(
					'status'	=> 'error',
					'msg' 		=> 'Could not log you in. Your password or email may be wrong.'
				);
				return $returnData;
			}

		} else {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Could not log you in.'
			);

			return $returnData;
		}
	}

	/*
	 * Attempt to register a user
	 */
	public function register () {

		if (!$this->app->request->post('password') || !$this->app->request->post('email')){
			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Please fill in all fields.'
			);
			return $returnData;
		}

		if (!$this->app->request->post('agreement')){
			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'You must agree to our Privacy and Terms.'
			);
			return $returnData;
		}

		$email 		= $this->app->request->post('email');

		//Check for existing email
		if ($this->emailExists($email) > 0) {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'An account with that email already exists.'
			);

			return $returnData;
		}

		try {

			//Hash the user's password
			$hashedPass = hash('sha256', $this->app->request->post('password'));

			$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
			$stmt = $this->core->dbh->prepare($sql);

			if ($stmt->execute(
				array(
					':email' 	=> $email,
					':password' => $hashedPass,
				)
			)) {

				$newUID = $this->core->dbh->lastInsertId();

				//Set their session
				$_SESSION['userID'] = $newUID;

				$returnData = array(
					'status' 	=> 'success',
					'msg' 		=> 'Your account has been created. One Moment.'
				);

				return $returnData;
			}

		} catch (PDOException $e) {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> $e->getMessage()
			);

			return $returnData;
		}

	}

	/*
	 * Update Email
	 */
	public function updateEmail () {

		$email = $this->app->request->post('email');

		//Update their email
		$sql = "UPDATE users set email = :email WHERE id = :userID";
		$stmt = $this->core->dbh->prepare($sql);
		$stmt->execute(array(
			':email' 	=> $email,
			':userID' 	=> $_SESSION['userID']
		));

		$returnData = array(
			'status' 	=> 'success',
			'msg' 		=> 'You have updated your email address.'
		);

		return $returnData;
	}

	/*
	 * Update Password
	 */
	public function updatePassword () {

		$curPassword 	= hash('sha256', $this->app->request->post('current_password'));
		$password1 		= hash('sha256', $this->app->request->post('new_password'));
		$password2 		= hash('sha256', $this->app->request->post('new_password_confirm'));

		if (empty($curPassword) || empty($password1) || empty($password2)) {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Please fill out all of the passwords.'
			);

		} elseif ($curPassword != $this->getStat('password', $_SESSION['userID'])) {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Sorry, but your current password does not match.'
			);

		} elseif ($password1 === $password2 && !empty($curPassword) && !empty($password1) && !empty($password2) ) {

			$hashedPass = hash('sha256', $this->app->request->post('new_password'));

			//Update their password
			$sql = "UPDATE users set password = :password WHERE id = :userID";
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->execute(array(
				':password' => $hashedPass,
				':userID' 	=> $_SESSION['userID']
			));

			$returnData = array(
				'status' 	=> 'success',
				'msg' 		=> 'You have updated your password.'
			);

		} else {

			$returnData = array(
				'status' 	=> 'fail',
				'msg' 		=> 'Your passwords did not match.'
			);
		}

		return $returnData;
	}

	// Reset a user's password
	public function resetPassword() {

		$email = $this->app->request->post('email');

		//Select the user, if possible
		$sql = "SELECT id, email FROM users WHERE email = '".$email."'";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetch();

			$passwordString = $this->Util->generateStrongPassword(8);
			$newPass = hash('sha256', $passwordString);

			//Update their password
			$sql = "UPDATE users set password = :password WHERE id = :userID";
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->execute(array(
				':password' => $newPass,
				':userID' 	=> $r['id']
			));

			$returnData = array(
				'status' 	=> 'success',
				'msg' 		=> 'We have sent you a new password, check your email. We strongly advise you to login and reset your password.'
			);

			//Send out the email
			$msg = <<<EOT
Car Repair site Password Reset

Someone requested a password reset using your email address. If this is the correct email address, and you initiated the password reset, here it is:
$passwordString

If you did not initiate this password request, please contact us at help@website.com and let us know.
EOT;
			$this->Util->mailer($r['email'], 'RepairMotive Password', $msg, 'help@website.com');
		} else {

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'We did not find any users with that email.'
			);
		}

		return $returnData;
	}

	/*
	 * Check if an email address already exists
	 */
	private function emailExists ($email) {

		$stmt = $this->core->dbh->prepare("SELECT email FROM users WHERE email = '$email' LIMIT 1");
		$stmt->execute();

		return $count = $stmt->rowCount();
	}

	/*
	 * User Count
	 */
	public function userCount () {

		$stmt = $this->core->dbh->prepare("SELECT id FROM users");
		$stmt->execute();

		return $count = $stmt->rowCount();
	}
}