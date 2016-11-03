<?php
namespace CarRepair\Controllers;

use CarRepair\Lib\Core,
    PDO,
    CarRepair\Models\UserModel,
	CarRepair\Models\CarModel,
	CarRepair\Models\RepairModel;

class Car {

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
		$this->RepairModel = new RepairModel();
	}

	/*
	 * Fetch all the makes by year
	 */
	public function GetMakesByYear () {
		header("Content-Type: application/json");
		echo json_encode($this->CarModel->makesByYear());
	}

	/*
	 * Fetch all the models by make and year
	 */
	public function GetModelsByMakeYear () {
		header("Content-Type: application/json");
		echo json_encode($this->CarModel->modelsByMakeYear());
	}

	/*
	 * Delete Car Request
	 */
	public function DeleteCar () {
		header("Content-Type: application/json");
		echo json_encode($this->CarModel->deleteCar());
	}
}