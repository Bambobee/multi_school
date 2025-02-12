<?php

$DB_NAME = "jonard_schools";
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";

$OPTIONS = [
    PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Make the default fetch be an associative array
];

try {
    $DSN = "mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME; // Add the semicolon after host
    $conn = new PDO($DSN, $DB_USER, $DB_PASS, $OPTIONS);
} catch (PDOException $ex) { // Change Exception to PDOException
    echo "Connection Problem " . $ex->getMessage();
    die(); // Remove $conn, as it's not defined here
}
