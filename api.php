<?php

function retrieve_pokemon_data($name) {
    // List all pokemons to find by name (local search for more robustness with accents)
    $all_url = "https://tyradex.app/api/v1/pokemon";
    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n" .
                "cache-control: no-cache\r\n" .
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n",
            "method" => "GET"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($all_url, false, $context);
    $data = json_decode($response, true);

    $found = null;
    foreach ($data as $p) {
        if (isset($p['name']['fr']) && (strcasecmp($p['name']['fr'], $name) == 0)) {
            $found = $p;
            break;
        }
        if (isset($p['name']['en']) && (strcasecmp($p['name']['en'], $name) == 0)) {
            $found = $p;
            break;
        }
    }

    if (!$found) {
        throw new Exception("Pokemon not found: " . $name);
    }

    return parse_pokemon_data($found);
};

function parse_pokemon_data($data) {
    return [
        "id" => $data["pokedex_id"],
        "name" => $data["name"]["fr"],
        "img_url" => $data["sprites"]["regular"],
        "type1" => $data["types"][0]["name"],
        "type2" => $data["types"][1]["name"] ?? NULL,

        "height" => $data["height"],
        "weight" => $data["weight"],

        "hp" => $data["stats"]["hp"],
        "atk" => $data["stats"]["atk"],
        "def" => $data["stats"]["def"],
        "spe_atk" => $data["stats"]["spe_atk"],
        "spe_def" => $data["stats"]["spe_def"],
        "speed" => $data["stats"]["vit"]
    ];
}
