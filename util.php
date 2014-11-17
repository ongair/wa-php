<?php
	
	function createIdentity($username) {
		return strtolower(sha1($username, false));
	}

	function setup() {
		date_default_timezone_set('Africa/Nairobi');

		if ($_SERVER['argv'][1] == null) {
		    echo "No target phone number\r\n";
		    exit(1);
		}

		setupDB();

		return $_SERVER['argv'][1];
	}

	function setupDB() {
		R::setup('mysql:host=localhost:8889;dbname=ongair_prod','root','root', true);
	}

	function getAccount($phoneNumber) {
		$account  = R::findOne( 'accounts', ' phone_number = ?', [$phoneNumber] );
		return $account;
	}