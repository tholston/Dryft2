<?php

/**
 * address.php
 *
 * CRUD interfaces for address entries within the system
 *
 * @author Noah South
 */

    namespace DRyft;
    require_once("../bootstrap.php");
    $db = Database\Connection::getConnection();
    $edit_state = false;

    if (isset($_GET['edit'])){
        $id = $_GET['edit'];
        if($id != NULL){
            $edit_state = true;
            $find = "SELECT * FROM locations WHERE LOCATION_ID='$id'";
            $rec = mysqli_query($db, $find);
            $record = mysqli_fetch_array($rec);
            $id = $record['LOCATION_ID'];
            $Lati = $record['latitude'];
            $Longi = $record['longitude'];
            $Nickn = $record['nickname'];
            $Linone = $record['line1'];
            $Lintwo = $record['line2'];
            $Cit = $record['city'];
            $Stat = $record['state'];
            $Zipc = $record['zip'];
        }
        else{
            $edit_state = false;
            $id = NULL;
            $Lati = NULL;
            $Longi = NULL;
            $Nickn = NULL;
            $Linone = NULL;
            $Lintwo = NULL;
            $Cit = NULL;
            $Stat = NULL;
            $Zipc = NULL;
        }
    }
?>

<!-- This table will print out the information for the coordinator to view and alter any locations stored within the database. -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>latitude</th>
            <th>longitude</th>
            <th>nickname</th>
            <th>line 1</th>
            <th>line 2</th>
            <th>City</th>
            <th>State</th>
            <th>Zip Code</th>
            <th colspan="2">CRUD ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $query = "SELECT * FROM locations;";
            $results = mysqli_query($db, $query);
            while ($row = mysqli_fetch_array($results)){
        ?>
            <tr>
                <td><?php echo $row['LOCATION_ID'];?></td>
                <td><?php echo $row['latitude'];?></td>
                <td><?php echo $row['longitude'];?></td>
                <td><?php echo $row['nickname'];?></td>
                <td><?php echo $row['line1'];?></td>
                <td><?php echo $row['line2'];?></td>
                <td><?php echo $row['city'];?></td>
                <td><?php echo $row['state'];?></td>
                <td><?php echo $row['zip'];?></td>
                <td><a href="address.php?edit=<?php echo $row['LOCATION_ID'] ?>">EDIT</a></td>
                <td><a href="#">DELETE</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div>
    <h3>Create Entry</h3>
    <form method="post" action="addressexternal.php">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <label for="Lati">Latitude: </label>
        <input type="text" id="Lati" name="Lati" value="<?php echo $Lati; ?>" required><br>
        <label for="Longi">Longitude: </label>
        <input type="text" id="Longi" name="Longi" value="<?php echo $Longi; ?>" required><br>
        <label for="Nickn">Nickname: </label>
        <input type="text" id="Nickn" name="Nickn" value="<?php echo $Nickn; ?>" required><br>
        <label for="Linone">Line 1: </label>
        <input type="text" id="Linone" name="Linone" value="<?php echo $Linone; ?>" required><br>
        <label for="Lintwo">Line 2: </label>
        <input type="text" id="Lintwo" name="Lintwo" value="<?php echo $Lintwo; ?>" required><br>
        <label for="Cit">City: </label>
        <input type="text" id="Cit" name="Cit" value="<?php echo $Cit; ?>" required><br>
        <label for="Stat">State: </label>
        <input type="text" id="Stat" name="Stat" value="<?php echo $Stat; ?>" required><br>
        <label for="Zipc">Zipcode: </label>
        <input type="text" id="Zipc" name="Zipc" value="<?php echo $Zipc; ?>" required><br>
        <?php if ($edit_state == false): ?>
            <button type="submit" name="addLoc" class="btn">Add Entry</button>
        <?php else: ?>
            <button type="submit" name="alter" class="btn">Update Entry</button>
            <button name="unselect" class="btn"><a href="address.php?edit=<?php echo NULL ?>">Unselect</a></button>
        <?php endif ?>
    </form>
</div>

<?php
    
    /* planned to move functions into an externaladdress.php file so they may be included without printed earlier full table */

    //function for external access by other sections of code.
    //Prints out the information of a location based on the passed location_id.
    function externalaccessPrint($id){
        $query = "SELECT * FROM locations WHERE LOCATION_ID='$id'";
        $results = mysqli_query($db, $query);
        echo "<table><thead><tr>";
        echo "<th>latitude</th><th>longitude</th><th>nickname</th><th>line 1</th><th>line 2</th><th>City</th><th>State</th><th>Zip Code</th><th>Edit</th>";
        echo "</tr></thead><tbody>";
        while ($row = mysqli_fetch_array($results)){
            echo "<tr>";
            echo "<td>" . $row['latitude'] . "</td>";
            echo "<td>" . $row['longitude'] . "</td>";
            echo "<td>" . $row['nickname'] . "</td>";
            echo "<td>" . $row['line1'] . "</td>";
            echo "<td>" . $row['line2'] . "</td>";
            echo "<td>" . $row['city'] . "</td>";
            echo "<td>" . $row['state'] . "</td>";
            echo "<td>" . $row['zip'] . "</td>";
            echo "<td> EDIT </td>"; //will be used to call externalaccessUpdate, tbd how
            echo "</tr>";
        }
    }

    function externalaccessUpdate($id){
        //will update file information outside of this address.php file/
    }
?>