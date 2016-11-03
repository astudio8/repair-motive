<?php
namespace CarRepair\Controllers;

use CarRepair\Lib\Core,
    PDO,
    CarRepair\Models\UserModel,
	CarRepair\Models\CarModel;

class User {

	protected $core;

	function __construct() {
		global $app;
		$this->app = $app;

		$this->core = Core::getInstance();
		$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//Set their Session ID
		if(isset($_SESSION['userID'])) {
			$this->UID = $_SESSION['userID'];
		} else {
			$this->UID = NULL;
		}

		$this->UserModel = new UserModel();
		$this->CarModel = new CarModel();
	}

	/*
	 * Dashboard View
	 */
	public function Index () {

		if(!isset($_SESSION['userID'])) {
			$this->app->redirect('/login');
		}

		$data = [
			'UID' 	=> $this->UID,
			'me' 	=> $this->UserModel->myData($_SESSION['userID']),
			'cars'	=> $this->CarModel->userCars($_SESSION['userID'])
		];

		$this->app->render('user/dashboard.twig', $data);
	}

	/*
	 * Add a Car
	 */
	public function AddCar () {

		if(!isset($_SESSION['userID'])) {
			$this->app->redirect('/login');
		}

		$data = [
			'UID' 	=> $this->UID,
			'me' 	=> $this->UserModel->myData($_SESSION['userID']),
			'cars'	=> $this->CarModel->userCars($_SESSION['userID']),
			'make'	=> $this->CarModel->carDataColumn('car_make'),
			'model'	=> $this->CarModel->carDataColumn('car_model')
		];

		$this->app->render('user/addCar.twig', $data);
	}

	/*
	 * View My Cars
	 */
	public function MyCar ($id) {

		if(!isset($_SESSION['userID'])) {
			$this->app->redirect('/login');
		}

		$data = [
			'UID' 	=> $this->UID,
			'me' 	=> $this->UserModel->myData($_SESSION['userID']),
			'car'	=> $this->CarModel->userCarData($id),
			'make'	=> $this->CarModel->carDataColumn('car_make'),
			'model'	=> $this->CarModel->carDataColumn('car_model')
		];

		$this->app->render('user/car.twig', $data);
	}

	/*
	 * Account Settings View
	 */
	public function Settings() {

		if(!isset($_SESSION['userID'])) {
			$this->app->redirect('/login');
		}

		$data = [
			'me' => $this->UserModel->myData($_SESSION['userID'])
		];

		$this->app->render('user/account.twig', $data);
	}

	/*
	 * Process login form
	 */
	public function Login() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->login());
	}

	/*
	 * Process logout
	 */
	public function Logout() {

		unset($_SESSION['userID']);
		session_destroy();

		$this->app->redirect('/');
	}

	/*
	 * Process signup form
	 */
	public function Register() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->register());
	}

	/*
	 * Update Email Form
	 */
	public function UpdateEmail() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->updateEmail());
	}

	/*
	 * Update Password Form
	 */
	public function UpdatePassword() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->updatePassword());
	}

	/*
	 * Update General Settings Form
	 */
	public function UpdateSettings() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->updateSettings());
	}

	/*
	 * Reset Password via Email
	 */
	public function ResetPassword() {
		header("Content-Type: application/json");
		echo json_encode($this->UserModel->resetPassword());
	}

	/*
	 * Add a new car to garage
	 */
	public function AddCarGarage() {
		header("Content-Type: application/json");
		echo json_encode($this->CarModel->addCar());
	}

	/*
	 * Update a car
	 */
	public function UpdateCar() {
		header("Content-Type: application/json");
		echo json_encode($this->CarModel->editCar());
	}
}