<?php
session_start();

// Verifica se l'utente è loggato, altrimenti reindirizza alla pagina di login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbsetpronto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Errore di connessione: " . $conn->connect_error);
}

$hashedId = $_GET['user'];

$idUtente = base64_decode($hashedId);

$sql = "SELECT * FROM anag_ditte WHERE idUtente = '$idUtente'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Profilo non trovato.";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Anagrafica Profilo</title>
    <link rel="stylesheet" href="anagrafia_style.css">
    <link rel="stylesheet" href="color_palette.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <header><?php require_once 'header.php'; ?></header><br>
    <main>
    <div class="profile-container">
        
        
        <div class="categories-list">
            <div class="profile-image">
                        <?php
                        if (!empty($row["img_profile"])) {
                            $imgData = base64_encode($row["img_profile"]);
                            $imgSrc = "data:image/jpeg;base64,$imgData";
                            echo "<img src='$imgSrc' alt='Profile'>";
                        } else {
                            echo "<img src='images/PROFILO.png' alt='Profile'>";
                        }
                        ?>
            </div>
            <h3>Informazione</h3>
            <ul>
                <li class="category-item selected" data-section="dati_personali">Dati personali</li>
                <li class="category-item" data-section="dettagli_azienda">Dettagli sull'azienda</li>
                <li class="category-item" data-section="indirizzo">Indirizzo</li>
                <li class="category-item" data-section="informazioni_contatto">Contatti</li>
                <li class="category-item" data-section="domande">Domande</li>
            </ul>
         </div>
            <div class="data-container">
            
            <h2 style="margin-top: 0px; text-align: center; margin-top:20px"><?php echo htmlspecialchars($row["nome"]) . ' ' . htmlspecialchars($row["cognome"]); ?></h2>
            
                <div class="dati_personali personal-info">
                    <div class="h-container">
                        <h3>Dati personali</h3>
                    </div>
                    <!-- Dati personali -->
                    <p><strong><i class="fas fa-briefcase"></i>Professione:</strong> <?php echo htmlspecialchars($row["professione"]); ?></p>
                    <p><strong><i class="fas fa-envelope"></i>Email:</strong> <?php echo htmlspecialchars($row["mail"]); ?></p>
                    <p><strong><i class="fas fa-mobile-alt"></i>Cellulare:</strong> <?php echo htmlspecialchars($row["cellulare"]); ?></p>
                    <p><strong><i class="fas fa-phone"></i>Telefono 1:</strong> <?php echo htmlspecialchars($row["telefono"]); ?></p>
                    <p><strong><i class="fas fa-phone"></i>Telefono 2:</strong> <?php echo htmlspecialchars($row["telefono2"]); ?></p>
                    <p><strong><i class="fas fa-phone"></i>Telefono 3:</strong> <?php echo htmlspecialchars($row["telefono3"]); ?></p>
                    <p><strong><i class="fas fa-fax"></i>Fax:</strong> <?php echo htmlspecialchars($row["fax"]); ?></p>
                    <p><strong><i class="far fa-calendar"></i>Data di Registrazione:</strong> <?php echo date('d F Y', strtotime($row["dataReg"])); ?></p>
                    <br>
                </div>
                <div class="dettagli_azienda personal-info">
                    <div class="h-container">
                        <h3>Dettagli sull'azienda</h3>
                    </div>
                    <!-- Dettagli sull'azienda -->
                    <p><strong><i class="fas fa-layer-group"></i> Tipologia:</strong> <?php echo htmlspecialchars($row["tipologia"]); ?></p>
                    <p><strong><i class="fas fa-building"></i> Ragione Sociale:</strong> <?php echo htmlspecialchars($row["ragioneSociale"]); ?></p>
                    <p><strong><i class="fas fa-id-card"></i> Codice Fiscale:</strong> <?php echo htmlspecialchars($row["cfiscPiva"]); ?></p>
                    <p><strong><i class="fas fa-sticky-note"></i> Descrizione Breve:</strong> <?php echo htmlspecialchars($row["descrBreve"]); ?></p>
                    <p><strong><i class="fas fa-file-alt"></i> Descrizione Completa:</strong> <?php echo htmlspecialchars($row["descrCompleta"]); ?></p>
                    <p><strong><i class="fas fa-globe"></i> Sito Web:</strong> <a href="<?php echo htmlspecialchars($row["sitoWeb"]); ?>" target="_blank"><?php echo htmlspecialchars($row["sitoWeb"]); ?></a></p>
                    <p><strong><i class="fab fa-skype"></i> Skype:</strong> <?php echo htmlspecialchars($row["skype"]); ?></p>
                </div>
                <div class="indirizzo personal-info">
                    <div class="h-container">
                        <h3>Indirizzo</h3>
                    </div>
                    <!-- Indirizzo -->
                    <p><strong><i class="fas fa-map-marker-alt"></i> Indirizzo:</strong> <?php echo htmlspecialchars($row["indirizzo"]); ?></p>
                    <p><strong><i class="fas fa-map"></i> Provincia:</strong> <?php echo htmlspecialchars($row["provincia"]); ?></p>
                    <p><strong><i class="fas fa-city"></i> Città:</strong> <?php echo htmlspecialchars($row["citta"]); ?></p>
                    <p><strong><i class="fas fa-envelope-open-text"></i> CAP:</strong> <?php echo htmlspecialchars($row["cap"]); ?></p>
                </div>

                <div class="informazioni_contatto personal-info">
                    <div class="h-container">
                        <h3>Contatti</h3>
                    </div>
                    <!-- Contatti -->
                    <p><strong><i class="fas fa-user"></i> Contatto:</strong> <?php echo htmlspecialchars($row["contatto"]); ?></p>
                    <p><strong><i class="fas fa-phone"></i> Telefono Contatto:</strong> <?php echo htmlspecialchars($row["telefonoContatto"]); ?></p>
                    <p><strong><i class="fas fa-mobile-alt"></i> Cellulare Contatto:</strong> <?php echo htmlspecialchars($row["cellulareContatto"]); ?></p>
                    <p><strong><i class="fas fa-fax"></i> Fax Contatto:</strong> <?php echo htmlspecialchars($row["faxContatto"]); ?></p>
                    <p><strong><i class="fas fa-envelope"></i> Email Contatto:</strong> <?php echo htmlspecialchars($row["mailContatto"]); ?></p>
                </div>

                <div class="domande personal-info">
                    <div class="h-container">
                        <h3>Domande</h3>
                    </div>
                    <!-- Domande -->
                    <p><strong><i class="fas fa-question"></i> Domanda 1:</strong> <?php echo htmlspecialchars($row["domanda1"]); ?></p>
                    <p><strong><i class="fas fa-question"></i> Domanda 2:</strong> <?php echo htmlspecialchars($row["domanda2"]); ?></p>
                </div>

            </div>
        </div>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const categoryItems = document.querySelectorAll(".category-item");
        const personalInfoSections = document.querySelectorAll(".personal-info");

        // Nascondi tutte le sezioni tranne "Dati personali"
        personalInfoSections.forEach(function (section) {
            section.style.display = "none";
        });
        document.querySelector(".dati_personali").style.display = "block"; // Mostra solo "Dati personali" all'avvio

        categoryItems.forEach(function (item) {
            item.addEventListener("click", function () {
                categoryItems.forEach(function (item) {
                    item.classList.remove("selected");
                });
                this.classList.add("selected");

                const sectionToShow = this.getAttribute("data-section");
                personalInfoSections.forEach(function (section) {
                    section.style.display = "none";
                });
                document.querySelector("." + sectionToShow).style.display = "block"; // Usa "block" invece di "flex"
            });
        });

        // Gestione dei moduli di modifica per tutte le sezioni
        function setupModificaModulo(buttonId, formId, overlayId, closeButtonId, cancelId) {
            const modificaButton = document.getElementById(buttonId);
            const formModifica = document.getElementById(formId);
            const popupOverlay = document.getElementById(overlayId);
            const popupCloseButton = document.getElementById(closeButtonId);
            const popupCancelButton = document.getElementById(cancelId);

            modificaButton.addEventListener("click", function () {
                // Mostra il modulo di modifica quando il pulsante viene cliccato
                popupOverlay.style.display = "block";
            });

            popupCloseButton.addEventListener("click", function () {
                // Chiudi il popup quando il pulsante di chiusura viene cliccato
                popupOverlay.style.display = "none";
            });

            popupCancelButton.addEventListener("click", function () {
                // Chiudi il popup anche se si clicca su "Annulla"
                popupOverlay.style.display = "none";
            });
        }

        // Configura i moduli di modifica per tutte le sezioni
        setupModificaModulo("modifica-button", "form-modifica", "popup-overlay", "popup-close-button", "popup-cancel-button");
        setupModificaModulo("modifica-button-azienda", "form-modifica-azienda", "popup-overlay-azienda", "popup-close-button-azienda", "popup-cancel-azienda-button");
        setupModificaModulo("modifica-button-indirizzo", "form-modifica-indirizzo", "popup-overlay-indirizzo", "popup-close-button-indirizzo", "popup-cancel-indirizzo-button");
        setupModificaModulo("modifica-button-contatti", "form-modifica-contatti", "popup-overlay-contatti", "popup-close-button-contatti", "popup-cancel-contatti-button");
        setupModificaModulo("modifica-button-domande", "form-modifica-domande", "popup-overlay-domande", "popup-close-button-domande", "popup-cancel-domande-button");
    });
</script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    const inputImage = document.getElementById("inputImage");
    const buttonLabel = document.querySelector(".button-label");

    buttonLabel.addEventListener("click", function() {
        inputImage.click();
    });

    inputImage.addEventListener("change", function() {
    });
</script>
<script>

</script>
</body>
</html>
