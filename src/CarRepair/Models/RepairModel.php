<?php
namespace CarRepair\Models;

use CarRepair\Lib\Core,
	PDO,
	CarRepair\Models\CarModel;

class RepairModel {

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

		$this->CarModel = new CarModel();

	}

	/*
	 * Get list of repairs for a user (no specific car)
	 */
	public function userRepairs ($id, $limit) {

		$sql = "SELECT * FROM repairs WHERE user_id = $id ORDER BY id DESC LIMIT $limit";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//Pull in the details from the user's vehicle
			foreach($r as $key => $value){

				//Get Master Policy info
				$vehicle = $this->CarModel->userCarData($r[$key]['car_id']);

				$r[$key]['vehicle'] = $vehicle['car_year'].' '.$vehicle['car_make'].' '.$vehicle['car_model'];
			}

		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get list of repairs for a car
	 */
	public function carRepairs ($id) {

		$sql = "SELECT * FROM repairs WHERE car_id = $id ORDER BY id DESC";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get data for single repair
	 */
	public function repairData ($id) {

		$sql = "SELECT * FROM repairs WHERE id = $id AND user_id = ". $_SESSION['userID'];
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetch();
		} else {
			$r = 0;
		}

		return $r;
	}

	/*
	 * Get car based on repair ID
	 */
	public function carFromRepairID ($id) {
		$sql = "SELECT car_id FROM repairs WHERE id = $id";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetch();
			$vehicle = $this->CarModel->userCarData($r['car_id']);
		}

		return $vehicle['car_year'].' '.$vehicle['car_make'].' '.$vehicle['car_model'];
	}

	/*
	 * Save a repair
	 */
	public function saveRepair () {

		$vehicleID 	= $this->app->request->post('vehicle');
		$item 		= $this->app->request->post('item');
		$cost 		= preg_replace("/[^0-9]/","", $this->app->request->post('cost'));
		$shopName 	= $this->app->request->post('shop_name');
		$shopAddy 	= $this->app->request->post('shop_address');
		$mileage 	= preg_replace("/[^0-9]/","", $this->app->request->post('mileage'));

		//If anything was left empty
		if (empty($vehicleID) || empty($item) || empty($cost)){

			$returnData = array(
				'status' 	=> 'error',
				'msg' 		=> 'Please fill out the required fields.'
			);

		} else {

			//Add car to the user's car table
			$carSQL = "INSERT INTO repairs (user_id, car_id, item, cost, shop_name, shop_address, mileage)
						VALUES (:u_id, :c_id, :item, :cost, :shop_name, :shop_addy, :mileage)";
			$carStmt = $this->core->dbh->prepare($carSQL);
			$carStmt->execute(array(
				':u_id' 		=> $_SESSION['userID'],
				':c_id' 		=> $vehicleID,
				':item'			=> $item,
				':cost'			=> $cost,
				':shop_name' 	=> $shopName,
				':shop_addy' 	=> $shopAddy,
				':mileage'		=> $mileage
			));

			$returnData = array(
				'status' 	=> 'success',
				'msg' 		=> 'You have saved the repair. You may add another one.'
			);
		}

		return $returnData;
	}

	/*
	 * Get the total repair costs for a vehicle
	 */
	public function vehicleRepairTotal ($id) {
		$stmt = $this->core->dbh->prepare("SELECT sum(cost) AS totalCost FROM repairs WHERE car_id = $id");

		if ($stmt->execute()) {
			$r = $stmt->fetch(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r['totalCost'];
	}

	/*
	 * Get the total repair costs all repairs
	 */
	public function allRepairTotal ($uid) {
		$stmt = $this->core->dbh->prepare("SELECT sum(cost) AS totalCost FROM repairs WHERE user_id = $uid");

		if ($stmt->execute()) {
			$r = $stmt->fetch(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r['totalCost'];
	}

	/*
	 * Count total number of repairs
	 */
	public function repairCount () {

		$stmt = $this->core->dbh->prepare("SELECT id FROM repairs");
		$stmt->execute();

		return $count = $stmt->rowCount();
	}

	/*
	 * Get list of repairs for a car
	 */
	public function commonRepairList () {

		$searchTerm = $this->app->request->post('item');

		$sql = "SELECT * FROM common_repairs WHERE repair LIKE '%$searchTerm%'";
		$stmt = $this->core->dbh->prepare($sql);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}

		return $r;
	}

}