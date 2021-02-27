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

if($user->isClient()){
    
}
elseif($user->isDriver()){
    $searchby = $user->id();
    echo "<table><thead><tr>";
    echo "<th>Client</th><th>Driver</th><th>Pick-up</th><th>Drop-off</th><th>Departure Time</th><th>Arrival Time</th><th>Mileage</th>";
    echo "</tr></thead><tbody>";
    $search = "SELECT * FROM rides WHERE driver='$searchby' AND mileage='0.0'";
    $results = mysqli_query($db, $search);
    while($row = mysqli_fetch_array($results)){
        echo "<td>" . $row['client'] . "</td>";
        echo "<td>" . $row['driver'] . "</td>";
        echo "<td>" . $row['pickup'] . "</td>";
        echo "<td>" . $row['dropoff'] . "</td>";
        echo "<td>" . $row['departure'] . "</td>";
        echo "<td>" . $row['arrival'] . "</td>";
        echo "<td>" . $row['mileage'] . "</td>";
    }
    echo "</tbody></table>";
}
elseif($user->isCoordinator()){
    echo "<table><thead><tr>";
    echo "<th>Client</th><th>Driver</th><th>Pick-up</th><th>Drop-off</th><th>Departure Time</th><th>Arrival Time</th><th>Mileage</th>";
    echo "</tr></thead><tbody>";
    $search = "SELECT * FROM rides WHERE driver='0'";
    $results = mysqli_query($db, $search);
    while($row = mysqli_fetch_array($results)){
        echo "<td>" . $row['client'] . "</td>";
        echo "<td>" . $row['driver'] . "</td>";
        echo "<td>" . $row['pickup'] . "</td>";
        echo "<td>" . $row['dropoff'] . "</td>";
        echo "<td>" . $row['departure'] . "</td>";
        echo "<td>" . $row['arrival'] . "</td>";
        echo "<td>" . $row['mileage'] . "</td>";
    }
    echo "</tbody></table>";

    /*
    echo "<table><thead><tr>";
    echo "<th>Client</th><th>Driver</th><th>Pick-up</th><th>Drop-off</th><th>Departure Time</th><th>Arrival Time</th><th>Mileage</th>";
    echo "</tr></thead><tbody>";
    $search = "SELECT * FROM rides WHERE mileage='0.0'";
    $results = mysqli_query($db, $search);
    while($row = mysqli_fetch_array($results)){
        echo "<td>" . $row['client'] . "</td>";
        echo "<td>" . $row['driver'] . "</td>";
        echo "<td>" . $row['pickup'] . "</td>";
        echo "<td>" . $row['dropoff'] . "</td>";
        echo "<td>" . $row['departure'] . "</td>";
        echo "<td>" . $row['arrival'] . "</td>";
        echo "<td>" . $row['mileage'] . "</td>";
    }
    echo "</tbody></table>";
    */
}
