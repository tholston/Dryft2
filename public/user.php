<?php

/**
 * user.php
 *
 * Handle user CRUD.
 *
 * @author Errol Sayre
 */

namespace DRyft;

// Setup the bootstrap
require_once('../bootstrap.php');

// Require a coordinator user session
$user = Session::getSession()->getUser();

// now that actions that modify headers are complete, start the page template

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Please login | DRyft</title>' . PHP_EOL;

// add page header based on the user's access level

// determine if the user has access to this "viewtroller"
if (!$user || !$user->isCoordinator()) {

	include '../header.html';
	// throw an error and exit
	echo '<h1>Access Denied</h1>';
} else {

	include '../header-coordinator.html';

	// determine which action we're here to accomplish
	$action = $_REQUEST[PARAM_ACTION];

	// determine if a user has been provided
	$selectedUser = null;
	if (array_key_exists(PARAM_ID, $_REQUEST)) {
		try {
			$selectedUser = User::getUserById(intval($_REQUEST[PARAM_ID]));
		} catch (Database\Exception $e) {

			// if no user was found display the error and drop out with a dummy action
			echo '<h1>Unable to locate user for id: ' . intval($_REQUEST[PARAM_ID]) . '</h1>';
			echo '<p>' . $e->getMessage() . '</p>';
			$action = ACTION_ERROR;
		}
	}

	if ($action == ACTION_EDIT) {
		// display the edit form for the selected user
		// ensure the user
		echo '<h1>Edit ' . $selectedUser->firstName . ' ' . $selectedUser->lastName . ' (' . $selectedUser->id() . ')</h1>';
	} elseif ($action == ACTION_UPDATE) {
		// load updates for the user from the request
		echo '<h1>WILL IT UPDATE?</h1>';
	} elseif ($action == ACTION_CREATE) {
		// load data to create a new user
		echo '<h1>WILL IT CREATE?</h1>';
	} elseif ($action == ACTION_NEW) {
		// display the edit form for an empty user
		echo '<h1>Create a new user</h1>';
	} else {
		// Present a list of the users in the system
		$users = User::getUsers();
?>
		<h1>Existing users</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Username</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($users as $item) {
				?>
					<tr>
						<td><?= $item->id() ?></td>
						<td><?= $item->firstName ?></td>
						<td><?= $item->lastName ?></td>
						<td><?= $item->username() ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
<?php
	}
}

// add page footer
include '../footer.html';
