<?php

function getHTTPVars($name, $POST, $GET) {
 $value='';    
 if (isset($POST[$name])) {
  $value = $POST[$name];
 } elseif (isset($GET[$name])) {
  $value = $GET[$name];
 } else {
  $value = '';
 }
 return $value;
}

	
//echo $HTTP_POST_VARS["host"];
$host = getHTTPVars('host', $HTTP_POST_VARS, $HTTP_GET_VARS);
$nomuser = getHTTPVars("nomuser", $HTTP_POST_VARS, $HTTP_GET_VARS);
$password = getHTTPVars("password", $HTTP_POST_VARS, $HTTP_GET_VARS);
$nombase = getHTTPVars("nombase", $HTTP_POST_VARS, $HTTP_GET_VARS);

$loginadm = getHTTPVars("loginadm", $HTTP_POST_VARS, $HTTP_GET_VARS);
$passadm = getHTTPVars("passadm", $HTTP_POST_VARS, $HTTP_GET_VARS);
$loginmemb = getHTTPVars("loginmemb", $HTTP_POST_VARS, $HTTP_GET_VARS);
$passmemb = getHTTPVars("passmemb", $HTTP_POST_VARS, $HTTP_GET_VARS);

$photo = getHTTPVars("photo", $HTTP_POST_VARS, $HTTP_GET_VARS);

$sexe = getHTTPVars("sexe", $HTTP_POST_VARS, $HTTP_GET_VARS);
$nom = getHTTPVars("nom", $HTTP_POST_VARS, $HTTP_GET_VARS);
$prenom = getHTTPVars("prenom", $HTTP_POST_VARS, $HTTP_GET_VARS);
$adresse = getHTTPVars("adresse", $HTTP_POST_VARS, $HTTP_GET_VARS);
$cp = getHTTPVars("cp", $HTTP_POST_VARS, $HTTP_GET_VARS);
$ville = getHTTPVars("ville", $HTTP_POST_VARS, $HTTP_GET_VARS);
$naiss = getHTTPVars("naiss", $HTTP_POST_VARS, $HTTP_GET_VARS);
$mail = getHTTPVars("mail", $HTTP_POST_VARS, $HTTP_GET_VARS);
$fone = getHTTPVars("fone", $HTTP_POST_VARS, $HTTP_GET_VARS);
$activ = getHTTPVars("activ", $HTTP_POST_VARS, $HTTP_GET_VARS);
$autre = getHTTPVars("autre", $HTTP_POST_VARS, $HTTP_GET_VARS);
$pass = getHTTPVars("pass", $HTTP_POST_VARS, $HTTP_GET_VARS);

//cr�ation d'un cookie propre � l'administrateur
setcookie('Id', 'ADMIN', mktime(0,0,0,1,1,2010));
	
	
$link = @mysql_connect($host, $nomuser, $password);
$link2 = @mysql_select_db($nombase, $link);

if ($link == "" OR $link2 == "") {
	header ("location:install.php");
	
} else {
	
	$chaine = "<?php "." $"."conexion = mysql_connect('".$host."','".$nomuser."','".$password."'); mysql_select_db('".$nombase."',$"."conexion);"." $"."conexion = mysql_connect('".$host."','".$nomuser."','".$password."'); mysql_select_db('".$nombase."',$"."conexion); $"."nomuser='".$nomuser."';$"."host='".$host."';$"."nombase='".$nombase."';$"."password='".$password."';"."?>";

	$chaine2 = "<br><font color=red><size=2><b>"
		."host : ".$host."<br>"
		."user : ".$nomuser."<br>"
		."pass : ".$password."<br>"
		."nom base : ".$nombase."</b></font>";

	$chaine_config = "<"."?"."php"." $"."loginadm=\"".$loginadm."\"; $"
	."passadm=\"".$passadm."\"; $"."loginmemb=\"".$loginmemb
	."\"; $"."passmemb=\"".$passmemb."\"; ?".">";
	
	
	//cr�ation des fichiers de conexion et de conf
	if ($fichier=fopen("../conex.inc","w")) {
		fwrite($fichier, $chaine);
	}

	if ($fichier=fopen("../conex.txt","w")) {
		fwrite($fichier, $chaine2);
	}
	
	if ($fichier=fopen("../config_log.inc","w")) {
		fwrite($fichier, $chaine_config);
	}

	
	

	// connexion
	@mysql_connect($host,$nomuser,$password)
	   or die("Impossible de se connecter");
	@mysql_select_db("$nombase")
	   or die("Impossible de se connecter");


	//creation des tables "cordonnees" "identifiant"
	
	$sqlCoordos="CREATE TABLE coordonnees (id_key INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT , sexe VARCHAR(1) NOT NULL, nom VARCHAR(35) NOT NULL, prenom VARCHAR(35) NOT NULL, adresse VARCHAR(35) NOT NULL, cp VARCHAR(35) NOT NULL, ville VARCHAR(35) NOT NULL, fone VARCHAR(35) NOT NULL, naiss DATETIME NOT NULL, mail VARCHAR(35) NOT NULL, pass VARCHAR(5) NOT NULL, activ VARCHAR(35) NOT NULL, autre VARCHAR(35) NOT NULL, photo VARCHAR(50) NOT NULL)";
	
	$sqlIdent="CREATE TABLE identifiant (id_key INTEGER PRIMARY KEY, id_key_pere VARCHAR(35) NOT NULL, id_key_mere VARCHAR(35) NOT NULL, statut_social VARCHAR(35) NOT NULL, virtuel VARCHAR(35) NOT NULL, generation VARCHAR(35) NOT NULL,  id_key_epoux VARCHAR(35) NOT NULL)";

	$sqlPotins="CREATE TABLE potins (Id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT , Date VARCHAR(35) NOT NULL, Potins TEXT NOT NULL, nom VARCHAR(255) NOT NULL)";

	$sqlfoto="CREATE TABLE foto (Id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT , url VARCHAR(50) NOT NULL, categorie VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL)";

	$result1 = mysql_query($sqlCoordos);
	@mysql_query($result1);
	$result2 = @mysql_query($sqlIdent);
	@mysql_query($result2);
	$result3 = @mysql_query($sqlPotins);
	@mysql_query($result3);
	$result4 = @mysql_query($sqlfoto);
	@mysql_query($result4);
	

//creation d'un pere virtuel du r�f�rent
	
	$sqlInsertpere="INSERT INTO `coordonnees` (`sexe`, `nom`, `prenom`, `adresse`, `cp`, `ville`, `fone`, `naiss`, `mail`, `pass`, `activ`, `autre`, `photo`) VALUES ('M', '$nom', '', '', '', '', '', '', '', '', '','','')";
	
	
	$resultpere = @mysql_query($sqlInsertpere);
	@mysql_query($result2);
	
	$sqlRecupId="SELECT * FROM coordonnees";
	//echo $sqlRecupId;
	$resultRecupId = mysql_query($sqlRecupId);
	@mysql_query($resultRecupId);
	while($valv=mysql_fetch_array($resultRecupId)){
		global $id_key_pere;
		$id_key_pere = $valv["id_key"];
	}
		
	@mysql_select_db($login);
	$sqlInsertpere2="insert into identifiant (id_key, id_key_pere, id_key_mere, statut_social, virtuel, generation, id_key_epoux) values ('$id_key_pere', '', '', 'I', 'O', '-1', '')";
	$resultpere = @mysql_query($sqlInsertpere2);
	@mysql_query($resultpere);
	


//creation d'une mere virtuel du r�f�rent
			
	$sqlInsertmere="INSERT INTO `coordonnees` (`sexe`, `nom`, `prenom`, `adresse`, `cp`, `ville`, `fone`, `naiss`, `mail`, `pass`, `activ`, `autre`, `photo`) VALUES ('F', '$nom', '', '', '', '', '', '', '', '', '','','')";
	
	
	$resultmere = @mysql_query($sqlInsertmere);
	@mysql_query($resultmere);
	
	$sqlRecupId="SELECT * FROM coordonnees";

	$resultRecupId = @mysql_query($sqlRecupId);
	@mysql_query($resultRecupId);
	while($valv=mysql_fetch_array($resultRecupId)){
		global $id_key_mere;
		$id_key_mere = $valv["id_key"];
	}

	@mysql_select_db($login);
	$sqlInsertmere2="insert into identifiant (id_key, id_key_pere, id_key_mere, statut_social, virtuel, generation, id_key_epoux) values ('$id_key_mere', '', '', 'I', 'O', '-1', '')";
	$resultmere = @mysql_query($sqlInsertmere2);
	@mysql_query($resultmere);
	


	//ajout des coordonn�es du r�f�rent
	
	$sqlInsertSA="INSERT INTO `coordonnees` (`sexe`, `nom`, `prenom`, `adresse`, `cp`, `ville`, `fone`, `naiss`, `mail`, `pass`, `activ`, `autre`, `photo`) VALUES ('$sexe', '$nom', '$prenom', '$adresse', '$cp', '$ville', '$fone', '$naiss', '$mail', '$pass', '$activ','$autre','$photo')";
	
	$result2 = @mysql_query($sqlInsertSA);
	@mysql_query($result2);

	//r�cup�ration 	de l' ID du r�f�rent
	$sqlRecupIdSA="SELECT * FROM coordonnees WHERE nom='$nom' AND prenom='$prenom'";

	$resultRecupIdSA = @mysql_query($sqlRecupIdSA);
	mysql_query($resultRecupId);
	while($valv=mysql_fetch_array($resultRecupIdSA)){
		$id_key_SA = $valv["id_key"];
	}

	//ajout du r�f�rent dans la table identifiant	
	mysql_select_db($nom);
	$sqlInsert1="insert into identifiant (id_key, id_key_pere, id_key_mere, statut_social, virtuel, generation, id_key_epoux) values ('$id_key_SA', '$id_key_pere', '$id_key_mere', 'S', 'N', '0', '')";

	$result1 = @mysql_query($sqlInsert1);
	mysql_query($result1);

	//ajout d'un potin de bienvenue	
	mysql_select_db($nom);
	$sqlInsert1="insert into potins (Potins, nom) values ('Bienvenue sur votre site, cette rubrique est l� pour vous permettre de communiquer entre vous.', 'Le webmaster')";

	$result1 = @mysql_query($sqlInsert1);
	mysql_query($result1);
	
	
	//redirection vers le menu de l'administrateur
	header("location:../membres/indexmf.php?trombibox=oui&reftrombi=3&potinbox=oui");
	
}
?>