<?php

$Main   = new \CarRepair\Controllers\Main();
$User   = new \CarRepair\Controllers\User();
$Repair = new \CarRepair\Controllers\Repair();
$Car    = new \CarRepair\Controllers\Car();

//Public Facing
$app->get('/', function () use ($Main) {
	$Main->Index();
});

$app->get('/login', function () use ($Main) {
	$Main->Login();
});

$app->get('/logout', function () use ($User) {
	$User->Logout();
});

$app->get('/faq', function () use ($Main) {
	$Main->Faq();
});

$app->get('/terms', function () use ($Main) {
	$Main->Terms();
});

$app->get('/privacy', function () use ($Main) {
	$Main->Privacy();
});

$app->get('/contact', function () use ($Main) {
	$Main->Contact();
});

$app->post('/contact-send', function () use ($Main) {
	$Main->ContactSend();
});

$app->get('/about', function () use ($Main) {
	$Main->About();
});

$app->get('/changelog', function () use ($Main) {
	$Main->Changelog();
});

$app->get('/forgot-pass', function () use ($Main) {
	$Main->ForgotPass();
});

$app->post('/password/reset', function () use ($User) {
	$User->ResetPassword();
});

//Main user overview
$app->get('/dashboard', function () use ($User) {
	$User->Index();
});

$app->get('/settings', function () use ($User) {
	$User->Settings();
});


//View to add a car
$app->get('/add-car', function () use ($User) {
	$User->AddCar();
});

//View to add a repair
$app->get('/add-repair', function () use ($Repair) {
	$Repair->AddRepair();
});

//View all repairs
$app->get('/view-repairs', function () use ($Repair) {
	$Repair->AllRepairs();
});

//View a specific car
$app->get('/mycar/:id', function ($id) use ($User) {
	$User->MyCar($id);
});

//View repair list of a car
$app->get('/repairs/:id', function ($id) use ($Repair) {
	$Repair->ListRepairs($id);
});

//Get list of common repairs
$app->post('/repaircommon', function () use ($Repair) {
	$Repair->CommonRepairs();
});

/*** Backend Calls ***/
$app->post('/user/login', function () use ($User) {
	$User->Login();
});

$app->post('/user/signup', function () use ($User) {
	$User->Register();
});

/* Adding Car */
$app->post('/car/add', function () use ($User) {
	$User->AddCarGarage();
});

$app->post('/car/delete', function () use ($Car) {
	$Car->DeleteCar();
});

//Get the makes for the selected year
$app->post('/car/makeyear', function () use ($Car) {
	$Car->GetMakesByYear();
});

$app->post('/car/modelmakeyear', function () use ($Car) {
	$Car->GetModelsByMakeYear();
});


$app->post('/car/update', function () use ($User) {
	$User->UpdateCar();
});

$app->post('/repair/add', function () use ($Repair) {
	$Repair->SaveRepair();
});

//View to see details of a single repair
$app->get('/repair/:id', function ($id) use ($Repair) {
	$Repair->ViewRepair($id);
});

$app->post('/user/updateemail', function () use ($User) {
	$User->UpdateEmail();
});

$app->post('/user/updatepassword', function () use ($User) {
	$User->UpdatePassword();
});