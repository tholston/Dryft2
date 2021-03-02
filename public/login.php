<?php

/**
 * login.php
 *
 * Handle user authentication
 *
 * The basic process for this page is to:
 * - determine existing session
 *   - logout or
 *   - redirect to front page
 * - accept credentials and
 *   - start a session or
 *   - show an error
 * - show the login form
 *
 * @author Errol Sayre
 */

namespace DRyft;

require_once('../bootstrap.php');

// determine if there is a session
$user = Session::getSession()->getUser();

// determine if a username has been submitted
if (
	array_key_exists('user', $_REQUEST) &&
	array_key_exists('password', $_REQUEST)
) {
	// try to validate the provided password
	try {
		$user = User::getUserByName($_REQUEST['user']);
		if ($user->validatePassword($_REQUEST['password'])) {

			// setup the session
			Session::getSession()->setupSession($user);
		} else {
			$error = 'Unable to login: incorrect password.';
			unset($user);
		}
	} catch (Database\Exception $e) {
		$error = 'Unable to locate user: ' . $e->getMessage();
		unset($user);
	}
}

// Setup a shortcut to our application path
$linker = new Linker;

if (array_key_exists('logout', $_REQUEST)) {
	Session::destroy();
	$user = null;
	$message = 'Logged out successfully.';
} elseif ($user) {

	$message = 'Session started.';
	// redirect the user to the main page
	header('Location: ' . $linker->urlPath());
} elseif (array_key_exists('login', $_REQUEST)) {
	$message = 'Unable to log in.';
} else {
	$message = 'Please login';
}


// now that actions that modify headers are complete, start the page template

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Please login | DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';

// Give the user the login form
include '../views/login.html';

// add page footer
include '../footer.html';
