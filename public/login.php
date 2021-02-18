<?php
/**
 * login.php
 *
 * Handle user authentication
 */

require_once( '../bootstrap.php' );

// try to load a user
$user = DRyft\Session::getSession()->getUser();

if ( array_key_exists( 'user', $_REQUEST ) ) {
	$user = \DRyft\User::getUserByName( $_REQUEST['user'] );
}
if ( array_key_exists( 'logout', $_REQUEST ) ) {
	DRyft\Session::destroy();
	echo '<h1>Logged out.</h1>';
}
elseif ( $user ) {
	// setup the session
	DRyft\Session::getSession()->setupSession( $user );

	echo '<h1>Hello ', $user->username(), '</h1>';
	echo '<pre>';
	print_r( $user );
	echo '</pre>';
	echo '<h2>Started Session</h2>';
}
else {
	echo '<h1>Unable to log in.</h1>';
}

include '../testing_links.html';