<?php
require_once 'config.php';
require_once 'api.php';

function reset_db() {
    $db = connect_to_db();
    $sql = file_get_contents("pokemons.sql");

    // Adjusting SQL for SQLite
    if (DB_TYPE === 'sqlite') {
        $sql = str_replace("INT UNSIGNED PRIMARY KEY", "INTEGER PRIMARY KEY", $sql);
        $sql = str_replace("SERIAL PRIMARY KEY", "INTEGER PRIMARY KEY AUTOINCREMENT", $sql);
        $sql = str_replace("INT UNSIGNED NOT NULL", "INTEGER NOT NULL", $sql);
        // SQLite doesn't support DROP TABLE IF EXISTS without some considerations but it usually works
    }

    try {
        $db->exec($sql);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }

    return;
}

function get_pokemons($db, $search = null): array {
    $query_str = "SELECT * FROM pokemons";
    $params = [];
    if ($search) {
        $query_str .= " WHERE name LIKE :search OR type1 LIKE :search OR type2 LIKE :search";
        $params['search'] = "%$search%";
    }
    $query_str .= " ORDER BY id";
    $query = $db->prepare($query_str);
    $query->execute($params);
    $results = $query->fetchAll();

    $pokemons = [];
    foreach ($results as $pokemon) {
        $pokemons[$pokemon["id"]] = $pokemon;
    }

    return $pokemons;
}

function get_pokemon($name, $db) {
    $query = $db->prepare("
        SELECT pokemons.*, pokemons_details.height, pokemons_details.weight, pokemons_details.hp, pokemons_details.atk, pokemons_details.def, pokemons_details.spe_atk, pokemons_details.spe_def, pokemons_details.speed
        FROM pokemons
        LEFT JOIN pokemons_details
        ON pokemons.id = pokemons_details.pokemon_id
        WHERE name = :name
    ");
    $query->execute(["name" => $name]);

    return $query->fetch();
}

function add_pokemon($name, $db) {
    try {
        $pokemon = retrieve_pokemon_data($name);

        try {
            $query = $db->prepare("INSERT INTO pokemons (id, name, img_url, type1, type2) VALUES (:id, :name, :img_url, :type1, :type2)");
            $query->execute([
                "id" => $pokemon["id"],
                "name" => $pokemon["name"],
                "img_url" => $pokemon["img_url"],
                "type1" => $pokemon["type1"],
                "type2" => $pokemon["type2"]
            ]);

            add_pokemon_details($pokemon, $db);
            return null; // Success
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Pokemon already exists";
            }
            return "Error adding pokemon: " . $e->getMessage();
        }

    } catch (\Throwable $th) {
        return "Pokemon not found: " . $th->getMessage();
    }
}

function measurments_to_float($measurment) {
    if (is_null($measurment)) return 0.0;
    // "1,5 m" -> 1.5 or "4,5 kg" -> 4.5
    return (float) str_replace(",", ".", explode(" ", $measurment)[0]);
}

function add_pokemon_details($pokemon, $db) {
    $query = $db->prepare("INSERT INTO pokemons_details (pokemon_id, height, weight, hp, atk, def, spe_atk, spe_def, speed) VALUES (:pokemon_id, :height, :weight, :hp, :atk, :def, :spe_atk, :spe_def, :speed)");
    try {
        $query->execute([
            "pokemon_id" => $pokemon["id"],
            "height" => measurments_to_float($pokemon["height"]),
            "weight" => measurments_to_float($pokemon["weight"]),
            "hp" => $pokemon["hp"],
            "atk" => $pokemon["atk"],
            "def" => $pokemon["def"],
            "spe_atk" => $pokemon["spe_atk"],
            "spe_def" => $pokemon["spe_def"],
            "speed" => $pokemon["speed"]
        ]);
    } catch (PDOException $e) {
        die("Error adding details: " . $e->getMessage());
    }
}

function delete_pokemon($name, $db) {
    $query = $db->prepare("DELETE FROM pokemons WHERE name = :name");
    $query->execute(["name" => $name]);
}

function update_nickname($name, $nickname, $db) {
    $query = $db->prepare("UPDATE pokemons SET nickname = :nickname WHERE name = :name");
    $query->execute([
        "nickname" => $nickname,
        "name" => $name
    ]);
}

function display_pokemon_card($pokemon) {
    $name = $pokemon["name"];
    $img_url = $pokemon["img_url"];
    $type1 = $pokemon["type1"];
    $type2 = $pokemon["type2"];
    $id = $pokemon["id"];

    echo "<div class='pokemon-card " . strtolower($type1) . "'>";
    echo "  <div class='card-header'>";
    echo "    <span class='pokemon-id'>#$id</span>";
    echo "    <form action='actions/remove_pokemon.php' method='post' class='delete-form'>";
    echo "      <input type='hidden' name='name' value='" . htmlspecialchars($name) . "'>";
    echo "      <button type='submit' class='delete-btn' onclick='return confirm(\"Supprimer " . htmlspecialchars($name) . " ?\")'>&times;</button>";
    echo "    </form>";
    echo "  </div>";
    echo "  <a href='pokemon.php?name=" . urlencode($name) . "'>";
    echo "    <div class='img-container'><img src='$img_url' alt='" . htmlspecialchars($name) . "'></div>";
    echo "    <div class='card-info'>";
    echo "      <h3>" . htmlspecialchars($name) . "</h3>";
    echo "      <div class='types'>";
    echo "        <span class='type " . strtolower($type1) . "'>" . htmlspecialchars($type1) . "</span>";
    if ($type2) {
        echo "        <span class='type " . strtolower($type2) . "'>" . htmlspecialchars($type2) . "</span>";
    }
    echo "      </div>";
    echo "    </div>";
    echo "  </a>";
    echo "</div>";
}
?>
