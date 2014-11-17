<?php
	
	require_once 'rb.php';
	require_once 'util.php';
	require_once 'logger.php';
	require_once 'api/whatsprot.class.php';
	require_once 'api/events/AllEvents.php';
	// require_once 'api/events/MyEvents.php';

	// Event handler class
	class EventHandler extends AllEvents {
		protected $logger;
		public $activeEvents = array(
			'onConnect',
			'onDisconnect',
			'onGetMessage'
		);
		
		public function __construct($_logger, $client) {
			parent::__construct($client);
			$this->logger = $_logger;
			$this->log("Instantiated logger");
		}

		public function log($message) {
			$this->logger->i($message);
		}

		public function onConnect($mynumber, $socket)
	    {
	    	$this->log("WooHoo!, Phone number $mynumber connected successfully!");
	    }

	    public function onDisconnect($mynumber, $socket)
	    {	        
	        $this->log("Booo!, Phone number $mynumber is disconnected!");
	    }

	    public function onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body ) {
	    	$this->log("Message received");	
	    }
	}	


	try
	{
		$target = setup();
		$log = new PHPLogger("log/".$target.".log");		
		
		$account = getAccount($target);

		$password = $account->whatsapp_password;
		$name = $account->name;
		$identity = createIdentity($target);

		$log->d( "Welcome ".$account);
		$log->i( "Password: ".$password);
		$log->i( "Name: ".$name);
		$log->i( "Identity: ".$identity);

		// About to log in

		$w = new WhatsProt($target, $identity, $nickname, true);
		$event_handler = new EventHandler($log, $w);
		$log->i("Logging in...");

		$w->connect();	
		$w->loginWithPassword($password);
		$w->sendGetServerProperties();
		$w->sendClientConfig();

		$log->i( "Logged in");
		// $event_handler->startListening();
		$event_handler->setEventsToListenFor($event_handler->activeEvents);
		
		$count = 0;
		while($count < 5) {
			$log->i( "Poll pass ".$count);
			$w->pollMessage();
			sleep(5);
			$count += 1;
		}


	    $w->disconnect();
		$log->i( "Logged out");
	}
	catch(Exception $e) {
		$log->e( "Error : ".$e);
	}