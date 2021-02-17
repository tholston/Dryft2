<?php
/**
 * index.php
 *
 * Initial page for users. Should redirect based on session status and user type.
 */

require_once( '../bootstrap.php' );

// try to load a user
if ( array_key_exists( 'user', $_REQUEST ) ) {
	$user = \DRyft\User::getUserByName( $_REQUEST['user'] );
}
if ( $user ) {
	echo '<h1>Hello ', $user->username(), '</h1>';
}
else {
	echo '<h1>Hello world</h1>';
}