<?php

/**
 * driver.php
 *
 * Handle tasks for drivers
 *  - establish availability status
 *  - list availble/accepted rides
 *  - list of past rides
 *  - accept/launch a ride
 *
 * One option to implement the model for this would be to subclass User and expand for the elements
 * specific to driver objects (rate and availability).
 *
 * @author Clay Bellou
 */

namespace DRyft;

require_once('../bootstrap.php');
//require_once('../DRyft/User.php');

$user = Session::getSession()->getUser();
include '../head.html';
include '../header.html';

if (!$user || (!($user->isCoordinator()) && !($user->isDriver()))) {
    // throw an error and exit
    echo '<h1>Access Denied "You pillow" </h1>';
    //TODO some redirect to somewhere here.
} elseif ($user->isCoordinator()) {
    include '../header-coordinator.html';
    // Present a list of the users in the system
    echo '<h1>Coordinator Page.</h1>';

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
    if ($action == "toggleavailable") {
        Driver::toggleIsAvailable($selectedUser->id());
        echo "<p> Toggled Available status for driver {$selectedUser->id()}</p>";
    }
    $availableDrivers = Driver::getAvailableDrivers();
    $drivers = Driver::getDrivers();

?>

    <br />
    <br />
    <h1>Available Drivers</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Rate</th>
                <th>Actions</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($availableDrivers as $item) {
            ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $item->firstName ?> <?= $item->lastName ?></td>
                    <td><?= $item->username() ?></td>
                    <td><?= $item->rate() ?></td>
                    <td>
                        <form method="POST" action="driver.php?id=<?= $item->id() ?>&action=toggleavailable"><button type="submit" class="btn btn-sm btn-primary">Toggle Available</button></form>
                    </td>
                    <td>
                        <form method="POST" action="user.php?id=<?= $item->id() ?>&action=edit"><button type="submit" class="btn btn-sm btn-primary">Edit</button></form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <br />
    <br />
    <h1>All Drivers</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Rate</th>
                <th>Available?</th>
                <th>Actions</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drivers as $item) {
                $availible = "No";
                $bgColor = "color:red";
                if ($item->isAvailable()) {
                    $availible = "Yes";
                    $bgColor = "color:green";
                } ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $item->firstName ?> <?= $item->lastName ?></td>
                    <td><?= $item->username() ?></td>
                    <td><?= $item->rate() ?></td>
                    <td style="<?= $bgColor ?>"><?= $availible ?></td>
                    <td>
                        <form method="POST" action="driver.php?id=<?= $item->id() ?>&action=toggleavailable"><button type="submit" class="btn btn-sm btn-primary">Toggle Available</button></form>
                    </td>
                    <td>
                        <form method="POST" action="user.php?id=<?= $item->id() ?>&action=edit"><button type="submit" class="btn btn-sm btn-primary">Edit</button></form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php

}

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// BEGIN DRIVER VIEW ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
elseif ($user->isDriver()) {
    echo '<h1>Driver Page.</h1>';
    //Handle setting driver availability.
    $action = $_REQUEST[PARAM_ACTION];
    // determine if a user has been provided
    $selectedUser = null;

    if ($action == "activatedriver") {
        Driver::setIsAvailable($user->id(), true);
        //echo "<p> You are now set as Available to do drives!</p>";
    } elseif ($action == "deactivatedriver") {
        Driver::setIsAvailable($user->id(), false);
        //echo "<p> You are now set as no longer available to do drives.</p>";
    }

    //Determine if driver is currently already Available or not.
    $driver = Driver::getDriverById($user->id());
    $isAvailable = false;
    if ($driver->isAvailable()) {
        $isAvailable = true;
    }
    //Display a different button depending if they are already set as Available or not.
    if ($isAvailable) {
    ?>
        <h3> You are currently <span style="color:green">Available</span> to do drives! </h3>
        <p> Press the button below to mark yourself as unavailable when you can no longer do drives.</p>
        <form method="POST" action="driver.php?action=deactivatedriver">
            <button type="submit" class="btn btn-sm btn-primary">Become Unavailable (Clock out)</button>
        </form>

    <?php
    } else {
    ?>
        <h3> You are currently <span style="color:red">Not Available</span> to do drives! </h3>
        <p> Whenever you are ready, Press the button below to mark yourself as available!</p>
        <form method="POST" action="driver.php?action=activatedriver">
            <button type="submit" class="btn btn-sm btn-primary">Become Available (Clock in)</button>
        </form>
    <?php
    }

    $driversRides = Ride::getRidesByDriver($user->id());
    ?>
    <br />
    <br />
    <h1>Drive History.</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client ID</th>
                <th>Client Name</th>
                <th>Pickup ID</th>
                <th>Dropoff ID</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Miles</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($driversRides as $item) {
                $clientUser = User::getUserById($item->clientID());
                $clientName = "{$clientUser->firstName()} {$clientUser->lastName()}";
            ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $item->clientID() ?></td>
                    <td><?= $clientName ?></td>
                    <td><?= $item->pickupLocationID() ?></td>
                    <td><?= $item->dropoffLocationID() ?></td>
                    <td><?= $item->departureTime() ?></td>
                    <td><?= $item->arrivalTime() ?></td>
                    <td><?= $item->mileage() ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php

}


include '../testing_links.html';



// add page footer
include '../footer.html';
