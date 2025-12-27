<?php

// Define constants expected by Config if they depend on order, but Config usually defines them.
// Load Configuration which also loads Env
require_once __DIR__ . '/app/Config/config.php';

// Manually require Database class since we might not have an autoloader
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    echo "Connecting to MySQL server to check database existence...\n";

    // Connect without selecting a database first
    $dsnNoDb = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsnNoDb, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        throw new Exception("Connection failed: " . $e->getMessage());
    }

    $dbName = DB_NAME;
    echo "Creating database '$dbName' if it doesn't exist...\n";

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci");

    // Select the database
    $pdo->exec("USE `$dbName`");
    echo "Database '$dbName' selected.\n";

    $schemaPath = __DIR__ . '/database/schema.sql';

    if (!file_exists($schemaPath)) {
        throw new Exception("Schema file not found at " . $schemaPath);
    }

    $schemaSql = file_get_contents($schemaPath);

    if (empty(trim($schemaSql))) {
        echo "Schema file is empty.\n";
        exit;
    }

    echo "Executing schema...\n";
    $pdo->exec($schemaSql);

    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    echo "Migration Failed:\n";
    echo $e->getMessage() . "\n";
    exit(1);
}
