<?php

/**
 * client.php
 *
 * Display the client's scheduled and past rides.
 *
 * This view/script and others like it should accomplish the tasks a client will need to do:
 *  - request a ride
 *  - maintain addresses
 *  - register as a user? (not a stated feature)
 *  - view past rides?
 *
 * @author Austin Marotti
 */


// Setup the bootstrap
require_once( '../bootstrap.php' );
$linker = new DRyft\Linker;




// Require a client user session
$user = DRyft\Session::getSession()->getUser();
$db = DRyft\Database\Connection::getConnection();
if(array_key_exists('addressEdit', $_REQUEST)){
	//displays past rides a client has taken TODO>+++++++++++++++
	header('Location: ' . $linker->urlPath() . 'address.php');
	die();
}
elseif(array_key_exists('request', $_REQUEST)){
	header('Location: ' . $linker->urlPath() . 'ride.php');
	die();
}

// add HTML head
include '../head.html';

// Output a page title and any other specific head elements
echo '		<title>Client | DRyft</title>' . PHP_EOL;

// add page header
include '../header.html';
echo '<h1>Client | DRyft</h1>' . PHP_EOL;
if ( !$user || !$user-> isClient()){

	// throw an error and exit
	echo '<h2>Access Denied</h2>';
}
elseif(array_key_exists('info', $_REQUEST)){
	//displays current client info page and allows change 


	?>
	<form method="POST">  

		<?php echo '<h3>Name: ' . $user->firstName . ' ' . $user->lastName;?>
        <input type="submit" name="nameEdit"
		class="btn btn-primary" value="Edit"/></h3>
		<?php echo '<h3>Username: ' . $user->username();?>
		<?php echo '<h3>Home Address: ' . $user->homeAddress();?>
		<input type="submit" name="addressEdit"
		class="btn btn-primary" value="Edit"/></h3>
		<h3>Password
        <input type="submit" name="passwordEdit"
                class="btn btn-primary" value="Edit"/></h3>
    </form> 

<?php

}
elseif(array_key_exists('history', $_REQUEST)){
	//displays past rides a client has taken TODO>+++++++++++++++

	$clientRides = DRyft\Ride::getRidesByClient($user->id());

	?>
    <br />
    <br />
    <h1>Ride History</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pickup Location</th>
                <th>Dropoff Location</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Miles</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientRides as $item) {
                $clientUser = $user->getUserById($item->clientID());
                $clientName = "{$clientUser->firstName} {$clientUser->lastName}";
				$temp = $item->pickupLocationID();
				$search = "SELECT line1 FROM locations WHERE LOCATION_ID='$temp'";
            	$results = mysqli_query($db, $search);
				$row = mysqli_fetch_array($results);
				$pick = $row['line1'];

				$temp = $item->dropoffLocationID();
				$search = "SELECT line1 FROM locations WHERE LOCATION_ID='$temp'";
            	$results = mysqli_query($db, $search);
				$row = mysqli_fetch_array($results);
				$drop = $row['line1'];
				
            ?>
                <tr>
                    <td><?= $item->id() ?></td>
                    <td><?= $pick ?></td>
                    <td><?= $drop ?></td>
                    <td><?= $item->departureTime() ?></td>
                    <td><?= $item->arrivalTime() ?></td>
                    <td><?= $item->mileage() ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
}
elseif(array_key_exists('nameEdit', $_REQUEST)){
	//displays form to update name and goes back to information page

	echo '<form method="POST">
		<label for="fname">First name:</label><br>
		<input type="text" id="fname" name="fname" value= '. $user->firstName .'><br>
		<label for="lname">Last name:</label><br>
		<input type="text" id="lname" name="lname" value= '. $user->lastName .'><br>
		<input type="submit"  value="Change">
	</form>';
	
}
elseif(array_key_exists('passwordEdit', $_REQUEST)){
	//displays form to change password
	echo '<form method="POST">
		<label for="fname">Current Password:</label><br>
		<input type="password" id="pass" name="pass"><br>
		<label for="lname">New Password:</label><br>
		<input type="password" id="npass" name="npass"><br>
		<label for="lname">Confirm New Password:</label><br>
		<input type="password" id="cpass" name="cpass"><br>
		<input type="submit" value="Change">
	</form>';

}

else {
	//the following updates user stuff before returning to main client page

	if(array_key_exists('fname', $_REQUEST) || //checks to see if either name was chnaged
	array_key_exists('lname', $_REQUEST)){

		if($_REQUEST['fname']!=$user->firstName || $_REQUEST['lname']!=$user->lastName){
			$user->firstName = $_REQUEST['fname'];
			$user->lastName = $_REQUEST['lname']; //updates names and saves
			$user->save();
			$message='Success';
		}else{
			$message='No Changes Were Made';
		}
		
	}
	if(array_key_exists('pass', $_REQUEST) && //checks to see all vields are filled
	array_key_exists('npass', $_REQUEST) && array_key_exists('cpass', $_REQUEST)){

		//validates old password and checks that new passwords match
		if($user->validatePassword($_REQUEST['pass']) && $_REQUEST['npass']==$_REQUEST['cpass']){
			$user->setPassword($_REQUEST['cpass']);
			$user->save();
			$message='Success';
		}
		
	}
	// Presents main client menu
	?>
	<br></br>
	
	<form method="POST"> 
        <input type="submit" name="request"
		class="w-100 btn btn-primary btn-lg" value="Request a Ride" formaction="ride.php"/>
		<br></br>
		<input type="submit" name="info"
		class="w-100 btn btn-primary btn-lg" value="Personal Information"/> 
		<br></br>
		<input type="submit" name="history"
		class="w-100 btn btn-primary btn-lg" value="Ride History"/> 
    </form> 

<?php
	echo '<h3>'.$message.'</h3>';
}



include '../footer.html';