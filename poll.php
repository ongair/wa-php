<?php
	
	require_once 'rb.php';
	require_once 'util.php';
	require_once 'logger.php';

	$target = setup();

	$log = new PHPLogger("log/".$target.".log");

	$log->i("SETUP","Welcome ".$target);

	$account = getAccount($target);

	$password = $account->whatsapp_password;
	$name = $account->name;

	$log->i("cred", "Password: ".$password);
	$log->i("cred", "Name: ".$name);

	// $log->d("SETUP","Name: ".$account->name." is empty?");
	// $log->d("SETUP","Name: ".$account);
	// $log->d("SETUP","Name: ".$account->phone_number." is empty?");