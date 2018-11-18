<?php
header('Content-Type: text/html; charset=UTF-8');
require('./config/constants.php');
require('./config/database.php');
require('./config/function.php');
$db_Obj     = BDD_Connection();

//Récupération des données postées
$FORM_token			= $_POST["token"];
$emailInput			= mb_strtolower(trim($_POST["emailInput"]));
$pseudoInput		= trim($_POST["pseudoInput"]);
$long_id        	= trim($_POST["long_id"]);

//Check Robot
if(CAPTCHA and !isRecaptchaValid($_POST['g-recaptcha-response'])) die('ROBOT');

//Check la provenance du formulaire
if ($FORM_token!=$_SESSION['form_token']) die('UNKNOWN_FORM');

//Check le format du mail
if (strlen($emailInput)>MAX_LENGTH_EMAIL) die('TOO_LONG_MAIL');
if ($emailInput=='') die('EMPTY_MAIL');
if (!filter_var($emailInput, FILTER_VALIDATE_EMAIL)) die('INVALID_EMAIL_FORMAT');

//Check le format du pseudo
if (strlen($pseudoInput)>MAX_LENGTH_WINNER) die('TOO_LONG_PSEUDO');
if (strlen($pseudoInput)<MIN_LENGTH_WINNER) die('TOO_SHORT_PSEUDO');
if ($pseudoInput=='') die('EMPTY_PSEUDO');

//Check la disponibilité du pseudo en dehors du mail saisie
if (!availablePseudo($db_Obj, $pseudoInput, $emailInput)) die('ALREADY_USER');

//Register
setWinner($db_Obj, $emailInput, $pseudoInput, $long_id);

unset($_SESSION['long_id_BINGO']);
die('GOOD');
unset($db_Obj);
?>