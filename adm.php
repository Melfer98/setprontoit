<?php
$nome_utente = "setprontoit_amministratore";
$password_in_chiaro = "SetPronto_AMM1N15TRAT0R3#";

$password_hash = password_hash($password_in_chiaro, PASSWORD_DEFAULT);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsetpronto";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inserta el nuevo usuario en la tabla
    $query = "INSERT INTO amministratore (nome_utente, password_hash) VALUES (:nome_utente, :password_hash)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nome_utente", $nome_utente, PDO::PARAM_STR);
    $stmt->bindParam(":password_hash", $password_hash, PDO::PARAM_STR);
    $stmt->execute();

    echo "Nuevo usuario agregado con éxito!";
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}
?>
