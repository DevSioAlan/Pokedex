<?php

define('DB_TYPE', 'sqlite'); // 'mysql' or 'sqlite'
define('DB_SQLITE_PATH', __DIR__ . '/pokedex.sq3');
define('DB_HOST', 'mysql-container');
define('DB_NAME', 'testdb');
define('DB_USER', 'user');
define('DB_PASS', 'password');

function connect_to_db() {
    try {
        if (DB_TYPE === 'mysql') {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $db = new PDO($dsn, DB_USER, DB_PASS);
        } else {
            $dsn = "sqlite:" . DB_SQLITE_PATH;
            $db = new PDO($dsn);
        }
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
