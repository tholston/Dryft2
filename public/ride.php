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

    <form method='post' action='ride.php'>
        <input type="hidden" name="userref" value="<?php echo $searchuserid; ?>">

        <div>
            <h5>Pick-up Location Information:</h5>
            <label for="PickNickn">Address Nickname: </label>
            <input type="text" name="PickNickn" maxlength='60'><br>
            <label for="PickLinone">Address Line 1: </label>
            <input type="text" name="PickLinone" maxlength='60'><br>
            <label for="PickLintwo">Address Line 2: </label>
            <input type="text" name="PickLintwo" maxlength='60'><br>
            <label for="PickCit">Address City: </label>
            <input type="text" name="PickCit" maxlength='60'><br>
            <label for="PickStat">Address State: </label>
            <input type="text" name="PickStat" maxlength='2'><br>
            <label for="PickZip">Address Zip Code: </label>
            <input type="text" name="PickZip" maxlength='10'><br>
        </div>

        <div>
            <h5>Drop-off Location Information:</h5>
            <label for="DropNickn">Address Nickname: </label>
            <input type="text" name="DropNickn" maxlength='60'><br>
            <label for="DropLinone">Address Line 1: </label>
            <input type="text" name="DropLinone" maxlength='60'><br>
            <label for="DropLintwo">Address Line 2: </label>
            <input type="text" name="DropLintwo" maxlength='60'><br>
            <label for="DropCit">Address City: </label>
            <input type="text" name="DropCit" maxlength='60'><br>
            <label for="DropStat">Address State: </label>
            <input type="text" name="DropStat" maxlength='2'><br>
            <label for="DropZip">Address Zip Code: </label>
            <input type="text" name="DropZip" maxlength='10'><br>
        </div>

        <div>
            <h5>Pick-up Time Information:</h5>
            <label for="DeptYear">Appointment Depature - Year: </label>
            <input type="number" name="DeptYear" maxlength='4'><br>
            <label for="DeptMonth">Appointment Depature - Month (January = 1 & December = 12): </label>
            <input type="number" name="DeptMonth" maxlength='2'><br>
            <label for="DeptDay">Appointment Depature - Day: </label>
            <input type="number" name="DeptDay" maxlength='2'><br>
            <label for="DeptHour">Appointment Depature - Hour (12-hour based): </label>
            <input type="number" name="DeptHour" maxlength='2'><br>
            <label for="DeptMinute">Appointment Depature - Minute: </label>
            <input type="number" name="DeptMinute" maxlength='2'><br>
            <label for="DeptAMP">Appointment (AM / PM): </label>
            <input type="text" name="DeptAMP" maxlength='2'><br>
        </div>

        <div>
            <h5>Drop-off Time Information:</h5>
            <label for="ArriHour">Appointment Expected Arrival - Hour (12-hour based): </label>
            <input type="text" name="ArriHour" maxlength='2'><br>
            <label for="ArriMinute">Appointment Expected Arrival - Minute: </label>
            <input type="text" name="ArriMinute" maxlength='2'><br>
            <label for="ArriAMP">Appointment (AM / PM): </label>
            <input type="text" name="ArriAMP" maxlength='2'><br>
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

    /* User drive request form handler
        Takes in the form information, validates whether or not locations already exist, creating them if not, and compiling all the information for a formal ride request.
        This information will be pushed into the database for the coordinator to then handle. By this, assigning a driver and finishing the ride acceptance.
    */
    if (isset($_POST['ridereq'])){
        $CID = $_POST['userref'];
        //Pick-up location inforamtion for location validation / creation.
        $PickNickn = $_POST['PickNickn'];
        $PickLinone = $_POST['PickLinone'];
        $PickLintwo = $_POST['PickLintwo'];
        $PickCit = $_POST['PickCit'];
        $PickStat = $_POST['PickStat'];
        $PickZip = $_POST['PickZip'];

        //Drop-off location information for location validation / creation.
        $DropNickn = $_POST['DropNickn'];
        $DropLinone = $_POST['DropLinone'];
        $DropLintwo = $_POST['DropLintwo'];
        $DropCit = $_POST['DropCit'];
        $DropStat = $_POST['DropStat'];
        $DropZip = $_POST['DropZip'];

        //Departure time information for drivers and clients
        $DeptYear = $_POST['DeptYear'];
        $DeptMonth = $_POST['DeptMonth'];
        $DeptDay = $_POST['DeptDay'];
        $DeptHour = $_POST['DeptHour'];
        $DeptMinute = $_POST['DeptMinute'];
        $DeptAMP = $_POST['DeptAMP'];

        //Expected arrival time information for drivers and clients
        $ArriHour = $_POST['ArriHour'];
        $ArriMinute = $_POST['ArriMinute'];
        $ArriAMP = $_POST['ArriAMP'];

        /*
        Validating user time-based input to ensure entries are capable of being used to create a datetime object. Testing includes but is not limited to: it is not a date prior to current date information, invalid dates were not input, correct AM/PM differntiation, etc.
        */
        if($DeptYear < date('Y')){
            header('Location: /ride.php?Error="Invalid Year Entry"');
            exit();
        }
        elseif($DeptMonth < 0 || $DeptMonth > 12 || $DeptMonth < date('m')){
            header('Location: /ride.php?Error="Invalid Month Number Entry"');
            exit();
        }
        elseif($DeptDay > 31 || $DeptDay < 0 || ($DeptDay < date('d') && $DeptMonth == date('m'))){
            header('Location: /ride.php?Error="Invalid Day Entry"');
            exit();
        }
        elseif($DeptHour > 12 || $DeptHour < 1 || $ArriHour > 12 || $ArriHour < 1){
            header('Location: /ride.php?Error="Invalid Out of Bounds Hour Entry"');
            exit();
        }
        elseif($DeptMinute > 59 || $DeptMinute < 0 || $ArriMinute > 59 || $ArriMinute < 0){
            header('Location: /ride.php?Error="Invalid Out of Bounds Minute Entry"');
            exit();
        }
        elseif($DeptAMP != 'AM' || $DeptAmp != 'am' || $DeptAMP != 'PM' || $DeptAmp != 'pm' || $ArriAMP != 'AM' || $ArriAMP != 'am' || $ArriAMP != 'PM' || $ArriAMP != 'pm'){
            header('Location: /ride.php?Error="Invalid AM / PM Differentiator Entry"');
            exit();
        }

        /*
        Correcting user input for out of bounds cases.
        Military time is also difficult for some to grasp if they are used to constant exposure to the AM / PM 12 hour system.
        As such, the 12 hour input will be corrected for the Datetime format of the database.
        */
        if($DeptHour == 12 && ($DeptAMP == "AM" || $DeptAMP == "am")){
            $DeptHour = 0;
        }
        elseif(($DeptAMP == "PM" || $DeptAMP == "pm") && $DeptHour != 12){
            $DeptHour = $DeptHour + 12;
        }
        elseif($ArriHour == 12 && ($ArriHour == "AM" || $ArriHour == "am")){
            $ArriHour = 0;
        }
        elseif(($ArriHour == "PM" || $ArriHour == "pm") && $ArriHour != 12){
            $ArriHour = $ArriHour + 12;
        }

        if($ArriHour < $DeptHour){
            header('Location: /ride.php?Error="Invalid Arrival Hour Time Entry"');
            exit();
        }
        elseif($ArriHour == $DeptHour && $ArriMinute < $DeptMinute){
            header('Location: /ride.php?Error="Invalid Arrival Minute Time Entry"');
            exit();
        }

        //Creation of the datetime objects for table insertion:
        $DeptDT = $DeptYear . '-' . $DeptMonth . '-' . $DeptDay . ' ' . $DeptHour . ':' . $DeptMinute . ':00';
        $ArriDT = $DeptYear . '-' . $DeptMonth . '-' . $DeptDay . ' ' . $ArriHour . ':' . $ArriMinute . ':00';

        //searches database for a location matching the input given by user, based on pick-up input.
        $existinglocation = "SELECT * FROM locations WHERE nickname='$PickNickn' AND line1='$PickLinone' AND line2='$PickLintwo' AND city='$PickCit' AND state='$PickStat' AND zip='$PickZip'";
        $checkresults = mysqli_query($db, $existinglocation);
        //if no search results are found, the location is then created and the search is repeated. the search should now find this location.
        if(mysqli_num_rows($checkresults) == 0){
            $query = "INSERT INTO locations(LOCATION_ID, latitude, longitude, nickname, line1, line2, city, state, zip) VALUES(DEFAULT, '0.0', '0.0', '$PickNickn', '$PickLinone', '$PickLintwo', '$PickCit', '$PickStat', '$PickZipc')";
            mysqli_query($db, $query);
            $existinglocation = "SELECT * FROM locations WHERE nickname='$PickNickn' AND line1='$PickLinone' AND line2='$PickLintwo' AND city='$PickCit' AND state='$PickStat' AND zip='$PickZip'";
            $checkresults = mysqli_query($db, $existinglocation);
        }
        //Stores the found location for later use when creating the ride entry.
        $locationP = mysqli_fetch_array($checkresults);
        $PLID = $locationP['LOCATION_ID'];

        //searches database for a location matching the input given by user, based on drop-off input.
        $existinglocation2 = "SELECT * FROM locations WHERE nickname='$DropNickn' AND line1='$DropLinone' AND line2='$DropLintwo' AND city='$DropCit' AND state='$DropStat' AND zip='$DropZip'";
        $checkresults2 = mysqli_query($db, $existinglocation2);
        //if no search results are found, the location is then created and the search is repeated. the search should now find this location.
        if(mysqli_num_rows($checkresults2) == 0){
            $query2 = "INSERT INTO locations(LOCATION_ID, latitude, longitude, nickname, line1, line2, city, state, zip) VALUES(DEFAULT, '0.0', '0.0', '$DropNickn', '$DropLinone', '$DropLintwo', '$DropCit', '$DropStat', '$DropZipc')";
            mysqli_query($db, $query2);
            $existinglocation2 = "SELECT * FROM locations WHERE nickname='$DropNickn' AND line1='$DropLinone' AND line2='$DropLintwo' AND city='$DropCit' AND state='$DropStat' AND zip='$DropZip'";
            $checkresults2 = mysqli_query($db, $existinglocation2);
        }
        //Stores the found location for later use when creating the ride entry.
        $locationD = mysqli_fetch_array($checkresults2);
        $DLID = $locationD['LOCATION_ID'];

        $createrequest = "INSERT INTO rides(RIDE_ID, client, driver, pickup, dropoff, departure, arrival, mileage) VALUES(DEFAULT, '$CID', '0', '$PLID', '$DLID', '$DeptDT', '$ArriDT', '0.0')";
        mysqli_query($db, $createrequest);
        header('Location: /ride.php');
        exit();
    }   
?>
