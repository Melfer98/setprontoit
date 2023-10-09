<?php
session_start();
require_once 'db.php';

// Creare un array associativo per tutte le categorie, inizializzando il conteggio degli annunci a 0 per ciascuna categoria
$allCategories = array();

try {
  $stmt = $conn->query("SELECT * FROM cat_merc ORDER BY descrCatMerc ASC");
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Calcolare il conteggio degli annunci per ciascuna categoria
  foreach ($categories as $category) {
    $allCategories[$category['descrCatMerc']] = 0;
  }
} catch (PDOException $e) {
  die("Errore nella query: " . $e->getMessage());
}

$query = "SELECT ad.*, adt.nome, adt.cognome, adt.img_profile, cm.descrCatMerc
          FROM annunci_ditte ad
          INNER JOIN anag_ditte adt ON ad.idUtente = adt.idUtente
          INNER JOIN cat_merc cm ON ad.codCatMerc = cm.codCatMerc
          WHERE ad.approvato = 1";

$stmt = $conn->prepare($query);
$stmt->execute();
$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inizializza l'array $usersByCategory
$usersByCategory = array();

// Raggruppa gli annunci per categoria
foreach ($anuncios as $anuncio) {
    $categoryName = $anuncio['descrCatMerc'];
    
    // Verifica se la categoria esiste nell'array, altrimenti inizializzala
    if (!isset($usersByCategory[$categoryName])) {
        $usersByCategory[$categoryName] = array();
    }
    
    // Aggiungi l'utente all'array della categoria
    $usersByCategory[$categoryName][] = $anuncio;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ricerca per fornitore</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <style>
    /* Reset CSS per resettare i margini e i padding predefiniti */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Stile del corpo della pagina */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
}

/* Contenitore delle categorie */
.category-container {
  display: inline-block;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

/* Stile delle categorie */
.category {
  background-color: white;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 20px;
  width: 660px; /* Larghezza sufficiente per 5 utenti in una riga */
  text-align: center;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  margin: 20px;
  display: none; /* Inizialmente nascondi tutti i contenitori delle categorie */
}

/* Titolo della categoria */
.category h3 {
  font-size: 18px;
  margin-bottom: 10px;
}

/* Lista degli utenti nella categoria */
.user-list {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start; /* Allineamento degli utenti a sinistra */
}

/* Stile dell'utente */
.user {
  width: 120px; /* Larghezza fissa per ciascun utente */
  margin: 10px;
  text-align: center;
}

/* Immagine dell'utente */
.user-image img {
  max-width: 100px;
  max-height: 100px;
  border-radius: 50%;
}

/* Nome dell'utente */
.user span {
  font-weight: bold;
  margin-top: 5px;
}

/* Stile per il messaggio quando non ci sono annunci */
.no-announcements {
  color: #888;
}

/* Immagine predefinita quando non ci sono annunci */
.no-announcements img {
  max-width: 150px;
  max-height: 150px;
}
 /* Stile del contenitore del filtro */
 .filter-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
  }

  /* Stile dell'icona del filtro */
  .filter-icon {
    font-size: 30px;
    color: var(--colore-secondario);
  }

  /* Stile del menu a tendina */
  select#category {
    padding: 5px;
    font-size: 15px;
    color: #888;
  }
  </style>
</head>
<body>
  <header>
    <?php require_once 'header.php'; ?>
  </header>
  <br>

  <div class="filter-container">
    <i class="fas fa-filter filter-icon"></i>
    <form>
      <label for="category" style="color: var(--colore-secondario); font-weight: bold; font-size:18px">   </label>
      <select name="category" id="category">
        <option value="">Tutte le categorie</option>
        <?php foreach ($allCategories as $categoryName => $count): ?>
          <option value="<?= $categoryName ?>"><?= $categoryName ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
  <div class="category-container">
    <?php
    // Itera attraverso tutte le categorie
    foreach ($allCategories as $categoryName => $count): ?>
      <?php if (!empty($usersByCategory[$categoryName])): // Verifica se ci sono annunci per questa categoria ?>
        <a href="annunci_per_utente.php?keyword=<?= urlencode($categoryName) ?>" style="text-decoration: none; color: var(--colore-testo)">
        <div class="category">
          
          <h3><?= $categoryName ?></h3>
          <div class="user-list">
            <?php foreach ($usersByCategory[$categoryName] as $user): ?>
              <div class="user">
                <?php
                $encodedImage = base64_encode($user['img_profile']);
                $imageSrc = !empty($encodedImage) ? "data:image/jpeg;base64,{$encodedImage}" : 'images\PROFILO.png';
                ?>
                <div class="user-image">
                  <img src="<?= $imageSrc ?>" alt="<?= $user['nome'] ?> <?= $user['cognome'] ?>">
                </div>
                <span><?= $user['nome'] ?> <?= $user['cognome'] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        </a>
      <?php else: // Mostra un messaggio quando non ci sono annunci ?>
        <div class="category">
          <h3><?= $categoryName ?></h3>
          <div class="no-announcements">
            <p>Nessun fornitore in questa categoria.</p>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <script>
    // Funzione per gestire il filtro delle categorie
    function filterCategories() {
      const selectedCategory = document.getElementById('category').value;
      const categoryContainers = document.querySelectorAll('.category');

      categoryContainers.forEach((container) => {
        const categoryName = container.querySelector('h3').innerText;
        if (selectedCategory === '' || categoryName === selectedCategory) {
          container.style.display = 'block'; // Mostra il contenitore della categoria selezionata o tutti se nessuna categoria è selezionata
        } else {
          container.style.display = 'none'; // Nascondi gli altri contenitori
        }
      });
    }

    // Aggiungi un gestore di eventi al menu a tendina per chiamare la funzione di filtro quando cambia il valore
    document.getElementById('category').addEventListener('change', filterCategories);

    // Chiama la funzione di filtro all'avvio della pagina per impostare lo stato iniziale
    filterCategories();
  </script>
</body>
</html>
