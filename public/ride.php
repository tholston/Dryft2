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

<?php if($user->isClient()){ ?>
    <h3>Current Ride Requests for <?php echo $user->firstname() . $user->lastname(); ?></h3>
    <table>
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

    <form method='post' action>
        <input type="hidden" name="userref" value="<?php echo $searchuserid; ?>">

        <div>
            <h5>Pick-up Location Information:</h5>
            <label for="PickNickn">Address Nickname: </label>
            <input type="text" name="PickNickn"><br>
            <label for="PickLinone">Address Line 1: </label>
            <input type="text" name="PickLinone"><br>
            <label for="PickLintwo">Address Line 2: </label>
            <input type="text" name="PickLintwo"><br>
            <label for="PickCit">Address City: </label>
            <input type="text" name="PickCit"><br>
            <label for="PickStat">Address State: </label>
            <input type="text" name="PickStat"><br>
            <label for="PickZip">Address Zip Code: </label>
            <input type="text" name="PickZip"><br>
        </div>

        <div>
            <h5>Drop-off Location Information:</h5>
            <label for="DropNickn">Address Nickname: </label>
            <input type="text" name="DropNickn"><br>
            <label for="DropLinone">Address Line 1: </label>
            <input type="text" name="DropLinone"><br>
            <label for="DropLintwo">Address Line 2: </label>
            <input type="text" name="DropLintwo"><br>
            <label for="DropCit">Address City: </label>
            <input type="text" name="DropCit"><br>
            <label for="DropStat">Address State: </label>
            <input type="text" name="DropStat"><br>
            <label for="DropZip">Address Zip Code: </label>
            <input type="text" name="DropZip"><br>
        </div>

        <div>
            <h5>Pick-up Time Information:</h5>
            <label for="DeptYear">Appointment Depature - Year: </label>
            <input type="text" name="DeptYear"><br>
            <label for="DeptMonth">Appointment Depature - Month: </label>
            <input type="text" name="DeptMonth"><br>
            <label for="DeptDay">Appointment Depature - Day: </label>
            <input type="text" name="DeptDay"><br>
            <label for="DeptHour">Appointment Depature - Hour (12-hour based): </label>
            <input type="text" name="DeptHour"><br>
            <label for="DeptMinute">Appointment Depature - Minute: </label>
            <input type="text" name="DeptMinute"><br>
            <label for="DeptAMP">Appointment (AM / PM): </label>
            <input type="text" name="DeptAMP"><br>
        </div>

        <div>
            <h5>Drop-off Time Information:</h5>
            <label for="ArriHour">Appointment Expected Arrival - Hour: </label>
            <input type="text" name="ArriHour"><br>
            <label for="ArriMinute">Appointment Expected Arrival - Minute: </label>
            <input type="text" name="ArriMinute"><br>
            <label for="ArriAMP">Appointment (AM / PM): </label>
            <input type="text" name="ArriAMP"><br>
        </div>

        <button type="submit" name="ridereq" class="btn">Submit Request</button>
    </form>
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
    <table>
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
                if($select_state_accept == true && $Aid == $row['RIDE_ID']){
                    echo "<td><form action='ride.php'>";
                    echo "<input type='number' name='driveassign'>";
                    echo "<input type='hidden' name='id' value='" . $row['RIDE_ID'] . "'>";
                    echo "<button type='submit' name='driverassignment' class='btn'>Accept</button>";
                    echo "</form></td>";
                }
                else{
                    echo "<td>" . $row['driver'] . "</td>";
                }
                echo "<td>" . $row['pickup'] . "</td>";
                echo "<td>" . $row['dropoff'] . "</td>";
                echo "<td>" . $row['departure'] . "</td>";
                echo "<td>" . $row['arrival'] . "</td>";
                echo "<td>" . $row['mileage'] . "</td>";
                echo "<td><a href='ride.php?assign=" . $row['RIDE_ID'] . "'>Select</a></td>";
                if ($select_state_accept == true && $Aid == $row['RIDE_ID']){
                    echo "<td><a href='ride.php?assign=" . NULL . "'>Deselect</a></td>";
                }          
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
    
    <h3>Accepted - Unfinished</h3>
    <table>
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
                    echo "<td><form action='ride.php'>";
                    echo "<input type='number' step='0.001' name='mileageassign'>";
                    echo "<input type='hidden' name='id' value='" . $row['RIDE_ID'] . "'>";
                    echo "<button type='submit' name='mileageassignment' class='btn'>Accept</button>";
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

<?php
    if (isset($_POST['driverassignment'])){
        $PID = $_POST['id'];
        $PDriver = $_POST['driveassign'];

        $query = "UPDATE rides SET driver='$PDriver' WHERE RIDE_ID='$PID'";
        mysqli_query($db, $query);
        header('Location: /ride.php');
        exit();
    }

    if (isset($_POST['mileageassignment'])){
        $PID = $_POST['id'];
        $PMileage = $_POST['mileageassign'];

        $query = "UPDATE rides SET mileage='$PMileage' WHERE RIDE_ID='$PID'";
        mysqli_query($db, $query);
        header('Location: /ride.php');
        exit();
    }
?>
