<?php

/**
 * index.php
 *
 * Initial page for users. Should redirect based on session status and user type.
 *
 * @author Errol Sayre
 */

require_once('../bootstrap.php');

// determine if we have a user
$user = DRyft\Session::getSession()->getUser();
$linker = new DRyft\Linker;


// add HTML head
if (!$user) {
	echo '<h1>Nothing to see here</h1>';
} else {
	if($user-> isClient()){
		header('Location: ' . $linker->urlPath() . 'client.php');
		die();
	}
}

include '../head.html';


// Output a page title and any other specific head elements
echo '		<title>Welcome to DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';


// add page footer
include '../footer.html';
?>
