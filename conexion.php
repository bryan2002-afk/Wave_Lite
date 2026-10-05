<?php

try {

    // Ruta del archivo SQLite
    $bd = __DIR__ . "/bd_lite/wave.db";

    // Crear conexión SQLite usando PDO
    $conn = new PDO("sqlite:" . $bd);

    // Configurar errores como excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Activar llaves foráneas
    $conn->exec("PRAGMA foreign_keys = ON;");


} catch (PDOException $e) {

    die("Error de conexión SQLite: " . $e->getMessage());

}

?>