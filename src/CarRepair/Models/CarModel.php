<?php
namespace CarRepair\Models;

use CarRepair\Lib\Core,
    PDO;

class CarModel {

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

	}

	/*
	 * Get all cars for a user
	 */
	public function userCars ($id) {

		$sql = "SELECT * FROM user_cars WHERE user_id = $id";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get data for single user car
	 */
	public function userCarData ($id) {

		$sql = "SELECT * FROM user_cars WHERE id = $id AND user_id = ". $_SESSION['userID'];
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetch();
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get data for a specific point of a car
	 */
	public function carDataColumn ($column) {

		$sql = "SELECT DISTINCT $column FROM cars ORDER BY $column ASC";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Save a new car to the user's Car list
	 */
	public function addCar () {

		$year 	= $this->app->request->post('car_year');
		$make 	= $this->app->request->post('car_make');
		$model 	= $this->app->request->post('car_model');

		//Add car to the user's car table
		$carSQL = "INSERT INTO user_cars (user_id, car_year, car_make, car_model)
						VALUES (:u_id, :cyear, :cmake, :cmodel)";
		$carStmt = $this->core->dbh->prepare($carSQL);
		$carStmt->execute(array(
			':u_id' 	=> $_SESSION['userID'],
			':cyear' 	=> $year,
			':cmake'	=> $make,
			':cmodel'	=> $model
		));

		$returnData = array(
			'status' 	=> 'success',
			'msg' 		=> 'You have add a new car. One moment...'
		);

		return $returnData;
	}

	/*
	 * Edit Car
	 */
	public function editCar () {

		$carID = $this->app->request->post('carID');
		$year = $this->app->request->post('car_year');
		$make = $this->app->request->post('car_make');
		$model = $this->app->request->post('car_model');
		$purchase_year = $this->app->request->post('purchase_year');
		$purchase_millage = $this->app->request->post('purchase_millage');
		$purchase_amount = preg_replace("/[^0-9]/", "", $this->app->request->post('purchase_amount'));

		//Update their password
		$sql = "UPDATE user_cars
				SET car_year = :c_year, car_make = :c_make, car_model = :c_model, purchase_year = :p_year, purchase_amount = :p_amount, purchase_millage = :p_mile
				WHERE user_id = :userID AND id = :c_id";
		$stmt = $this->core->dbh->prepare($sql);
		$stmt->execute(array(
			':c_year' 	=> $year,
			':c_make' 	=> $make,
			':c_model' 	=> $model,
			':p_year' 	=> $purchase_year,
			':p_amount' => $purchase_amount,
			':p_mile'	=> $purchase_millage,
			':userID' 	=> $_SESSION['userID'],
			':c_id'		=> $carID
		));

		$returnData = array(
			'status' 	=> 'success',
			'msg' 		=> 'You have updated this vehicle.'
		);

		return $returnData;
	}

	public function deleteCar () {

		$carID = $this->app->request->post('vehicleid');

		//Get the car
		$car = $this->userCarData($carID);

		//If car doesn't belong to user
		if (empty($car['id'])){

			$returnData = array(
				'status' 	=> 'success',
				'msg' 		=> 'Sorry, there was a problem removing this vehicle.'
			);

		} else {

			//Delete all associated repair data
			$repairStmt = $this->core->dbh->prepare("DELETE FROM repairs WHERE car_id = $carID");
			$repairStmt->execute();

			//Delete the vehicle
			$removeStmt = $this->core->dbh->prepare("DELETE FROM user_cars WHERE id = $carID");
			$removeStmt->execute();

			$returnData = array(
				'status' 	=> 'success',
				'msg' 		=> 'You have removed the vehicle.'
			);
		}

		return $returnData;
	}

	/*
	 * Count total number of cars
	 */
	public function carCount () {

		$stmt = $this->core->dbh->prepare("SELECT id FROM user_cars");
		$stmt->execute();

		return $count = $stmt->rowCount();
	}

	/*
	 * Count total number of cars for a user
	 */
	public function userCarCount ($id) {

		$stmt = $this->core->dbh->prepare("SELECT id FROM user_cars WHERE user_id = $id");
		$stmt->execute();

		return $count = $stmt->rowCount();
	}

	/*
	 * Get all makes by year
	 */
	public function makesByYear () {

		$year = $this->app->request->post('year');

		$sql = "SELECT DISTINCT car_make FROM cars WHERE car_year = $year ORDER BY car_make ASC";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get all makes by year
	 */
	public function modelsByMakeYear () {

		$year = $this->app->request->post('year');
		$make = $this->app->request->post('make');

		$sql = "SELECT DISTINCT car_model FROM cars WHERE car_year = $year AND car_make = '".$make."' ORDER BY car_model ASC";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}
}