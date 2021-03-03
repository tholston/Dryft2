<?php

/**
 * payments.php
 *
 * List upcoming and past payments for a driver.
 *
 * @author Clay Bellou
 */

namespace DRyft;

require_once('../bootstrap.php');


$user = Session::getSession()->getUser();
include '../head.html';
include '../header.html';

$action = $_REQUEST[Constants::PARAM_ACTION];
$linker = new Linker;

if (!$user || (!($user->isCoordinator()) && !($user->isDriver()))) {
    // throw an error and exit
    echo '<h1>Access Denied</h1>';
    //TODO some redirect to somewhere here.

}
//Display all rides for the given payment.
//Same page for Coordinator and driver.
elseif ($action == "viewpaymentrides") {
    echo "<h1>All rides for specific Payment page.</h1>";
    if ($user->isCoordinator() || $user->isDriver()) {
        if (array_key_exists(Constants::PARAM_ID, $_REQUEST)) {
            $paymentID = intval($_REQUEST[Constants::PARAM_ID]);
            $rides = Ride::getRidesForPayment($paymentID);
            $driverID = 0;

?>
            <br />
            <br />
            <h3>Payment id=<?= $paymentID ?></h3>
            <?php
            if ($user->isDriver()) {
                echo "<h2>All rides in specified payment for {$user->firstName()} {$user->lastName()}</h2>";
            }
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client ID</th>
                        <th>Client Name</th>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Miles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rides as $item) {
                        //This is needed for Coordinator viewing.
                        if ($driverID == 0) {
                            $driverID = $item->driverID();
                        }
                        $clientUser = User::getUserById($item->clientID());
                        $clientName = "{$clientUser->firstName()} {$clientUser->lastName()}";
                        $Pickup = 0;
                        $Dropoff = 0;
                        try {
                            $Pickup = Address::getAddressbyId($item->pickupLocationID());
                            $Pickup = $Pickup->__toString();
                            $Dropoff = Address::getAddressbyId($item->dropoffLocationID());
                            $Dropoff = $Dropoff->__toString();
                        } catch (Database\Exception $e) {
                            $Pickup = "Invalid Location";
                            $Dropoff = "Invalid Location";
                        }
                    ?>
                        <tr>
                            <td><?= $item->id() ?></td>
                            <td><?= $item->clientID() ?></td>
                            <td><?= $clientName ?></td>
                            <td><?= $Pickup ?></td>
                            <td><?= $Dropoff ?></td>
                            <td><?= $item->departureTime() ?></td>
                            <td><?= $item->arrivalTime() ?></td>
                            <td><?= $item->mileage() ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php
            //If user is coordinator, we didn't actually know who the driver was until after, so now display their name/username!
            if ($user->isCoordinator()) {
                if ($driverID != 0) {
                    $driver = Driver::getDriverById($driverID);
                    echo "<h1>All above rides for Driver {$driver->firstName()} {$driver->lastName()} (Username:{$driver->username()})</h1>";
                }
            }
        } else {
            echo '<h1>Error, PAYMENT_ID not provided!</h1>';
        }
    } else {
        echo '<h1>Access Denied</h1>';
        //TODO some redirect to somewhere here.
    }
}

/////////////////////////////                           ///////////////////////////////////////////
/////////////////////////////  BEGIN COORDINATOR VIEW   ///////////////////////////////////////////
/////////////////////////////                           ///////////////////////////////////////////
elseif ($user->isCoordinator()) {
    echo '<h1>Coordinator Payment View</h1>';
    //viewpayments action means Coordinator is looking at 1 specific driver's payments.
    if ($action == "viewpayments") {
        // determine if a user has been provided
        $selectedUser = null;
        if (array_key_exists(Constants::PARAM_ID, $_REQUEST)) {
            try {
                $selectedUser = User::getUserById(intval($_REQUEST[Constants::PARAM_ID]));
            } catch (Database\Exception $e) {
                // if no user was found display the error and drop out with a dummy action
                echo '<h1>Unable to locate user for id: ' . intval($_REQUEST[Constants::PARAM_ID]) . '</h1>';
                echo '<p>' . $e->getMessage() . '</p>';
                $action = Constants::ACTION_ERROR;
            }
        }
        if ($selectedUser == null) {
            echo '<h1>Page Error, cannot access payments for user.</h1>';
            die();
        }

        echo "<h1>All payments for {$selectedUser->firstName()} {$selectedUser->lastName()}. (Username:{$selectedUser->username()})</h1>";
        $unpaidPayments = Payment::getUnpaidPaymentsByDriver($selectedUser->id());
        $paidPayments = Payment::getPaidPaymentsByDriver($selectedUser->id());
        displayPaidUnpaidTable($unpaidPayments, $paidPayments);
    } else {
        //If coordinator, show list of all drivers to look at payments of.
        $drivers = Driver::getDrivers();

        ?>
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
                            <form method="POST" action="payments.php?id=<?= $item->id() ?>&action=viewpayments">
                                <button type="submit" class="btn btn-sm btn-primary">View Payments</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php
    }
}


////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// BEGIN DRIVER VIEW ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
elseif ($user->isDriver()) {
    echo '<h1>Past and Upcoming Payments</h1>';
    $unpaidPayments = Payment::getUnpaidPaymentsByDriver($user->id());
    $paidPayments = Payment::getPaidPaymentsByDriver($user->id());
    displayPaidUnpaidTable($unpaidPayments, $paidPayments);
}

/**
 * Displays 2 HTML tables showing all Unpaid payments, then all Paid Payments (Using arrays of Payment Objects passed in)
 * @param array of Payment objects
 * @param array of Payment objects
 */
function displayPaidUnpaidTable($unpaidPayments, $paidPayments)
{
    ?>
    <br />
    <br />
    <h1>Unpaid</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mileage</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Actions</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($unpaidPayments as $item) {
            ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $item->mileage() ?></td>
                    <td><?= $item->rate() ?></td>
                    <td>$<?= number_format($item->amount(), 2) ?></td>
                    <td>
                        <form method="POST" action="payments.php?id=<?= $item->id() ?>&action=viewpaymentrides">
                            <button type="submit" class="btn btn-sm btn-primary">View All Payment Rides</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br />
    <br />
    <h1>Paid</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mileage</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Actions</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paidPayments as $item) {
            ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $item->mileage() ?></td>
                    <td><?= $item->rate() ?></td>
                    <td>$<?= number_format($item->amount(), 2) ?></td>
                    <td>
                        <form method="POST" action="payments.php?id=<?= $item->id() ?>&action=viewpaymentrides">
                            <button type="submit" class="btn btn-sm btn-primary">View All Payment Rides</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
}

// add page footer
include '../footer.html';
