<?php
session_start();
require_once 'db.php';

$idAnnuncio = $_GET['id'] ?? null;

if (!$idAnnuncio) {
  // Gestisci il caso in cui l'ID dell'annuncio non sia presente nella query string
  die("ID dell'annuncio non specificato.");
}

try {
  // Recupera i dettagli dell'annuncio specifico dal database utilizzando l'ID
  $query = "SELECT ad.*, adt.*, cm.descrCatMerc
            FROM annunci_ditte ad
            INNER JOIN anag_ditte adt ON ad.idUtente = adt.idUtente
            INNER JOIN cat_merc cm ON ad.codCatMerc = cm.codCatMerc
            WHERE ad.idAnnuncio = :idAnnuncio AND ad.approvato = 1";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(":idAnnuncio", $idAnnuncio, PDO::PARAM_INT);
  $stmt->execute();
  $annuncio = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$annuncio) {
    // Gestisci il caso in cui l'annuncio specificato non esista o non sia approvato
    die("Annuncio non trovato o non approvato.");
  }
} catch (PDOException $e) {
  die("Errore nella query: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="color_palette.css">
  <link rel="stylesheet" href="i_miei_annunci_style.css">
  <title>Annunci</title>
</head>

<body>
  <header>
    
    <?php require_once 'header.php'; ?>

  </header>
  <main>
    <br>
    <?php if (!empty($annuncio)) : ?>
      <div class="announcement" style = "width: 90%;">
        <div class="left-column" style = "width: 75%;">
          <div style="display: inline-flex; align-items: center;">
            <img src="<?php echo $annuncio['img_profile'] ? 'data:image/jpeg;base64,' . base64_encode($annuncio['img_profile']) : 'images/PROFILO.png'; ?>" alt="Immagine del profilo" style = "height: 50px; width: 50px; border-radius: 50%; margin-left:5px">
            <p style="font-weight:bold; color:#0a0a0a"><?= $annuncio['cognome'] . ', ' . $annuncio['nome'][0] . '.'; ?></p>
          </div>
          <div class="carousel-container">
            <div id="carouselExample<?= $annuncio['idAnnuncio']; ?>" class="carousel slide" data-interval="false">
              <div class="carousel-inner" style = "max-height: 600px;" >
                <?php for ($i = 1; $i <= 6; $i++) {
                  displayCarouselItem($i, $annuncio);
                } ?>
              </div>
              <?php if (countPhotos($annuncio) > 1) : ?>
                <a class="carousel-control-prev" href="#carouselExample<?= $annuncio['idAnnuncio']; ?>" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Precedente</span>
                </a>

                <a class="carousel-control-next" href="#carouselExample<?= $annuncio['idAnnuncio']; ?>" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Successivo</span>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="right-column">
          <div class="announcement-content">
            <div>
              <?php $userId; ?>
            </div>
            <h3><?= $annuncio["titolo"]; ?></h3>
            <p style='margin-bottom: 0px'><?= ottieniDescrizioneCategoria($annuncio["codCatMerc"], $conn); ?></p>
            <?php
            $dataInserimento = new DateTime($annuncio["dataInserimento"]);
            $today = new DateTime();
            $differenza = $dataInserimento->diff($today);

            $intervallo = "";

            if ($differenza->y > 0) {
              $intervallo = $differenza->y . " anni fa";
            } elseif ($differenza->m > 0) {
              $intervallo = $differenza->m . " mesi fa";
            } elseif ($differenza->d > 0) {
              $intervallo = $differenza->d . " giorni fa";
            } elseif ($differenza->days >= 7) {
              $settimane = floor($differenza->days / 7);
              $intervallo = $settimane . " settimane fa";
            } else {
              $intervallo = "Oggi";
            }
            ?>
            <i><p style='font-size: 13px; color:var(--colore-secondario)'><?= $intervallo; ?></p></i>
            <p><strong>Descrizione: <br></strong><?= $annuncio["descr"]; ?></p>
            <?php if (!empty($annuncio["email"])) : ?>
              <div>
                <i class="fas fa-envelope" style="color:var(--colore-secondario); font-size: 14px"></i> <?= $annuncio["email"]; ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($annuncio["linkFacebook"])) : ?>
              <div>
                <i class="fab fa-facebook" style="color:var(--colore-secondario)"></i> <a href="<?= $annuncio["linkFacebook"]; ?>" target="_blank" style='color:var(--colore-testo); font-size: 14px'>Pagina Facebook</a>
              </div>
            <?php endif; ?>
            <?php if (!empty($annuncio["linkVendita"])) : ?>
              <div>
                <i class="fas fa-external-link-alt" style="color:var(--colore-secondario)"></i> <a href="<?= $annuncio["linkVendita"]; ?>" target="_blank" style='color:var(--colore-testo); font-size: 14px'>Link di Vendita</a>
              </div>
            <?php endif; ?>

            <div id="map_<?= $annuncio['idAnnuncio']; ?>" style="height: 160px; margin-bottom: 10px; margin-top: 10px"></div>
            <script>
              var coords = '<?= $annuncio["posizione"]; ?>'.split(',');
              var lat = parseFloat(coords[0].trim());
              var lon = parseFloat(coords[1].trim());

              var mapElement = document.getElementById("map_<?= $annuncio['idAnnuncio']; ?>");

              if (mapElement !== null) {
                var map = L.map(mapElement).setView([lat, lon], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  maxZoom: 19,
                  attribution: 'OpenStreetMap'
                }).addTo(map);

                L.marker([lat, lon]).addTo(map)
                  .bindPopup('Posizione')
                  .openPopup();
              } else {
                console.error("Elemento mappa non trovato: map_<?= $annuncio['idAnnuncio']; ?>");
              }
            </script>

            <?php
            $coords = explode(',', $annuncio["posizione"]);
            $lat = trim($coords[0]);
            $lon = trim($coords[1]);

            $geocodeUrl = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$lat}&lon={$lon}";

            $opts = [
              'http' => [
                'method' => 'GET',
                'header' => "User-Agent: setpronto/1.0\r\n"
              ]
            ];
            $context = stream_context_create($opts);

            $geocodeData = file_get_contents($geocodeUrl, false, $context);

            if ($geocodeData === false) {
              echo "Errore durante il recupero dei dati dall'API";
            } else {
              $geocodeJson = json_decode($geocodeData, true);

              if ($geocodeJson && isset($geocodeJson["address"])) {
                $region = isset($geocodeJson["address"]["state"]) ? $geocodeJson["address"]["state"] : '';
                $city = isset($geocodeJson["address"]["city"]) ? $geocodeJson["address"]["city"] : '';

                echo "<p style='margin-bottom: 0px'><strong>";

                if (!empty($city)) {
                  echo "{$city}";
                }

                if (!empty($region)) {
                  if (!empty($city)) {
                    echo ", ";
                  }
                  echo "{$region}";
                }

                echo "</strong></p><i><p style='font-size: 13px; color:var(--colore-secondario)''>La posizione è approssimativa.</p></i>";
              }
            }
            ?>

          </div>
          <div>
            <div>
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-briefcase" style="color:var(--colore-secondario);"></i> Professione:</strong> <?php echo htmlspecialchars($annuncio["professione"]); ?></p> 
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-user" style="color:var(--colore-secondario);"></i> Contatto:</strong> <?php echo htmlspecialchars($annuncio["contatto"]); ?></p>
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-phone" style="color:var(--colore-secondario);"></i> Telefono Contatto:</strong> <?php echo htmlspecialchars($annuncio["telefonoContatto"]); ?></p>
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-mobile-alt" style="color:var(--colore-secondario);"></i> Cellulare Contatto:</strong> <?php echo htmlspecialchars($annuncio["cellulareContatto"]); ?></p>
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-fax" style="color:var(--colore-secondario);"></i> Fax Contatto:</strong> <?php echo htmlspecialchars($annuncio["faxContatto"]); ?></p>
              <p style="margin-bottom: 5px;"><strong><i class="fas fa-envelope" style="color:var(--colore-secondario);"></i> Email Contatto:</strong> <?php echo htmlspecialchars($annuncio["mailContatto"]); ?></p>
            </div>
        </div>
      </div>
    <?php else : ?>
      <br><br><br><br>
      <p style='font-weight: bold; color:grey; font-size:16px; text-align: center'>L'annuncio specificato non è stato trovato o non è stato approvato.</p>
    <?php endif; ?>
  </main>
  <script>
    loadImagesAsync();

    function applyFilter() {
      const filterInput = document.getElementById("filterInput").value;
      const currentUrl = window.location.href;

      const urlWithoutQuery = currentUrl.split('?')[0];
      const newUrl = urlWithoutQuery + '?keyword=' + filterInput;

      window.location.href = newUrl;
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<?php
function anyPhotos($annuncio) {
  for ($i = 1; $i <= 6; $i++) {
    if (!empty($annuncio["foto" . $i])) {
      return true;
    }
  }
  return false;
}

function countPhotos($annuncio) {
  $count = 0;
  for ($i = 1; $i <= 6; $i++) {
    if (!empty($annuncio["foto" . $i])) {
      $count++;
    }
  }
  return $count;
}

function displayCarouselItem($i, $annuncio) {
  if (!empty($annuncio["foto" . $i])) { ?>
    <div class="carousel-item <?= ($i === 1) ? 'active' : ''; ?>">
      <img src="data:image/jpeg;base64,<?= base64_encode($annuncio["foto" . $i]); ?>" class="d-block mx-auto" alt="Foto <?= $i; ?>" style="max-height: 600px;">
    </div>
  <?php }
}

function ottieniDescrizioneCategoria($codCatMerc, $conn) {
  $query = "SELECT descrCatMerc FROM cat_merc WHERE codCatMerc = :codCatMerc";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(":codCatMerc", $codCatMerc, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result["descrCatMerc"];
}

?>
