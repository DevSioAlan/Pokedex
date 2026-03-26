<!DOCTYPE html>
<?php
    require_once 'functions.php';

    $err_msg = $_GET["err"] ?? null;
    $search = $_GET["search"] ?? null;

    $db = connect_to_db();
    $pokemons = get_pokemons($db, $search);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pokédex Moderne</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Pokédex</h1>
        </header>

        <div class="search-add-container">
            <form class="form-group" action="index.php" method="get">
                <input type="text" name="search" placeholder="Rechercher par nom ou type..." value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit">Rechercher</button>
            </form>

            <form class="form-group" action="actions/add_pokemon.php" method="post">
                <input type="text" id="name" name="name" placeholder="Ajouter un Pokémon..." required>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <?php if (isset($err_msg)): ?>
            <div class="error-msg"><?= htmlspecialchars($err_msg) ?></div>
        <?php endif; ?>

        <div class="pokemon-grid">
            <?php
                if (count($pokemons) > 0) {
                    foreach ($pokemons as $pokemon) {
                        display_pokemon_card($pokemon);
                    }
                } else {
                    echo "<div class='empty-state'>Aucun Pokémon trouvé.</div>";
                }
            ?>
        </div>
    </div>
</body>
</html>
