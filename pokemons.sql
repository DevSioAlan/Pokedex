DROP TABLE IF EXISTS pokemons_details;
DROP TABLE IF EXISTS pokemons;

CREATE TABLE IF NOT EXISTS pokemons (
    id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    img_url VARCHAR(255) NOT NULL,
    nickname VARCHAR(255),
    type1 VARCHAR(255) NOT NULL,
    type2 VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS pokemons_details (
    id SERIAL PRIMARY KEY,
    pokemon_id INT UNSIGNED NOT NULL,

    height DECIMAL(4, 1) NOT NULL,
    weight DECIMAL(4, 1) NOT NULL,

    hp INTEGER NOT NULL,
    atk INTEGER NOT NULL,
    def INTEGER NOT NULL,
    spe_atk INTEGER NOT NULL,
    spe_def INTEGER NOT NULL,
    speed INTEGER NOT NULL,

    FOREIGN KEY (pokemon_id) REFERENCES pokemons(id) ON DELETE CASCADE
);
