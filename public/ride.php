<?php

/**
 * ride.php
 *
 * Display the form or accept the submission of a client's request for a ride.
 *
 * @author Noah South
 */

namespace DRyft;

require_once("../bootstrap.php");
$db = Database\Connection::getConnection();
$user = Session::getSession()->getUser();
include '../head.html';
include '../header.html';
$searchuserid = $user->id();
?>


<?php
/* validates user client status to display ride creation form */
if ($user->isClient()) {
    /*
        Displays all current unfinished rides for a user.
    */
?>
    <h3>Current Ride Requests for <?php echo $user->firstName . $user->lastName; ?></h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Request State:</th>
                <th>Pick-up Location</th>
                <th>Drop-off Location</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = "SELECT * FROM rides WHERE client='$searchuserid' AND mileage='0.0'";
            $results = mysqli_query($db, $search);
            while ($row = mysqli_fetch_array($results)) {
                $DState = "";
                if ($row['driver'] == 0){
                    $DState = "Unaccepted";
                }
                else{
                    $DState = "Accepted";
                }
                $Pickup = Address::getAddressbyId($row['pickup']);
                $Pickup = $Pickup->__toString();
                $Dropoff = Address::getAddressbyId($row['dropoff']);
                $Dropoff = $Dropoff->__toString();
                echo "<tr>";
                echo "<td>" . $DState . "</td>";
                echo "<td>" . $Pickup . "</td>";
                echo "<td>" . $Dropoff . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>

<?php } ?>
<?php if ($user->isCoordinator()) { ?>

    <?php
    $select_state_accept = false;
    $select_state_finish = false;

    /*
        This method gets all the necessary information that a coordinator will need to assign a rider.
        dropoff and pickup values are certainly important to be specified so that no "rogue" entries are also altered.
    */
    if (isset($_GET['assign'])) {
        $Aid = $_GET['assign'];
        $select_state_accept = true;
        /*
        $Aselect = "SELECT * FROM rides WHERE RIDE_ID='$Aid'";
        $Arec = mysqli_query($db, $Aselect);
        $Arecord = mysqli_fetch_array($Arec);
        $Aclient = $Arecord['client'];
        $Adriver = $Arecord['driver'];
        $Apickup = $Arecord['pickup'];
        $Adropoff = $Arecord['pickup'];
        $Adeparture = $Arecord['departure'];
        $Aarrival = $Arecord['arrival'];
        $Amileage = $Arecord['mileage'];
        */
    } else {
        $select_state_accept = false;
        $Aid = NULL;
        /*
        $Aclient = NULL;
        $Adriver = NULL;
        $Apickup = NULL;
        $Adropoff = NULL;
        $Adeparture = NULL;
        $Aarrival = NULL;
        $Amileage = NULL;
        */
    }

    /*
            This method gets all the necessary information that a coordinator will need to properly finish the ride.
            This method will then pass these values to a method for handling when the coordinator finishes.
            dropoff and pickup values are certainly important to be specified so that no "rogue" entries are also altered.
        */
    if (isset($_GET['finish'])) {
        $Bid = $_GET['finish'];
        $select_state_finish = true;
        /*
        $Bselect = "SELECT * FROM rides WHERE RIDE_ID='$Bid'";
        $Brec = mysqli_query($db, $Bselect);
        $Brecord = mysqli_fetch_array($Brec);
        $Bclient = $Brecord['client'];
        $Bdriver = $Brecord['driver'];
        $Bpickup = $Brecord['pickup'];
        $Bdropoff = $Brecord['pickup'];
        $Bdeparture = $Brecord['departure'];
        $Barrival = $Brecord['arrival'];
        $Bmileage = $Brecord['mileage'];
        */
    } else {
        $select_state_accept = false;
        $Bid = NULL;
        /*
        $Bclient = NULL;
        $Bdriver = NULL;
        $Bpickup = NULL;
        $Bdropoff = NULL;
        $Bdeparture = NULL;
        $Barrival = NULL;
        $Bmileage = NULL;
        */
    }
    ?>

    <h3>Unaccepted Ride Requests</h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Client</th>
                <th>Driver</th>
                <th>Pick-up</th>
                <th>Drop-off</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Mileage</th>
                <th colspan="2">Assign Driver</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = "SELECT * FROM rides WHERE driver='0'";
            $results = mysqli_query($db, $search);
            while ($row = mysqli_fetch_array($results)) {
                $Pickup = Address::getAddressbyId($row['pickup']);
                $Pickup = $Pickup->__toString();
                $Dropoff = Address::getAddressbyId($row['dropoff']);
                $Dropoff = $Dropoff->__toString();
                echo "<tr>";
                echo "<td>" . $row['client'] . "</td>";
                echo "<td>" . $row['driver'] . "</td>";
                echo "<td>" . $Pickup . "</td>";
                echo "<td>" . $Dropoff . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "<td>" . $row['mileage'] . "</td>";
                echo "<td><a href='ride.php?assign=" . $row['RIDE_ID'] . "'>Assign</a></td>";
                echo "<td><a href='ridefunction.php?rejection=" . $row['RIDE_ID'] . "'>Reject</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <form method='post' action='ridefunction.php'>
        <h6>Selected Ride: <?php echo $Aid ?> - Client: <?php echo $Aclient ?></h6>
        <label for='driveassign'>Assign a Driver: </label>
        <input type='number' name='driveassign'><br>
        <input type='hidden' name='id' value='<?php echo $Aid ?>'>
        <button type='submit' name='driverassignment' class="btn btn-sm btn-primary">Accept</button>
        <a class="btn btn-sm btn-secondary" href='ride.php'>Deselect Entry</a>
    </form>

    <?php
        /*
            Prints out all drivers in the system so that the coordinator may choose a valid driver when accepting a ride.
        */
    ?>
    <br>
    <h6>Valid Drivers:</h6>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Driver ID</th>
                <th>Name</th>
                <th>Pay Rate</th>
                <th>Currently Available</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $search = "SELECT * FROM driver_attributes";
                $results = mysqli_query($db, $search);
                while ($row = mysqli_fetch_array($results)){
                    $driver = Driver::getDriverById($row['DRIVER_ID']);
                    echo "<tr>";
                    echo "<td>" . $row['DRIVER_ID'] . "</td>";
                    echo "<td>" . $driver->firstName . " " . $driver->lastName . "</td>";
                    echo "<td>" . $driver->rate() . "</td>";
                    echo "<td>" . $driver->isAvailable() . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

    <br><br>
    <h3>Accepted - Unfinished</h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Client</th>
                <th>Driver</th>
                <th>Pick-up</th>
                <th>Drop-off</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Mileage</th>
                <th colspan="2">Finish Drive</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /*
                Queries the database to retrieve all rides within the system which have a 0.0 mileage and 0 is not the ID for driver.
                This means that the ride was accepted and a "valid" driver has been assigned to this ride.
                0.0 is used to denote that the ride has not been completed as you would not know how long the ride was until after completion.
                All Rides which meet this criteria will be displayed to be finished by the coordinator.
            */
            $search = "SELECT * FROM rides WHERE mileage='0.0' AND driver!='0'";
            $results = mysqli_query($db, $search);
            while ($row = mysqli_fetch_array($results)) {
                $Pickup = Address::getAddressbyId($row['pickup']);
                $Pickup = $Pickup->__toString();
                $Dropoff = Address::getAddressbyId($row['dropoff']);
                $Dropoff = $Dropoff->__toString();
                echo "<tr>";
                echo "<td>" . $row['client'] . "</td>";
                echo "<td>" . $row['driver'] . "</td>";
                echo "<td>" . $Pickup . "</td>";
                echo "<td>" . $Dropoff . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                if ($select_state_finish == true && $Bid == $row['RIDE_ID']) {
                    echo "<td><form method='post' action='ridefunction.php'>";
                    echo "<input type='number' step='0.001' name='mileageassign'>";
                    echo "<input type='hidden' name='id' value='" . $row['RIDE_ID'] . "'>";
                    echo "<button type='submit' name='mileageassignment' class='btn btn-sm btn-primary'>Accept</button>";
                    echo "</form></td>";
                    echo "<td><a href='ride.php?finish=" . NULL . "'>Deselect</a></td>";
                } else {
                    echo "<td>" . $row['mileage'] . "</td>";
                }
                echo "<td><a href='ride.php?finish=" . $row['RIDE_ID'] . "'>Select</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?php } ?>

<?php
/*
        Input is taken from a coordinator or client which will then be used to create a ride request.
        In the coordinators case, this is due to the possible of alternate means of scheduling such as a phone call or email.
        As such, their ID (Coordinator) will be associated with the ride for possibility that the alternate communicator may not have an account within the system.

        In the clients case, this is how they will schedule rides for later or current use.
    */
if ($user->isCoordinator() || $user->isClient()) {
?>
    <br><br>
    <h6>Create a Ride Request</h6>
    <form method='post' action='ridefunction.php'>
        <input type="hidden" name="userref" value="<?php echo $searchuserid; ?>">

        <div>
            <h5>Pick-up Location Information:</h5>
            <label for="PickNickn">Address Nickname: </label>
            <input type="text" name="PickNickn" maxlength='60' required><br>
            <label for="PickLinone">Address Line 1: </label>
            <input type="text" name="PickLinone" maxlength='60' required><br>
            <label for="PickLintwo">Address Line 2: </label>
            <input type="text" name="PickLintwo" maxlength='60'><br>
            <label for="PickCit">Address City: </label>
            <input type="text" name="PickCit" maxlength='60' required><br>
            <label for="PickStat">Address State: </label>
            <input type="text" name="PickStat" maxlength='2' required><br>
            <label for="PickZip">Address Zip Code: </label>
            <input type="text" name="PickZip" maxlength='10' required><br>
        </div>

        <div>
            <h5>Drop-off Location Information:</h5>
            <label for="DropNickn">Address Nickname: </label>
            <input type="text" name="DropNickn" maxlength='60' required><br>
            <label for="DropLinone">Address Line 1: </label>
            <input type="text" name="DropLinone" maxlength='60' required><br>
            <label for="DropLintwo">Address Line 2: </label>
            <input type="text" name="DropLintwo" maxlength='60'><br>
            <label for="DropCit">Address City: </label>
            <input type="text" name="DropCit" maxlength='60' required><br>
            <label for="DropStat">Address State: </label>
            <input type="text" name="DropStat" maxlength='2' required><br>
            <label for="DropZip">Address Zip Code: </label>
            <input type="text" name="DropZip" maxlength='10' required><br>
        </div>

        <div>
            <h5>Pick-up Time Information:</h5>
            <label for="DeptYear">Appointment Depature - Year: </label>
            <input type="number" name="DeptYear" maxlength='4' required><br>
            <label for="DeptMonth">Appointment Depature - Month (January = 1 & December = 12): </label>
            <input type="number" name="DeptMonth" maxlength='2' required><br>
            <label for="DeptDay">Appointment Depature - Day: </label>
            <input type="number" name="DeptDay" maxlength='2' required><br>
            <label for="DeptHour">Appointment Depature - Hour (12-hour based): </label>
            <input type="number" name="DeptHour" maxlength='2' required><br>
            <label for="DeptMinute">Appointment Depature - Minute: </label>
            <input type="number" name="DeptMinute" maxlength='2' required><br>
            <label for="DeptAMP">Appointment (AM / PM): </label>
            <input type="text" name="DeptAMP" maxlength='2' required><br>
        </div>

        <div>
            <h5>Drop-off Time Information:</h5>
            <label for="ArriHour">Appointment Expected Arrival - Hour (12-hour based): </label>
            <input type="text" name="ArriHour" maxlength='2' required><br>
            <label for="ArriMinute">Appointment Expected Arrival - Minute: </label>
            <input type="text" name="ArriMinute" maxlength='2' required><br>
            <label for="ArriAMP">Appointment (AM / PM): </label>
            <input type="text" name="ArriAMP" maxlength='2' required><br>
        </div>

        <button type="submit" name="ridereq" class="btn btn-sm btn-primary">Submit Request</button>
    </form>
<?php } ?>

<?php
    /*
        This section allows for clients to see their prior rides and coordinators all previous rides.
    */
    if ($user->isClient()) {
?>
    <br>
    <h3>Previous Ride Requests for <?php echo $user->firstName . $user->lastName; ?></h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Pick-up Location</th>
                <th>Drop-off Location</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = "SELECT * FROM rides WHERE client='$searchuserid' AND mileage!='0.0'";
            $results = mysqli_query($db, $search);
            while ($row = mysqli_fetch_array($results)) {
                $Pickup = Address::getAddressbyId($row['pickup']);
                $Pickup = $Pickup->__toString();
                $Dropoff = Address::getAddressbyId($row['dropoff']);
                $Dropoff = $Dropoff->__toString();
                echo "<tr>";
                echo "<td>" . $Pickup . "</td>";
                echo "<td>" . $Dropoff . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php
    }
    if ($user->isCoordinator()) {
?>
    <br>
    <h3>All Finished Ride Requests:</h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Client</th>
                <th>Driver</th>
                <th>Pick-up</th>
                <th>Drop-off</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Mileage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = "SELECT * FROM rides WHERE mileage!='0.0'";
            $results = mysqli_query($db, $search);
            while ($row = mysqli_fetch_array($results)) {
                $Pickup = Address::getAddressbyId($row['pickup']);
                $Pickup = $Pickup->__toString();
                $Dropoff = Address::getAddressbyId($row['dropoff']);
                $Dropoff = $Dropoff->__toString();
                echo "<tr>";
                echo "<td>" . $row['client'] . "</td>";
                echo "<td>" . $row['driver'] . "</td>";
                echo "<td>" . $Pickup . "</td>";
                echo "<td>" . $Dropoff . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "<td>" . $row['mileage'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php
}

// add page footer
include '../footer.html';
