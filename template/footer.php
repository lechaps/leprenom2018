<?php
require('../config/database.php');
$db_Obj 	= BDD_Connection();
$classement = getClassement($db_Obj); //pseudo, score
$fils 	= getSolvedFils($db_Obj); //pseudo, score
unset($db_Obj);
$strclassement='';
$i=0;
if (empty($classement)) $flag=false; else $flag=true;
foreach ($classement as $rang => $val){
	$i++;
	$strclassement.='<tr><td>'.$i.'</td><td>'.$val[0].'</td><td>'.$val[1].'</td></tr>';
}

$strfils='';
$i=0;
foreach ($fils as $rang => $val){
	$i++;
	$strfils.='<tr><td>'.$i.'</td><td>'.$val[0].'</td><td>'.$val[1].'</td></tr>';
}

?>
<div class="container">
	<div class="row">
		<div class="col l6 m6 l6">
			<i class="large material-icons">assignment</i>
			<h5 class="white-text">Classement</h5>
			<?php if ($flag)
					echo '<table><thead><tr><th>#</th><th>Nom</th><th>Score</th></tr></thead><tbody>'.$strclassement.'</tbody></table>';
				else
					echo 'Aucune donnée pour le moment';?>
		</div>
		<div class="col l6 m6 l6">
			<i class="large material-icons">child_care</i>
			<h5 class="white-text">Enigme résolue</h5>
			<?php if ($flag)
					echo '<table><thead><tr><th>#</th><th>Nom</th><th>Réponse</th></tr></thead><tbody>'.$strfils.'</tbody></table>';
				else
					echo 'Aucune donnée pour le moment';?>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m12 l12">
			<i class="large material-icons">extension</i>
			<h5 class="white-text">Règlement</h5>
			<p class="grey-text text-lighten-4">Répondez correctement à l'énigme pour marquer un point et entrer au classement ou augmenter votre score</p>
		</div>
	</div>
</div>
<div class="footer-copyright">
	<div class="container">© 2017 Copyright LesPeigneurs <a class="grey-text text-lighten-4 right" href="https://www.facebook.com/TheChaps!">Le créateur</a></div>
</div>