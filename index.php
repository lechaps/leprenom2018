<?php
//SITE POUR FAIRE DEVINER LE PRENOM
header('Content-Type: text/html; charset=UTF-8');
require('./config/constants.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Les Peigneurs</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="./library/materialize/css/materialize.min.css"  media="screen,projection"/>
<link rel="stylesheet" type="text/css" media="screen" href="./library/perso.css">
<script type="text/javascript" src="./library/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="./library/materialize/js/materialize.min.js"></script>
<script type="text/javascript" src="./library/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="./library/perso.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("footer").load("./template/footer.php"); 			//chargement du footer
	$("#my_form").load("./template/answer_form.php");	//chargement du formulaire
});
</script>
</head>
<body>
<div class="navbar-fixed">
	<nav class="nav-extended">
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">&nbsp;Les Peigneurs</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">Tentez de découvrir les énigmes du Chaps&nbsp;</ul>
		</div>
	</nav>
</div>
<br>
<div class="container">
	<div class="row valign-wrapper">
        <div class="col s14">
        	<div class="card blue darken-1">
        		<div class="card-content white-text" id="my_form"></div>
          	</div>
        </div>
    </div>
</div>
<footer class="page-footer">
</footer>
</body>
</html>
