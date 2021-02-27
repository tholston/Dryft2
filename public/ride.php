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
$db = \Database\Connection::getConnection();
$user = DRyft\Session::getSession()->getUser();
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
        <label for="PickNickn">Location Nickname: </label>
        <input type="text" name="PickNickn"><br>
        <input type="text" name="PickLinone"><br>
        <input type="text" name="PickLintwo"><br>
        <input type="text" name="PickCit"><br>
        <input type="text" name="PickStat"><br>
        <input type="text" name="PickZip"><br>

        <input type="text" name="DropNickn"><br>
        <input type="text" name="DropLinone"><br>
        <input type="text" name="DropLintwo"><br>
        <input type="text" name="DropCit"><br>
        <input type="text" name="DropStat"><br>
        <input type="text" name="DropZip"><br>

        <input type="text" name="DeptYear"><br>
        <input type="text" name="DeptMonth"><br>
        <input type="text" name="DeptDay"><br>
        <input type="text" name="DeptHour"><br>
        <input type="text" name="DeptMinute"><br>

        <input type="text" name="ArriHour"><br>
        <input type="text" name="ArriMinute"><br>

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
