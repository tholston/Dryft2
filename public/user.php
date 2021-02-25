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
?>
		<h1>Edit <?= $selectedUser->firstName ?> <?= $selectedUser->lastName ?> (<?= $selectedUser->id() ?>)</h1>
		<form method="POST" action="user.php?id=<?= $selectedUser->id() ?>&action=update" class="needs-validation" novalidate>
			<div class="row g-3">
				<div class="col-sm-6">
					<label for="userType" class="form-label">User Type</label>
					<select class="form-select" id="userType" required="true">
						<option value="">Choose...</option>
						<option <?= $selectedUser->isCoordinator() ? "selected" : "" ?>>Coordinator</option>
						<option <?= $selectedUser->isDriver() ? " selected" : "" ?>>Driver</option>
						<option <?= $selectedUser->isClient() ? " selected" : "" ?>>Client</option>
					</select>
					<div class="invalid-feedback">Please select a valid country.</div>
				</div>
				<div class="col-sm-6">
					<label for="firstName" class="form-label">First name</label>
					<input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
					<div class="invalid-feedback">Valid first name is required.</div>
				</div>
				<div class="col-sm-6">
					<label for="lastName" class="form-label">Last name</label>
					<input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
					<div class="invalid-feedback">Valid last name is required.</div>
				</div>
			</div>
			<hr class="my-4">
			<button class="w-100 btn btn-primary btn-lg" type="submit">Update user</button>
		</form>
		<script src="js/form-validation.js"></script>

	<?php
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
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $item) { ?>
					<tr>
						<td><?= $item->id() ?></td>
						<td><?= $item->firstName ?></td>
						<td><?= $item->lastName ?></td>
						<td><?= $item->username() ?></td>
						<td>
							<form method="POST" action="user.php?id=<?= $item->id() ?>&action=edit"><button type="submit" class="btn btn-sm btn-primary">Edit</button></form>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
<?php
	}
}

// add page footer
include '../footer.html';
