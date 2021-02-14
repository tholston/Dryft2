<h1>Users</h1>
<?php
$db = new mysqli( 'db', 'dryft', 'ADeveloperPassword', 'dryft' );
if ( ( $results = $db->query( 'SELECT * FROM `users`;' ) ) !== false ) {
?>
<ul>
<?php
	while ( ( $user = $results->fetch_object() ) !== null ) {
?>
	<li><?php echo $user->USER_ID, ': ', $user->username; ?></li>
<?php
	}
?>
</ul>
<?php
}
else {
?>
<p>Query failure: <?php echo $db->error; ?></p>
<?php
}