<?php
    namespace DRyft;
    require_once("../bootstrap.php");
    $db = Database\Connection::getConnection();

    if (isset($_POST['addLoc'])){
        $PLati = $_POST['Lati'];
        $PLongi = $_POST['Longi'];
        $PNick = $_POST['Nickn'];
        $PLinone = $_POST['Linone'];
        $PLintwo = $_POST['Lintwo'];
        $PCit = $_POST['Cit'];
        $PStat = $_POST['Stat'];
        $PZipc = $_POST['Zipc'];

        $query = "INSERT INTO locations(LOCATION_ID, latitude, longitude, nickname, line1, line2, city, state, zip) VALUES(DEFAULT, '$PLati', '$PLongi', '$PNick', '$PLinone', '$PLintwo', '$PCit', '$PStat', '$PZipc')";
        mysqli_query($db, $query);
        header('Location: /address.php');
        exit();
    }

    if (isset($_POST['alter'])){
        $PID = $_POST['id'];
        $PLati = $_POST['Lati'];
        $PLongi = $_POST['Longi'];
        $PNick = $_POST['Nickn'];
        $PLinone = $_POST['Linone'];
        $PLintwo = $_POST['Lintwo'];
        $PCit = $_POST['Cit'];
        $PStat = $_POST['Stat'];
        $PZipc = $_POST['Zipc'];

        $query = "UPDATE locations SET latitude='$PLati', longitude='$PLongi', nickname='$PNick', line1='$PLinone', line2='$PLintwo', city='$PCit', state='$PStat', zip='$PZipc' WHERE LOCATION_ID='$PID'";
        mysqli_query($db, $query);
        header('Location: /address.php');
        exit();
    }
?>