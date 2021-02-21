<?php

/**
 * address.php
 *
 * CRUD interfaces for address entries within the system
 *
 * @author Noah South
 */
    include_once "../DRyft/Database/Connection.php";
    $db = getConnection();
?>

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
                <td><a href="#">EDIT</a></td>
                <td><a href="#">DELETE</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div>
    <h3>Create Entry</h3>
    <form method="post" action>
        <label for="Lati">Latitude: </label>
        <input type="text" id="Lati" name="Lati"><br>
        <label for="Longi">Longitude: </label>
        <input type="text" id="Longi" name="Longi"><br>
        <label for="Nickn">Nickname: </label>
        <input type="text" id="Nickn" name="Nickn"><br>
        <label for="Linone">Line 1: </label>
        <input type="text" id="Linone" name="Linone"><br>
        <label for="Lintwo">Line 2: </label>
        <input type="text" id="Lintwo" name="Lintwo"><br>
        <label for="Cit">City: </label>
        <input type="text" id="Cit" name="Cit"><br>
        <label for="Stat">State: </label>
        <input type="text" id="Stat" name="Stat"><br>
        <label for="Zipc">Zipcode: </label>
        <input type="text" id="Zipc" name="Zipc"><br>
        <input type="submit">
    </form>
</div>

<?php
    
?>