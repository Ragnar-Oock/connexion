// version modifiée le 07/06/2018
// =========================  passer le focus a un champ
function donner_focus(frm,champ)	{
		document.forms[frm].elements[champ].focus();
	}
// ========================= fonctions de navigation dans la liste de choix
function premier(frm, liste)	{
		document.forms[frm].elements[liste].value = document.forms[frm].elements[liste].options[0].value;
		document.forms[frm].submit();
	}
function precedent(frm, liste)	{
		document.forms[frm].elements[liste].value = document.forms[frm].elements[liste].options[Math.max(0,document.forms[frm].elements[liste].selectedIndex-1)].value;
		document.forms[frm].submit();
	}
function suivant(frm, liste)	{
		document.forms[frm].elements[liste].value = document.forms[frm].elements[liste].options[(Math.min((document.forms[frm].elements[liste].options.length-1),document.forms[frm].elements[liste].selectedIndex+1))].value;
		document.forms[frm].submit();
	}
function dernier(frm, liste)	{
		document.forms[frm].elements[liste].value = document.forms[frm].elements[liste].options[(document.forms[frm].elements[liste].options.length-1)].value;
		document.forms[frm].submit();
	}

// ========================= fonction annulation de saisie ou modification
function annuler(frm) {
			document.forms[frm].zOk.value="nonOk";
			document.forms[frm].submit();
	}


// ========================= fonctions de controle de validite d'un champ
function surligne(frm, champ, erreur)
{
  if(erreur) {
		champ.style.backgroundColor = "#f55"; alert("Champ '"+champ.id+"' incorrect ...\nMerci de corriger"); document.getElementById(champ.id).focus(); frm.zOk.value="nonOk";
	}
  else {
		champ.style.backgroundColor = "#fff"; frm.zOk.value="OK";
	}
}

// ========================= fonctions de controle de validite d'un champ texte (longueur)
function verifTexte(frm, champ, longueur)
{
	if(champ.value.length < 2 || champ.value.length > longueur) {
		surligne(frm, champ, true); return false;
	}
	else {
		surligne(frm, champ, false); return true;
	}
}

// ========================= fonctions de controle de validite d'une date
function verifDate(laDate)
{
  var ok=true;
  var d=laDate.value;
  laDate.style.backgroundColor="#fff";
  if(d !== null && d !== "") {
	  var amini=1900; // annee mini
	  var amax=2030; // annee maxi
	  var separateur="/"; // separateur entre jour/mois/annee
	  var j=(d.substring(0,2));
	  var m=(d.substring(3,5));
	  var a=(d.substring(6));

	  if ( ((isNaN(j))||(j<1)||(j>31)) && (ok==1) ) {
			alert(j+" n'est pas un jour correct...");
			laDate.style.backgroundColor="#f55";
			ok=false;
		}
	  if ( ((isNaN(m))||(m<1)||(m>12)) && (ok==1) ) {
			alert(m+" n'est pas un mois correct ...");
			laDate.style.backgroundColor="#f55";
			ok=false;
		}
	  if ( ((isNaN(a))||(a<amini)||(a>amax)) && (ok==1) ) {
			alert(a+" n'est pas une annee correcte: utiliser 4 chiffres, \n elle doit etre comprise entre "+amini+" et "+amax);
			laDate.style.backgroundColor="#f55";
			ok=false;
		}
		if ( ((d.substring(2,3)!=separateur)||(d.substring(5,6)!=separateur)) && (ok==1) ) {
			alert("Les separateurs doivent etre des "+separateur);
			laDate.style.backgroundColor="#f55";
			ok=false;
		}
	  if (ok===true) {
			var d2=new Date(a,m-1,j);
			var j2=d2.getDate();
			var m2=d2.getMonth()+1;
			var a2=d2.getFullYear();
			if (a2<=100) {
				a2=1900+a2;
		  }
			if ( (j!=j2)||(m!=m2)||(a!=a2) ) {
				alert("La date "+d+" n'existe pas !");
				laDate.style.backgroundColor="#f55";
				ok=false;
			}
  	}
  }
  return ok;
}

// ========================= affiche l'onglet choisi
 function Affiche(ongletChoisi, nb)
{
	var ongletActif=1;
	for(var i=1;i<nb+1;i++) {
		document.getElementById('onglet'+i).className = 'inactif onglet';
		document.getElementById('contenuOnglet'+i).style.display = 'none';
	}
	document.getElementById('onglet'+ongletChoisi).className = 'actif onglet';
	document.getElementById('contenuOnglet'+ongletChoisi).style.display = 'block';
	document.getElementById('zOnglet').value=ongletChoisi;
	document.getElementById('zNbOnglets').value=nb;
	ongletActif=ongletChoisi;
}

//==========================check toutes les checkboxes du formulaire "ajouter un utilisateur" quand on clic sur "toutes"
function checkAll(maitre, esclavesClasse) {
	//on déclare une variable "checkboxes" dans laquelle on entre le nom de la classe "escalvesClasse"
	var checkboxes = document.getElementsByClassName(esclavesClasse);
	//Pour chaque i=compteur, i plus petit que le nombre de checkbox esclaves, incrément de la boucle
 	for(var i=0;i<checkboxes.length;i++) {
		//si la checkbox maitre est cochée
		if (maitre.checked) {
			//on check la box de rang i
			checkboxes[i].checked=true;
		}
		else {
			//sinon on décoche la box
			checkboxes[i].checked=false;
		}
	}
}
//=========================="ajouter un utilisateur" décoche "toutes" si l'on décoche une checkbox
function cascadeCheckboxes(maitre_, esclavesClasse) {
	//checkboxes est une variable qui contient "esclavesClasse"
	var checkboxes = document.getElementsByClassName(esclavesClasse);
	//maitre est une variable qui contient l'id de la checkbox maitre (donc unique)
	var maitre = document.getElementById(maitre_);
	//toutesCheckees est une variable qui permet de savoir si toutes les checkboxes sont checkées
	var toutesCheckees = true;
	//si la box que appel la fonction n'est pas cochée
	if(this.checked === false) {
		//alors on décoche maitre
		maitre.checked=false;
	}
	//sinon la box qui appel la fonction est cochée
	else {
		//on test pour savoir si les autres checkboxes sont cochées
	 	for(var i=0;i<checkboxes.length;i++) {
			//si la checkbox d'index i n'est pas checkée
			if(checkboxes[i].checked === false) {
				//on décheck maitre
				maitre.checked=false;
				toutesCheckees = false;
			}
			//sinon: les autres box sont checkées
			else {
				if(toutesCheckees === true) {
					//donc on check maitre
					maitre.checked = true;
				}
			}
		}
	}
}

//=========================="ajouter un utilisateur" vérifie qu'au moins une base de données est cochée
function verificationFormulaire()
{
	var login = document.getElementsByClassName("login")[0].value;
	var mdp1 = document.getElementById("mdp1").value;
	var mdp2 = document.getElementById("mdp2").value;
		//le login doit faire au moins 6 caractères de long
		if(login.length >= 6){
			//les mots de passes doivent être identiques
			if(mdp1.localeCompare(mdp2) == 0){
				//alors on execute le formulaire
  			document.getElementById("creationMDP").submit();
      }
      else {
        alert("Les mots de passes ne sont pas identiques");
      }
		}
		//sinon le login fait moins de 6 caractères
		else {
			alert("Le login doit être d'au moins 6 caractères!");
		}
	}
//==========================ON/OFF du bouton détails connexions
function toggleDetailUtilisateur(id)
{
	//active ou désactive la classe "UU_details_hidden"
  document.getElementById(id).classList.toggle("UU_details_hidden");
}
