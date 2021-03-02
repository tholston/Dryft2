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

    <form method='post' action>{
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
    }
<?php } ?>
<?php if($user->isCoordinator()){ ?>
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
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
    <br>
    
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
            </tr>
        </thead>
        <tbody>
        <?php
            $search = "SELECT * FROM rides WHERE mileage='0.0'";
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
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
<?php } ?>
