<link rel="stylesheet" href="color_palette.css">
<style>
    header {
      background-color: var(--colore-principale);
      display: flex;
      justify-content: space-between;
      align-items: center;
      text-align: center;
      width: 100%;
      height: 80px;
      z-index: 1000;
      position: relative;
    }

    .header-content {
      display: flex;
      align-items: center;
      width: 100%;
    }

    .logo-container {
      height: 80px;
      margin-right: 20px;
    }

    .logo-container img {
      height: 100%;
      width: auto;
      object-fit: contain;
    }

    .button-container {
    display: flex;
    flex-grow: 1;
    justify-content: right;
    align-items: center;
  }

  .button {
    display: flex;
    flex-direction: column; 
    align-items: center; 
    padding: 10px; 
    font-weight: bold;
    text-decoration: none;
    position: relative;
  }

  .button:hover {
    background-color: var(--colore-hover);
  }

  .button.active {
    background-color: var(--colore-secondario);
  }

  .button-icon img {
    width: 50px;
    height: 50px;
    display: block;
    margin-bottom: 5px;
    margin-left: 12px;
    margin-right: 12px;
  }

  .button-text {
    display: none;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 10px;
    border-radius: 10px;
    font-size: 13px;
    white-space: nowrap;
    z-index: 1;
  }

  .button:hover .button-text {
    display: block;
  }

  .button:hover .button-icon img {
    filter: brightness(0) invert(1);
  }
  .container {
    max-width: 700px;
    margin: 0 auto;
    padding: 20px;
    background-color: var(--colore-sfondociaro);
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: var(--colore-m2);
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
  }

  .dropdown-content a {
    color: black;
    padding: 12px 12px;
    text-decoration: none;
    display: block;
    font-weight: bold;
    white-space: nowrap; 
  }

  .dropdown:hover .dropdown-content {
    display: block;
    white-space: nowrap; 
    color: white;
  }
  .dropdown-content a:active,
  .dropdown-content a:focus,
  .dropdown-content a:hover {
    background-color: var(--colore-secondario);
    color: var(--colore-sfondociaro);
  }
  </style>
  <header>
    <div class="header-content">
      <div class="logo-container">
        <img src="images/SETPRONTO.jpg" alt="Logo">
      </div>
      
      <div class="button-container">

        <a href="ricerca_per_fornitore.php" class="button">
          <span class="button-icon">
            <img src="images/STORE.png" alt="Fornitori">
          </span>
          <span class="button-text">FORNITORI</span>
        </a>

        <a href="ricerca_per_categoria.php" class="button">
          <span class="button-icon">
            <img src="images/CATEGORIA.png" alt="Categoria">
          </span>
          <span class="button-text">CATEGORIA</span>
        </a>

        <a href="annunci.php" class="button">
          <span class="button-icon">
            <img src="images/RICERCA.png" alt="Ricerca">
          </span>
          <span class="button-text">RICERCA</span>
        </a>

        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
          echo '
          <div class="dropdown">
            <a class="button">
              <span class="button-icon">
                <img src="images/PROFILO.png" alt="Profilo">
              </span>
              <span class="button-text">PROFILO</span>
            </a>
            <div class="dropdown-content">
              <a href="nuovo_annuncio.php">NUOVO ANNUNCIO</a>
              <a href="i_miei_annunci.php">I MIEI ANNUNCI</a>
              <a href="abbonamenti.php">ABBONAMENTI</a>
              <a href="anagrafia.php">ANAGRAFIA</a>
              <a href="modifica_password.php">MODIFICA PASSWORD</a>
            </div>
          </div>
          <a href="logout.php" class="button">
            <span class="button-icon">
              <img src="images/ESCI.png" alt="Esci">
            </span>
            <span class="button-text">ESCI</span>
          </a>
          ';
        } else {
          echo '
          <a href="login.php" class="button">
            <span class="button-icon">
              <img src="images/PROFILO.png" alt="Login">
            </span>
            <span class="button-text">LOGIN</span>
          </a>
          ';
        }
        ?>
      </div>
    </div>
  </header>

