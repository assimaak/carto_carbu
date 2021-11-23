// Koboyoda Arthur Assima - Fouad Medjahed 
window.addEventListener("load",dessinerCarte);

// fonction de mise en place de la carte.
// Suppose qu'il existe dans le document
// un élément possédant id="cartecampus"
function dessinerCarte(){
    // création de la carte, centrée sur le point 50.60976, 3.13909, niveau de zoom 16
    // cette carte sera dessinée dans l'élément HTML "cartecampus"
    var map = L.map('cartecampus').setView([50.60976, 3.13909], 16);

    // ajout du fond de carte OpenStreetMap
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Mise en place d'une gestionnaire d'évènement : activerBouton se déclenchera à chaque ouverture de popup
    map.on("popupopen",activerBouton);

    // NB : map.on() est une méthode propre à la bibliothèque Leaflet qui permet d'associer des gestionnaires
    // aux évènements concernant la carte.
    map.on("click",afficheCoord);
    placerMarqueurs(map);
}

// gestionnaire d'évènement (déclenché lors de l'ouverture d'un popup)
// cette fonction va rendre actif le bouton inclus dans le popup en lui assocaint un gestionnaire d'évènement
function activerBouton(ev) {
    var noeudPopup = ev.popup._contentNode; // le noeud DOM qui contient le texte du popup
    var bouton = noeudPopup.querySelector("button"); // le noeud DOM du bouton inclu dans le popup
    bouton.addEventListener("click",boutonActive); // en cas de click, on déclenche la fonction boutonActive
    bouton.addEventListener("click",modifieListe);
}

// gestionnaire d'évènement (déclenché lors d'un click sur le bouton dans un popup)
function boutonActive(ev) {
    // this est ici le noeud DOM de <button>. La valeur associée au bouton est donc this.value
    alert("Adresse de la station : " + this.value);
}

function afficheCoord(ev) {
    alert(ev.latlng);
}

function placerMarqueurs(map) {
   var l = document.querySelectorAll("table#communes>tbody>tr"); //liste de toutes les lignes
   var pointList= [];
   for (var i=0; i<l.length; i++){ // pour chaque ligne, insertion d'un marqueur sur la carte
        // nom de la commune :
        var nom = l[i].querySelector("td.nom").textContent;
        // n° insee :
        var adresse = l[i].querySelector("td.adresse").textContent;
        var cp = l[i].querySelector("td.insee").textContent;
        var c= l[i].querySelector("td.insee");
        c.style.display="none";
        // texte du popup. Remarquez que l'on mémorise le code Insee dans l'attribut value du bouton  :
        // ce qui permettra de faire le lien entre le bouton et la commune concernée
        var texte = nom + " <button value=\""+adresse+", "+cp+"\">Choisir</button>";
        // insertion du marqueur selon les coordonnées trouvées dans les attributs data-lat et data-lon :
        var point = [l[i].dataset.lat, l[i].dataset.lon];
        L.marker(point).addTo(map).bindPopup(texte);
        pointList.push(point);
   }
   // ajustement de la zone d'affichage de la carte aux points marqués
    map.fitBounds(pointList);
}

// gestionnaire d'évènement (déclenché lors d'un click sur le bouton dans un popup)
// cette fonction va ajouter la classe "marquee" à la ligne de la commune concernée par le click.
// et retirer la classe marquee à la ligne qui l'avait précedemment
// la ligne marquée est ensuite déplacée en début de table
function modifieListe(ev) {
    // suppression de la classe marquee, si nécessaire
    var oldMarquee = document.querySelector("table#communes tr.marquee");
    if (oldMarquee)
        oldMarquee.classList.remove("marquee");
    // this est ici le noeud DOM de <button>. La valeur associée au bouton est donc this.value
    // identifiant de la ligne correspondant au bouton cliqué :
    var identifiant = this.value;
    // et récupération de l'objet DOM de la ligne :
    var ligne =  document.getElementById(identifiant);
    ligne.classList.add("marquee");
    // déplacement de la ligne pour la remonter en début de table :
    var parent = ligne.parentNode;
    parent.removeChild(ligne); //supprime la ligne
    parent.insertBefore(ligne, parent.firstChild); // et la ré-insère au debut
}
