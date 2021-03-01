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
    if($user->isClient()){
?>
    <h3>Current Ride Requests for <?php echo $user->firstName . $user->lastName; ?></h3>
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
            $search = "SELECT * FROM rides WHERE client='$searchuserid' AND mileage='0.0'";
            $results = mysqli_query($db, $search);
            while($row = mysqli_fetch_array($results)){
                echo "<tr>";
                echo "<td>" . $row['pickup'] . "</td>";
                echo "<td>" . $row['dropoff'] . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
    <br>
<?php } ?>
<?php if($user->isCoordinator()){ ?>

    <?php
        $select_state_accept = false;
        $select_state_finish = false;

        if (isset($_GET['assign'])){
            $Aid = $_GET['assign'];
            $select_state_accept = true;
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
        }
        else{
            $select_state_accept = false;
            $Aid = NULL;
            $Aclient = NULL;
            $Adriver = NULL;
            $Apickup = NULL;
            $Adropoff = NULL;
            $Adeparture = NULL;
            $Aarrival = NULL;
            $Amileage = NULL;
        }

        if (isset($_GET['finish'])){
            $Bid = $_GET['finish'];
            $select_state_finish = true;
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
        }
        else{
            $select_state_accept = false;
            $Bid = NULL;
            $Bclient = NULL;
            $Bdriver = NULL;
            $Bpickup = NULL;
            $Bdropoff = NULL;
            $Bdeparture = NULL;
            $Barrival = NULL;
            $Bmileage = NULL;
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
            while($row = mysqli_fetch_array($results)){
                echo "<tr>";
                echo "<td>" . $row['client'] . "</td>";  
                echo "<td>" . $row['driver'] . "</td>";
                echo "<td>" . $row['pickup'] . "</td>";
                echo "<td>" . $row['dropoff'] . "</td>";
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
        <button type='submit' class="btn btn-sm btn-primary"><a href='ride.php?assign=<?php echo NULL ?>'>Deselect Entry</a></button>
    </form>
    
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
            $search = "SELECT * FROM rides WHERE mileage='0.0' AND driver!='0'";
            $results = mysqli_query($db, $search);
            while($row = mysqli_fetch_array($results)){
                echo "<tr>";
                echo "<td>" . $row['client'] . "</td>";
                echo "<td>" . $row['driver'] . "</td>";
                echo "<td>" . $row['pickup'] . "</td>";
                echo "<td>" . $row['dropoff'] . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                if($select_state_finish == true && $Bid == $row['RIDE_ID']){
                    echo "<td><form method='post' action='ridefunction.php'>";
                    echo "<input type='number' step='0.001' name='mileageassign'>";
                    echo "<input type='hidden' name='id' value='" . $row['RIDE_ID'] . "'>";
                    echo "<button type='submit' name='mileageassignment' class='btn btn-sm btn-primary'>Accept</button>";
                    echo "</form></td>";
                }
                else{
                    echo "<td>" . $row['mileage'] . "</td>";
                }
                echo "<td><a href='ride.php?finish=" . $row['RIDE_ID'] . "'>Select</a></td>";
                if ($select_state_accept == true && $Aid == $row['RIDE_ID']){
                    echo "<td><a href='ride.php?finish=" . NULL . "'>Deselect</a></td>";
                } 
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
<?php } ?>

<?php if ($user->isCoordinator() || $user->isClient()){ ?>
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