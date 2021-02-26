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
    // Present a list of the users in the system
    echo '<h1>Coordinator Page.</h1>';

    //$db = Database\Connection::getConnection();
    //$result = $db->query($select);

    $drivers = Driver::getDrivers();

?>
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
}


include '../testing_links.html';



// add page footer
include '../footer.html';
