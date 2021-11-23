<!-- Koboyoda Arthur Assima - Fouad Medjahed -->
<?php
// chargement des autres classes nécessaires :

class Stations{

   private $commune, $carburants, $rayon, $json, $taille, $stations, $requete;

   public function __construct($commune, $carburants,$rayon) {
     for($i=0;$i<count($carburants);$i++) {
       if ($carburants[$i]=="Gazole"){
         $carburants[$i]="1,";
       }
       else if ($carburants[$i]=="SP95"){
         $carburants[$i]="2,";
       }
       else if ($carburants[$i]=="E85"){
         $carburants[$i]="3,";
       }
       else if ($carburants[$i]=="GPLc"){
         $carburants[$i]="4,";
       }
       else if ($carburants[$i]=="E10"){
         $carburants[$i]="5,";
       }
       else if ($carburants[$i]=="SP98"){
         $carburants[$i]="6,";
       }
     }

     for($i=0;$i<count($carburants);$i++) {
       $this->carburants.=$carburants[$i];
     }

     $this->carburants=substr($this->carburants,0,-1);
     if ($rayon==NULL)
         $rayon=1;
      $this->$rayon = $rayon;
      $this->requete="http://webtp.fil.univ-lille1.fr/~clerbout/carburant/stations.php?commune=".$commune."&carburants=".$this->carburants."&rayon=".$rayon;
      $this->json =  file_get_contents ($this->requete);
      $this->json=json_decode($this->json, true);
      $this->taille=$this->json['taille'];
      $this->stations=$this->json['data'];
   }

   public function toHTML(){
     if ($this->json['status']!='ok'){
       return "<h3 id='vide'>La commune choisie n'est pas répertoriée, veuillez réessayer.</h3>";
     }
     else if ($this->taille==0){
       return "<h3 id='vide'>La recherche n'a pas trouvé de stations, veuillez réessayer.</h3>";
     }
     else if ($this->taille==1){
       $res .= "<h3 id='plein'>Voici l'unique station correspondant à votre recherche. </h3>";
       $res .= "<br/>";
       $res.="<div id='cartecampus'> </div>";
       $res .= $this->createtable();
       return $res;
     }
     else if ($this->taille<21){
       $res .= "<h3 id='plein'>Voici les ".$this->taille." stations correspondant à votre recherche. </h3>";
       $res .= "</br>";
       $res.="<div id='cartecampus'> </div>";
       $res .= $this->createtable();
       return $res;
     }
     else {
       $res .= "<h3 id='plein'>Voici les 20 stations les plus proches correspondant à votre recherche.</h3>";
       $res .= "<br/>";
       $res.="<div id='cartecampus'> </div>";
       $res .= $this->createtable();
       return $res;
     }
   }

   public function createtable(){
     $res.="        <table id='communes'>
               <thead>
                 <tr><th>Nom</th><th>Marque</th><th>Adresse</th><th>Commune</th><th>Station d'autoroute</th><th>Services</th><th>Prix (/1 L)</th></tr>
               </thead>
               <tbody>";
     for ($i=0;$i<count($this->stations);$i++){
       $station=$this->stations[$i];
       $cp=$station['cp'];
       $adresse=$station['adresse'];
       $ville=$station['ville'];
       $lat=$station['latitude'];
       $lon=$station['longitude'];
       if (count($station["services"])<1)
          $services="Non renseignés";
        else{
           $services="<ul>";
           foreach($station["services"] as $service){
             $services.="<li>".$service."</li>";
           }
           $services.="</ul>";
       }
       $prix="<ul>";
       foreach($station["prix"] as $p){
         $prix.="<li>".$p["libelleCarburant"]." - ".$p["valeur"]."€</li>";
       }
       $prix.="</ul>";
       switch ($station["automate24"]){
         case 1:
           $ouvert="Oui";
           break;
         case 0:
           $ouvert="Non";
           break;
       }
       if (isset($station["nom"]))
             $nom=$station["nom"];
       else{
         $nom="Indéfini";
       }
       if (isset($station["marque"]))
             $marque=$station["marque"];
       else{
         $marque="Indéfinie";
       }
       switch ($station["pop"]) {
         case 'R':
           $route="Non";
           break;
         case 'A':
             $route="Oui";
             break;

         default:
           // code...
           break;
       }
       $res.="<tr id='$adresse, $cp' data-insee='$cp' data-lon='$lon' data-lat='$lat'>
       <td class='nom'>$nom</td>
       <td class='marque'>$marque</td>
       <td class='adresse'>$adresse</td>
       <td class='commune'>$ville</td>
       <td class='route'>$route</td>
       <td class='insee'>$cp</td>
       <td class='service'>$services</td>
       <td class='prix'> $prix </td>
       </tr>" ;
     }
     $res.="</tbody>
            </table>";
     return $res;
   }
}
?>
