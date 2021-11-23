<!-- Koboyoda Arthur Assima - Fouad Medjahed -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Projet Carto'Carbu</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="index.css" />
      <script src="scriptCarte.js"></script>
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
   integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
   crossorigin=""></script>
    </head>
    <body>
        <header>
            <h1>Projet Carto'Carbu </h1>
            <h2>Réalisé par <span class="nom">Arthur Assima & Fouad Medjahed</span></h2>
        </header>
        <?php
          if (isset($errorMessage)){
            echo "<section id='errorMessage'>\n";
            echo "  	<p> Erreur : $errorMessage</p>\n"; // à améliorer
            echo "</section>\n";
         }
        ?>
        <form action="./principal.php" method = "get">
          <fieldset>
            <legend>Recherchez des stations essence dans la région</legend>
            <label for="ville">Ville </label><input type="text" placeholder="Exemple : Lens" id="ville" name="ville" size="25" maxlength="100" /><br />

            <label for="rayon">Rayon (km) </label>
            <input type="text" placeholder="Vaut 1 si vide" id="rayon" name="rayon" maxlength="3" pattern=".*[0-9]" />
          <br/>
          </fieldset>
          <fieldset>
              <legend>Choisissez votre(vos) carburant(s) </legend>
              <label> Carburants :</label>
              <br/>
              <br/>
            <?php $car=["Gazole","SP95","E85","GPLc","E10","SP98"];
              for ($i=0;$i<count($car);$i++){
              $name=$car[$i];
              $res="";
              $res.= "<input  class='cb' name='carburants[]' id=\"$name\"  type='checkbox' value=\"$name\">
                      <label for=\"$name\"> $name</label> <br/>";

              echo $res;
            }
                ?>
          </fieldset>
          <fieldset>
            <button type="reset">Effacer</button>
            <button type="submit" name="valid" value="envoyer">Envoyer</button>
          </fieldset>
        </form>
          <?php
          require("./lib/Stations.class.php");
          $s=new Stations($commune,$carburants,$rayon);
          if (filter_input(INPUT_GET,'valid')!==NULL && !isset($errorMessage)) {
              echo $s->toHTML();
        	 }
         ?>


    </body>

</html>
