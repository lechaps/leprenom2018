<?php
session_start();
ini_set('display_errors','on');
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| PERSO 
|--------------------------------------------------------------------------
*/
defined('MIN_LENGTH_ANSWER')OR define('MIN_LENGTH_ANSWER'	, 3); 				// Nb de car min de la réponse
defined('MAX_LENGTH_ANSWER')OR define('MAX_LENGTH_ANSWER'	, 20); 				// Nb de car max de la réponse
defined('MAX_LENGTH_EMAIL') OR define('MAX_LENGTH_EMAIL'	, 50); 				// Nb de car max de l'email utilisateur
defined('MIN_LENGTH_WINNER')OR define('MIN_LENGTH_WINNER'	, 3); 				// Nb de car min du gagnant du fil
defined('MAX_LENGTH_WINNER')OR define('MAX_LENGTH_WINNER'	, 20); 				// Nb de car max du gagnant du fil
defined('REPONSE_OK') 		OR define('REPONSE_OK'			, 'REPONSE_OK'); 	// flag réponse OK, evènement de user_event
defined('REPONSE_KO')		OR define('REPONSE_KO'			, 'REPONSE_KO'); 	// flag réponse KO, evènement de user_event
defined('FLOOD_TIME')		OR define('FLOOD_TIME'			, 10); 				// On ne peux pas saisir deux réponse à 10 sec d'intervalle
defined('CAPTCHA')			OR define('CAPTCHA'				, false); 			// active/desactive le google Captcha Test
