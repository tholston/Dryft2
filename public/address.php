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
    $edit_state = false;

    if (isset($_GET['edit'])){
        $id = $_GET['edit'];

        $rec = mysqli_query($db, "SELECT * FROM locations WHERE id=$id");
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
                <td><a href="address.php?edit=<?php echo $row['LOCATION_ID'] ?>">EDIT</a></td>
                <td><a href="#">DELETE</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div>
    <h3>Create Entry</h3>
    <form method="post" action>
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <label for="Lati">Latitude: </label>
        <input type="text" id="Lati" name="Lati" value="<?php echo $Lati; ?>"><br>
        <label for="Longi">Longitude: </label>
        <input type="text" id="Longi" name="Longi" value="<?php echo $Longi; ?>"><br>
        <label for="Nickn">Nickname: </label>
        <input type="text" id="Nickn" name="Nickn" value="<?php echo $Nickn; ?>"><br>
        <label for="Linone">Line 1: </label>
        <input type="text" id="Linone" name="Linone" value="<?php echo $Linone; ?>"><br>
        <label for="Lintwo">Line 2: </label>
        <input type="text" id="Lintwo" name="Lintwo" value="<?php echo $Lintwo; ?>"><br>
        <label for="Cit">City: </label>
        <input type="text" id="Cit" name="Cit" value="<?php echo $Cit; ?>"><br>
        <label for="Stat">State: </label>
        <input type="text" id="Stat" name="Stat" value="<?php echo $Stat; ?>"><br>
        <label for="Zipc">Zipcode: </label>
        <input type="text" id="Zipc" name="Zipc" value="<?php echo $Zipc; ?>"><br>
        <?php if ($edit_state == false): ?>
            <input type="submit" name="addLoc" class="btn">
        <?php else: ?>
            <input type="submit" name="alter" class="btn">
        <?php endif ?>
    </form>
</div>

<?php
    if (isset($_POST['addLoc'])){
        $PLati = mysqli_real_escape_string($_POST['Lati']);
        $PLongi = mysqli_real_escape_string($_POST['Longi']);
        $PNick = mysqli_real_escape_string($_POST['Nickn']);
        $PLinone = mysqli_real_escape_string($_POST['Linone']);
        $PLintwo = mysqli_real_escape_string($_POST['Lintwo']);
        $PCit = mysqli_real_escape_string($_POST['Cit']);
        $PStat = mysqli_real_escape_string($_POST['Stat']);
        $PZipc = mysqli_real_escape_string($_POST['Zipc']);

        $query = "INSERT INTO locations(latitude, longitude, nickname, line1, line2, city, state, zip) VALUES($PLati, $PLongi, $PNick, $PLinone, $PLintwo, $PCit, $PStat, $PZipc)";
        mysqli_query($db, $query);
    }

    if (isset($_POST['alter'])){
        $PID = mysqli_real_escape_string($_POST['id']);
        $PLati = mysqli_real_escape_string($_POST['Lati']);
        $PLongi = mysqli_real_escape_string($_POST['Longi']);
        $PNick = mysqli_real_escape_string($_POST['Nickn']);
        $PLinone = mysqli_real_escape_string($_POST['Linone']);
        $PLintwo = mysqli_real_escape_string($_POST['Lintwo']);
        $PCit = mysqli_real_escape_string($_POST['Cit']);
        $PStat = mysqli_real_escape_string($_POST['Stat']);
        $PZipc = mysqli_real_escape_string($_POST['Zipc']);

        $query = "UPDATE locations SET latitude='$PLati', longitude='$PLongi', nickname='$PNick', line1='$PLinone', line2='$PLintwo', city='$PCit', state='$PStat', zip='$PZipc' WHERE LOCATION_ID='$PID'";
        mysqli_query($db, $query);
    }
?>