<?php

/**
 * index.php
 *
 * Initial page for users. Should redirect based on session status and user type.
 */

require_once('../bootstrap.php');

// determine if we have a user
$user = DRyft\Session::getSession()->getUser();
if (!$user) {
	echo '<h1>Nothing to see here</h1>';
} else {
	echo '<h1>Welcome ' . $user->username() . '</h1>';
	echo '<pre>';
	print_r($user);
	echo '</pre>';
}

include '../testing_links.html';

echo DRyft\Linker::urlPath();
