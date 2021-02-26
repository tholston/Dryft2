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

		<?php echo '<h2>Name: ' . $user->firstName . ' ' . $user->lastName;?>
        <input type="submit" name="nameEdit"
                class="button" value="Edit"/></h2>
		<?php echo '<h2>Username: ' . $user->username();?>
		<?php echo '<h2>Home Address: ' . $user->homeAddress();?>
		<input type="submit" name="addressEdit"
                class="button" value="Edit"/></h2>
		<h2>Password
        <input type="submit" name="passwordEdit"
                class="button" value="Edit"/></h2>
    </form> 

<?php

}
elseif(array_key_exists('history', $_REQUEST)){
	//displays past rides a client has taken TODO>+++++++++++++++
	echo '
	<table style="width:100%" >
	<tr>
		<th>Pickup</th>
		<th>Dropoff</th> 
		<th>Departure</th>
		<th>Arrival</th>
		<th>Driver</th>
	</tr>
	<tr>
		<td>Jill</td>
		<td>Smith</td> 
		<td>50</td>
	</tr>
	<tr>
		<td>Eve</td>
		<td>Jackson</td> 
		<td>94</td>
	</tr>
</table>';
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
elseif(array_key_exists('addressEdit', $_REQUEST)){
	//displays past rides a client has taken TODO>+++++++++++++++
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
elseif(array_key_exists('request', $_REQUEST)){
	header('Location: ' . $linker->urlPath() . 'ride.php');
	die();
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
	<form method="POST"> 
        <input type="submit" name="request"
                class="button" value="Request a Ride" formaction="ride.php"/> 
		<input type="submit" name="info"
                class="button" value="Personal Information"/> 
		<input type="submit" name="history"
                class="button" value="Ride History"/> 
    </form> 

<?php
	echo '<h3>'.$message.'</h3>';
}





include '../testing_links.html';
include '../footer.html';