<?php
    namespace DRyft;
    require_once("../bootstrap.php");
    $db = Database\Connection::getConnection();

    /*
        Updates the database ride with a user thus accepting the ride
        Navigates back to the ride.php page after completion.
    */
    if (isset($_POST['driverassignment'])){
        $PID = $_POST['id'];
        $PDriver = $_POST['driveassign'];

        $query = "UPDATE rides SET driver='$PDriver' WHERE RIDE_ID='$PID'";
        mysqli_query($db, $query);
        header('Location: /ride.php');
        exit();
    }

    /*
        Updates the database ride with a mileage completing the ride
        Navigates back to the ride.php page after completion.

    */
    if (isset($_POST['mileageassignment'])){
        //PID for pass ID
        $PID = $_POST['id'];
        $PMileage = $_POST['mileageassign'];

        $query = "UPDATE rides SET mileage='$PMileage' WHERE RIDE_ID='$PID'";
        mysqli_query($db, $query);
        Payment::addFinishedRideToPayment($PID);
        header('Location: /ride.php');
        exit();
    }

    /*
        Queries the database by receiving a $_GET id from ride.php which will then use it to delete the entry.
        Navigates back to the ride.php page after completion.
    */
    if (isset($_GET['rejection'])){
        $RejectID = $_GET['rejection'];
        $delquery = "DELETE FROM rides WHERE RIDE_ID='$RejectID'";
        mysqli_query($db, $delquery);
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
            header('Location: /ride.php?Error="Invalid_Year_Entry"');
            exit();
        }
        elseif($DeptMonth < 0 || $DeptMonth > 12 || ($DeptMonth < date('m') && $DeptYear <= date('Y'))){
            header('Location: /ride.php?Error="Invalid_Month_Number_Entry"');
            exit();
        }
        elseif($DeptDay > 31 || $DeptDay < 0 || ($DeptDay < date('d') && $DeptMonth == date('m'))){
            header('Location: /ride.php?Error="Invalid_Day_Entry"');
            exit();
        }
        elseif($DeptHour > 12 || $DeptHour < 1 || $ArriHour > 12 || $ArriHour < 1){
            header('Location: /ride.php?Error="Invalid_Out_of_Bounds_Hour_Entry"');
            exit();
        }
        elseif($DeptMinute > 59 || $DeptMinute < 0 || $ArriMinute > 59 || $ArriMinute < 0){
            header('Location: /ride.php?Error="Invalid_Out_of_Bounds_Minute_Entry"');
            exit();
        }
        elseif($DeptAMP != 'AM' && $DeptAmp != 'am' && $DeptAMP != 'PM' && $DeptAmp != 'pm' && $ArriAMP != 'AM' && $ArriAMP != 'am' && $ArriAMP != 'PM' && $ArriAMP != 'pm'){
            header('Location: /ride.php?Error="Invalid_AM_PM_Differentiator_Entry"');
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
        if($ArriHour == 12 && ($ArriHour == "AM" || $ArriHour == "am")){
            $ArriHour = 0;
        }
        elseif(($ArriAMP == "PM" || $ArriAMP == "pm") && $ArriHour != 12){
            $ArriHour = $ArriHour + 12;
        }

        if($ArriHour < $DeptHour){
            header('Location: /ride.php?Error="Invalid_Arrival_Hour_Time_Entry"');
            exit();
        }
        elseif($ArriHour == $DeptHour && $ArriMinute < $DeptMinute){
            header('Location: /ride.php?Error="Invalid_Arrival_Minute_Time_Entry"');
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
            $query = "INSERT INTO locations(LOCATION_ID, latitude, longitude, nickname, line1, line2, city, state, zip) VALUES(DEFAULT, '0.0', '0.0', '$PickNickn', '$PickLinone', '$PickLintwo', '$PickCit', '$PickStat', '$PickZip')";
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
            $query2 = "INSERT INTO locations(LOCATION_ID, latitude, longitude, nickname, line1, line2, city, state, zip) VALUES(DEFAULT, '0.0', '0.0', '$DropNickn', '$DropLinone', '$DropLintwo', '$DropCit', '$DropStat', '$DropZip')";
            mysqli_query($db, $query2);
            $existinglocation2 = "SELECT * FROM locations WHERE nickname='$DropNickn' AND line1='$DropLinone' AND line2='$DropLintwo' AND city='$DropCit' AND state='$DropStat' AND zip='$DropZip'";
            $checkresults2 = mysqli_query($db, $existinglocation2);
        }
        //Stores the found location for later use when creating the ride entry.
        $locationD = mysqli_fetch_array($checkresults2);
        $DLID = $locationD['LOCATION_ID'];

        $createrequest = "INSERT INTO rides(RIDE_ID, client, driver, pickup, dropoff, departure, arrival, mileage) VALUES(DEFAULT, '$CID', '0', '$PLID', '$DLID', '$DeptDT', '$ArriDT', '0.0')";
        $finaltest = mysqli_query($db, $createrequest);
        if($finaltest === false){
            header('Location: /ride.php?Error="' . $db->error . '"');
            exit();
        }

        header('Location: /ride.php');
        exit();
    }
?>