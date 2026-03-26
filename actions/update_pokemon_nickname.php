<?php
require '../functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"]) && isset($_POST["nickname"])) {
    $name = $_POST["name"];
	$nickname = trim($_POST["nickname"]);

	$db = connect_to_db();
	update_nickname($name, $nickname, $db);

    // Redirection vers la page du Pokémon après mise à jour
    header("Location: ../pokemon.php?name=" . $name);
    exit();
}
