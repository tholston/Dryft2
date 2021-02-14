<?php
$db = new mysqli( 'db', 'dryft', 'ADeveloperPassword', 'dryft' );

$results = $db->query( 'SELECT * FROM `users`;' );
?>
<h1>Users</h1>
<ul>
<?php
while ( $user = $results->fetch_object() ) {
?>
    <li><?php echo $user->USER_ID, ': ', $user->username; ?></li>
<?php
}
?>
</ul>