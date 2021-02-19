<?php

//Configuration options
define('DB_HOST', getenv("DB_HOST") ?? "127.0.0.1");
define('DB_USER', getenv("DB_USER") ?? "rdmuser");
define('DB_PSWD', getenv("DB_PSWD") ?? "password");
define('DB_NAME', getenv("DB_NAME") ?? "rdmdb");
define('DB_PORT', getenv("DB_PORT") ?? 3306);
define('OWN_TS', getenv("OWN_TS") ?? "https://IP:PORT/tile/STYLE/{z}/{x}/{y}/1/png");

?>