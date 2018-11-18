<?php
// UPDATE`fils` set long_id=(substring(md5(rand()) FROM 1 FOR 16)) WHERE long_id is null;

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/
function BDD_Connection()   {
	try {
		$strConnection 	= 'mysql:host=localhost;dbname=##DB_NAME##'; 
		$arrExtraParam	= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); //alternative de $pdo->query("SET NAMES 'utf8'"); 
		$pdo 			= new PDO($strConnection, '##DB_USER##', '##DB_PWD##', $arrExtraParam);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		$msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
		die($msg);
	}
	return $pdo;
}

/*
| -------------------------------------------------------------------
| GET THE CURRENT FILS
| -------------------------------------------------------------------
*/
function getCurrentFils($connection)   {
	$reponse = $connection->query('SELECT id, long_id, titre, libelle FROM fils WHERE dtfin is null ORDER BY id LIMIT 1');
	return $reponse->fetch();
}

/*
| -------------------------------------------------------------------
| GET THE FLAG OF THE BRUTE FORCE DETECTION
| -------------------------------------------------------------------
*/
function getBruceForceFlag($connection)   {
	$sql = 'SELECT distinct 1 as flag FROM `fils_event` WHERE session_id=:session_id and ADDDATE(dateevent, INTERVAL '.FLOOD_TIME.' SECOND) > now()';
	$sth = $connection->prepare($sql);
	$sth->execute(array(':session_id' => session_id()));
	$result=$sth->fetch();
	if ($result[0]) return true; else return false;
}

/*
| -------------------------------------------------------------------
| SET THE EVENT OF THE ANSWER
| -------------------------------------------------------------------
*/
function setFilsEvent($connection, $idf, $event, $cmt)   {
	$session_id=session_id();
	$sql = 'INSERT INTO `fils_event`(idfil, session_id, event, cmtevent) VALUES (:idfil, :session_id, :event, :cmtevent)';
	$sth = $connection->prepare($sql);
	$sth->bindParam(':idfil',		$idf);
	$sth->bindParam(':session_id',	$session_id);
	$sth->bindParam(':event', 		$event);
	$sth->bindParam(':cmtevent', 	$cmt);
	$sth->execute();
	return;
}

/*
| -------------------------------------------------------------------
| GET THE ANSWER OF A FIL
| -------------------------------------------------------------------
*/
function getAnswer($connection, $id)   {
	$sql = 'SELECT reponse FROM `fils` WHERE id=:id';
	$sth = $connection->prepare($sql);
	$sth->execute(array(':id' => $id));
	$result=$sth->fetch();
	return $result[0];
}

/*
| -------------------------------------------------------------------
| CLOSED THE ANSWERED FIL 
| -------------------------------------------------------------------
*/
function setClosedFil($connection, $id)   {
	$sql = 'UPDATE `fils` set dtfin=NOW() WHERE id=:idfil';
	$sth = $connection->prepare($sql);
	$sth->bindParam(':idfil',		$id);
	$sth->execute();
	$id	 = getCurrentFils($connection)['id'];
	$sql = 'UPDATE `fils` set dtdebut=NOW() WHERE id=:idfil';
	$sth = $connection->prepare($sql);
	$sth->bindParam(':idfil',		$id);
	$sth->execute();
	return;
}

/*
| -------------------------------------------------------------------
| CHECK THE ANSWERED ID NEED A USER REGISTRATION
| -------------------------------------------------------------------
*/
function checkWinnerWaiting($connection, $id)   {
$sql = 'SELECT distinct 1 as flag FROM `fils` F WHERE long_id=:id and dtfin is not null 
		AND EXISTS (SELECT 1 FROM `fils_event` FE WHERE idfil=F.id AND event=:event 
			AND NOT EXISTS(SELECT 1 FROM `winner` WHERE id_event=FE.id) )';
	$event=REPONSE_OK;
	$sth = $connection->prepare($sql);
	$sth->execute(array(':id' => $id, ':event' => $event));
	$result=$sth->fetch();
	if ($result[0]) return true; else return false;
}

/*
| -------------------------------------------------------------------
| CHECK THE DISPONIBILITY OF THE PSEUDO
| -------------------------------------------------------------------
*/
function availablePseudo($connection, $pseudo, $email) {
$sql = 'SELECT distinct 1 as flag FROM `winner` WHERE pseudo=:pseudo and email!=:email';
	$sth = $connection->prepare($sql);
	$sth->execute(array(':pseudo' => $pseudo, ':email' => $email));
	$result=$sth->fetch();
	if ($result[0]) return false; else return true;
}

/*
| -------------------------------------------------------------------
| CLOSED THE ANSWERED FIL 
| -------------------------------------------------------------------
*/
function setWinner($connection, $mail, $pseudo, $id)   {
	$event=REPONSE_OK;
	$sql = 'INSERT INTO `winner`(id_event, email, pseudo) 
				(SELECT id, :email, :pseudo FROM `fils_event` WHERE idfil=(SELECT id FROM `fils` WHERE long_id=:long_id) AND event=:event)';
	$sth = $connection->prepare($sql);
	$sth->bindParam(':email',		$mail);
	$sth->bindParam(':pseudo',		$pseudo);
	$sth->bindParam(':long_id', 	$id);
	$sth->bindParam(':event', 		$event);
	$sth->execute();
	//$sql = 'UPDATE `winner` set pseudo=:pseudo) WHERE email=:email';
	//$sth = $connection->prepare($sql);
	//$sth->bindParam(':pseudo',		$pseudo);
	//$sth->bindParam('email',		$email);
	//$sth->execute();
	return;
}

/*
| -------------------------------------------------------------------
| GET CLASSEMENT
| -------------------------------------------------------------------
*/
function getClassement($connection)   {
	$reponse = $connection->query('SELECT pseudo, count(distinct id) as score FROM `winner` GROUP BY pseudo ORDER BY score desc, pseudo');
	return $reponse->fetchall();
}

/*
| -------------------------------------------------------------------
| GET CLASSEMENT
| -------------------------------------------------------------------
*/
function getSolvedFils($connection)   {
	$reponse = $connection->query('SELECT pseudo, reponse FROM `winner` W LEFT OUTER JOIN `fils_event` FE ON W.id_event=FE.id LEFT OUTER JOIN `fils` F ON FE.idfil=F.id ORDER BY F.id');
	return $reponse->fetchall();
}





