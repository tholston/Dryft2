<?php

/**
 * driver.php
 *
 * Handle tasks for drivers
 *  - establish availability status
 *  - list availble/accepted rides
 *  - list of past rides
 *  - accept/launch a ride
 *
 * One option to implement the model for this would be to subclass User and expand for the elements
 * specific to driver objects (rate and availability).
 *
 * @author Clay Bellou
 */

namespace DRyft;

require_once('../bootstrap.php');
//require_once('../DRyft/User.php');

$user = Session::getSession()->getUser();
include '../head.html';
include '../header.html';

echo '<h1>This page does in fact exist</h1>';
if (!$user || !($user->isCoordinator()) || !($user->isDriver())) {
    // throw an error and exit
    echo '<h1>Access Denied</h1>';
    //TODO some redirect to somewhere here.
} elseif ($user->isCoordinator()) {
    // Present a list of the users in the system
    echo '<h1>Coordinator Page.</h1>';

    $db = Database\Connection::getConnection();
    //$result = $db->query($select)
    echo "<div class='row'>";
    echo "<center>";
    echo "<h2>All Drivers</h2>";
    echo "<table>";
    echo "  <thead>";
    echo "    <tr><th style='text-align:center'>Name</th><th style='text-align:center'>Ingredients</th><th></th><th></th></tr>";
    echo "  </thead>";
    echo "  <tbody>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        //The } is in the next php tag area
?>

        <td style='text-align:center'><a href="readRecipe.php?id=<?php echo urlencode($row["RecipeID"]); ?>"><?php echo $row['Recipe_Name'] ?></td>
        <?php
        echo "<td style='text-align:center'>" . $row['GROUP_CONCAT(Ingredient_Name)'] . "</td>";
        ?>



        <!-- Create an Edit and Delete link - USE HTML
			//Edit should direct to edit.php, sending PersonID in URL
			//Delete should direct to delete.php, sending PersonID in URL - include onclick to confirm delete -->

        <td><a href="editRecipe.php?id=<?php echo urlencode($row["RecipeID"]); ?>">Edit</a></td>
        <td><a href="deleteRecipe.php?id=<?php echo urlencode($row["RecipeID"]); ?>" onclick="return confirm('Are you sure you want to delete?'); ">Delete</a></td>

<?php


        echo "</tr>";
    }
    echo "  </tbody>";
    echo "</table>";
    echo "<br /><br /><a href='createRecipe.php'>Add a Recipe</a> | <a href='createIngredient.php'>Add an Ingredient</a>";
    echo '<p/>';
    echo '<p/>';
    echo "<a href='readAllRecipes2.php'>See all recipes, including those without ingredients</a>";
    echo '<p/>';
    echo "<a href='readAllIngredients.php'>Go to overall Ingredient List</a>";
    echo "</center>";
    echo "</div>";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// BEGIN DRIVER VIEW ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
elseif ($user->isDriver()) {
    echo '<h1>Driver Page.</h1>';
}

$test = $user->isCoordinator();
echo "{$test}";

include '../testing_links.html';



// add page footer
include '../footer.html';
