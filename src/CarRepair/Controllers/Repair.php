<?php
namespace CarRepair\Controllers;

use CarRepair\Lib\Core,
    PDO,
    CarRepair\Models\UserModel,
	CarRepair\Models\CarModel,
	CarRepair\Models\RepairModel;

class Repair {

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
	 * Add a Repair
	 */
	public function AddRepair () {

		$data = [
			'UID' 		=> $this->UID,
			'me' 		=> $this->UserModel->myData($_SESSION['userID']),
			'cars'		=> $this->CarModel->userCars($_SESSION['userID']),
			'carCount' 	=> $this->CarModel->userCarCount($_SESSION['userID']),
		];

		$this->app->render('user/addRepair.twig', $data);
	}

	/*
	 * List Repairs for a car
	 */
	public function ListRepairs ($id) {

		if(!isset($_SESSION['userID'])) {

			$this->app->redirect('/login');
		} else {
			$data = [
				'UID' 			=> $this->UID,
				'me' 			=> $this->UserModel->myData($_SESSION['userID']),
				'cars' 			=> $this->CarModel->userCars($_SESSION['userID']),
				'car'			=> $this->CarModel->userCarData($id),
				'make' 			=> $this->CarModel->carDataColumn('car_make'),
				'model' 		=> $this->CarModel->carDataColumn('car_model'),
				'repairs' 		=> $this->RepairModel->carRepairs($id),
				'repairTotal' 	=> $this->RepairModel->vehicleRepairTotal($id)
			];

			$this->app->render('repairs/repairs.twig', $data);
		}
	}

	/*
	 * List all the repairs history
	 */
	public function AllRepairs () {

		if(!isset($_SESSION['userID'])) {
			$this->app->redirect('/login');
		} else {

			$data = [
				'UID' 			=> $this->UID,
				'me' 			=> $this->UserModel->myData($_SESSION['userID']),
				'cars' 			=> $this->CarModel->userCars($_SESSION['userID']),
				'repairs'		=> $this->RepairModel->userRepairs($_SESSION['userID'], 100),
				'repairTotal' 	=> $this->RepairModel->allRepairTotal($_SESSION['userID'])
			];

			$this->app->render('repairs/allRepairs.twig', $data);
		}
	}

	/*
	 * View a single repair
	 */
	public function ViewRepair ($id) {

		$data = [
			'UID' 			=> $this->UID,
			'me' 			=> $this->UserModel->myData($_SESSION['userID']),
			'cars'			=> $this->CarModel->userCars($_SESSION['userID']),
			'repair' 		=> $this->RepairModel->repairData($id),
			'car'			=> $this->RepairModel->carFromRepairID($id)
		];

		$this->app->render('repairs/single.twig', $data);
	}

	/*
	 * Save Repair to a user's car
	 */
	public function SaveRepair() {
		header("Content-Type: application/json");
		echo json_encode($this->RepairModel->saveRepair());
	}

	/*
	 * Common Repair List
	 */
	public function CommonRepairs() {
		header("Content-Type: application/json");
		echo json_encode($this->RepairModel->commonRepairList());
	}
}