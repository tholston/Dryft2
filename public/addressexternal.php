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

    if (isset($_GET['delete'])){
        $DeleteID = $_GET['delete'];
        $safetycheck = "SELECT * FROM users WHERE home_address='$DeleteID' OR mailing_address='$DeleteID'";
        $safetyresult = mysqli_query($db, $safetycheck);
        if(mysqli_num_rows($safetyresult) > 0){
            header('Location: /address.php?error="ThisIsAUsersAddress_CannotModify');
            exit();
        }

        $safetycheck = "SELECT * FROM rides WHERE pickup='$DeleteID' OR dropoff='$DeleteID'";
        $safetyresult = mysqli_query($db, $safetycheck);
        while ($row = mysqli_fetch_array($safetyresult)){
            if($row['pickup'] == $DeleteID){
                $safetyupdate = "UPDATE rides SET pickup='0' WHERE RIDE_ID='$DeleteID'";
                mysqli_query($db, $safetyupdate);
                header('Location: /address.php');
                exit();
            }
            if($row['dropoff'] == $DeleteID){
                $safetyupdate = "UPDATE rides SET dropoff='0' WHERE RIDE_ID='$DeleteID'";
                mysqli_query($db, $safetyupdate);
                header('Location: /address.php');
                exit();
            }
        }

        $delquery = "DELETE FROM locations WHERE LOCATION_ID='$DeleteID'";
        mysqli_query($db, $delquery);
        header('Location: /address.php');
        exit();
    }
?>