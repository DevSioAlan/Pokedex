<?php
require '../functions.php';

$db = connect_to_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["name"])) {
		$name = $_POST["name"];
		delete_pokemon($name, $db);
	}
}

header("Location: ../index.php");

exit();
