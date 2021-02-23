<?php

/**
 * client.php
 *
 * Display the client's scheduled and past rides.
 *
 * This view/script and others like it should accomplish the tasks a client will need to do:
 *  - request a ride
 *  - maintain addresses
 *  - register as a user? (not a stated feature)
 *  - view past rides?
 *
 * @author Austin Marotti
 */


// Setup the bootstrap
require_once( '../bootstrap.php' );
$linker = new DRyft\Linker;

// Require a client user session
$user = DRyft\Session::getSession()->getUser();
if ( !$user || !$user-> isClient()){

	// throw an error and exit
	echo '<h1>Access Denied</h1>';
}
elseif(array_key_exists('request', $_REQUEST)){
	header('Location: ' . $linker->urlPath() . 'ride.php');
	die();
}
elseif(array_key_exists('info', $_REQUEST)){
	echo '<h1>Full Name: ' . $user->firstName() . ' ' . $user->lastName() . '</h1>';
	echo '<h1>Home Address: ' . $user->homeAddress() . '</h1>';

}
elseif(array_key_exists('history', $_REQUEST)){
	
}
else {
	// Presents main clinet menu
	?>
	<form method="POST"> 
          
        <input type="submit" name="request"
                class="button" value="Request a Ride"/> 
		<input type="submit" name="info"
                class="button" value="Personal Information"/> 
		<input type="submit" name="history"
                class="button" value="Ride History"/> 
    </form> 

<?php
}


// Output a page title and any other specific head elements
echo '		<title>Please login | DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';


include '../testing_links.html';
include '../footer.html';