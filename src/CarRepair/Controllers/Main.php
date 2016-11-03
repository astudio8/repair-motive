<?php

namespace CarRepair\Controllers;

use CarRepair\Models\UserModel,
	CarRepair\Models\CarModel,
	CarRepair\Controllers\Util,
	CarRepair\Models\RepairModel,
	PHPMailer;

class Main {

	protected $core;

	public function __construct() {
		global $app;
		$this->app = $app;

		//Set their Session ID
		if(isset($_SESSION['userID'])) {
			$this->UID = $_SESSION['userID'];
		} else {
			$this->UID = NULL;
		}

		$this->UserModel 	= new UserModel();
		$this->CarModel 	= new CarModel();
		$this->Util 		= new Util();
		$this->RepairsModel = new RepairModel();
	}

	public function Index (){

		if(isset($_SESSION['userID'])) {
			$this->app->redirect('/dashboard');
		} else {

			$data = [
				'carCount' 		=> $this->CarModel->carCount(),
				'repairCount'	=> $this->RepairsModel->repairCount(),
				'userCount'		=> $this->UserModel->userCount()
			];

			$this->app->render('main/home.twig', $data);
		}
	}

	public function Login () {

		$data = [
			'countryList' => $this->Util->countryList()
		];

		$this->app->render('main/login.twig', $data);
	}

	public function Faq (){
		$this->app->render('main/faq.twig');
	}

	public function Terms (){
		$this->app->render('/main/terms.twig');
	}

	public function Privacy (){
		$this->app->render('/main/privacy.twig');
	}

	public function Contact (){
		$this->app->render('/main/contact.twig');
	}

	public function About (){
		$this->app->render('/main/about.twig');
	}

	public function Changelog (){
		$this->app->render('/main/version.twig');
	}

	public function ForgotPass (){
		$this->app->render('/main/forgotPass.twig');
	}

	//Send the actual contact request
	public function ContactSend (){

		header("Content-Type: application/json");

		//Get their email (if supplied)
		$email = $this->app->request->post('email');
		$message = $this->app->request->post('message');

		//Send out the email
		$msg = <<<EOT
RepairMotive - Contact Form

From Email: $email

Message: $message

EOT;
		$this->Util->mailer('help@website.com', 'Contact Form', $msg, $email);

		$returnData = array(
			'status' => 'success',
			'msg' => 'Thank you for contacting us. We will respond within 24 hours usually.'
		);
		echo json_encode($returnData);

	}

}