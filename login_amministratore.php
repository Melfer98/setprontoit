<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsetpronto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Errore di connessione: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_utente = $_POST["nome_utente"];
    $password = $_POST["password"];

    $sql = "SELECT id, nome_utente, password_hash FROM amministratore WHERE nome_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_utente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password_hash = $row["password_hash"];

        if (password_verify($password, $stored_password_hash)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["nome_utente"] = $row["nome_utente"];
            header("Location: amministratore.php"); 
        } else {
            $error = "Credenziali non valide.";
        }
    } else {
        $error = "Credenziali non valide.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Amministratore</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            text-align: center;
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Amministratore</h2><br>
        <div><img src="images\SETPRONTO.jpg" width="250"></div><br><br><br>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div><label for="nome_utente">Nome Utente:</label>
            <input type="text" name="nome_utente" required></div>
            <br>
            <div><label for="password">Password:</label>
            <input type="password" name="password" required></div>
            <br>
            <input type="submit" value="Accedi">
        </form><br>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>
