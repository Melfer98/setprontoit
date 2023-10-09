<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Membresia</title>
    <link rel="stylesheet" href="color_palette.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        h1{
            text-align: center;
        }
        .ab-container {
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between; /* Utilizza spazio tra le colonne */
        }

        .membership {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            flex: 1;
            margin: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            display: flex; /* Utilizza un display flex per allineare il pulsante alla fine */
            flex-direction: column; /* Cambia la direzione in verticale */
        }

        .membership h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .membership p {
            font-size: 16px;
        }

        .ab-btn {
            background-color: var(--colore-secondario);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: auto; /* Spinge il pulsante verso il basso */
            text-align: center;
            display: block;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }

        .ab-btn:hover {
            background-color: #fffbc8;
            color:var(--colore-secondario);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <?php require_once "header.php";?>
    </header><br>
    <h1>Acquista un abbonamento</h1>
    <div class="ab-container">
        <div class="membership">
            <h2>Basica</h2>
            <p>Accedi ai contenuti di base del nostro sito.</p>
            <p><strong>Prezzo:</strong> €10 al mese</p>
            <a href="#" class="ab-btn">Acquista ora</a>
        </div>
        <div class="membership">
            <h2>Personale</h2>
            <p>Accedi a contenuti personalizzati e servizi aggiuntivi.</p>
            <p><strong>Prezzo:</strong> €20 al mese</p>
            <a href="#" class="ab-btn">Acquista ora</a>
        </div>
        <div class="membership">
            <h2>Premium</h2>
            <p>Accesso completo a tutti i contenuti e vantaggi premium.</p>
            <p><strong>Prezzo:</strong> €30 al mese</p>
            <a href="#" class="ab-btn">Acquista ora</a>
        </div>
    </div>
</body>
</html>
