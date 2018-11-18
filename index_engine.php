<?php
header('Content-Type: text/html; charset=UTF-8');
require('./config/constants.php');
require('./config/database.php');
require('./config/function.php');
$db_Obj     = BDD_Connection();

//Récupération des données postées
$FORM_token		= $_POST["token"];
$proposition	= trim($_POST["answerInput"]);
$proposition	= ucwords(mb_strtolower($proposition), ' -');
$long_id        = trim($_POST["long_id"]);

//Check Robot
if(CAPTCHA and !isRecaptchaValid($_POST['g-recaptcha-response'])) die('ROBOT');

//Check conformité du format de la réponse
if (empty($proposition))  die('EMPTY_ANSWER');
if (strlen($proposition)>MAX_LENGTH_ANSWER)  die('TOO_LONG_ANSWER');
if (strlen($proposition)<MIN_LENGTH_ANSWER)  die('TOO_SHORT_ANSWER');

//Check la provenance du formulaire
if ($FORM_token!=$_SESSION['form_token']) die('UNKNOWN_FORM');

//Check fils tenter = fils en cours
$fils = getCurrentFils($db_Obj);
if ($long_id!=$fils['long_id']) die('NOT_CURRENT'); else $idfil = $fils['id'];

//Check si le long id est renseigné
if (!(isset($long_id) and ($long_id!=''))) die('LONGID_NOT_FOUND -> STUPID Lechaps');
 
//AntiBruteForce (1 réponse toute les xx secondes)
if (getBruceForceFlag($db_Obj)) die('BRUTE_FORCE');

//CheckReponse
$reponse = getAnswer($db_Obj, $idfil);
$reponse= ucwords(mb_strtolower($reponse));
if (strcasecmp($proposition, $reponse)==0) {
	$event=REPONSE_OK;
	setClosedFil($db_Obj, $idfil);
	$_SESSION['long_id_BINGO']=$long_id;
} else {
	$event=REPONSE_KO;
}

//Register tentative
setFilsEvent($db_Obj, $idfil, $event, 'réponse proposée : '.$proposition);

//Aiguillage Reponse + Retour
die($event);
unset($db_Obj);
?>