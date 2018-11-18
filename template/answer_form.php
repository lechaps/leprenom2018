<?php
require('../config/constants.php');
require('../config/database.php');

$db_Obj 	= BDD_Connection();
$fils 		= getCurrentFils($db_Obj); //id, long_id, titre, libelle
unset($db_Obj);

$FORM_token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
$_SESSION['form_token'] = $FORM_token;

/*
<div class="row valign-wrapper">
	<div class="col s14">
		<div class="card">
			<div class="card-image waves-effect waves-block waves-light">
				<img class="activator" src="http://materializecss.com/images/office.jpg">
			</div>
			<div class="card-content">
				<span class="card-title activator grey-text text-darken-4">Vous cherchez un prénom
					<i class="material-icons right">more_vert</i>
				</span>
				<div class="row">
					<form action="index.php" type="POST">
						<div class="input-field col">
							<i class="material-icons prefix">create</i>
							<input id="input_reponse" type="text" class="validate" placeholder="Libellé de votre réponse" maxlength="20" data-length="20">
							<label for="icon_prefix">Votre réponse</label>
							<button class="btn waves-effect waves-light" type="submit" name="action">Envoyer
								<i class="material-icons right">send</i>
							</button>
						</div>
					</form>
				</div>
			</div>
			<div class="card-reveal">
				<span class="card-title grey-text text-darken-4">Vous cherchez un prénom
					<i class="material-icons right">close</i>
				</span>
				<p>Encore plus d'information sur l'énigme</p>
		  </div>
		</div>
	</div>
</div>
*/


?>
<script type="text/javascript">
var libelle_reponseKO = ['Mauvaise réponse', 
						'Non je regrette ce n\'est pas ça', 
						'Oublions cette proposition...', 
						'Non, Non, il ne faut pas exagérer quand même',
						'Retentez votre chance...', 
						'On peut se tutoyer...',
						'Pas loin...non je rigole j\'en sais rien mais une chose sûr c\'est que ce n\'est pas ça' ];

$(document).ready(function() {
	$('input#answerInput').characterCounter();
	$('#answerForm').validate({
    	rules: {
        	answerInput:{
            	required	: true,
            	minlength	: <?php echo MIN_LENGTH_ANSWER ?>,
            	maxlength	: <?php echo MAX_LENGTH_ANSWER ?>
        	}
		},
		submitHandler: function(form) {
			//jQuery("#submitBtn").hide();
			//event.preventDefault();
    		var jqxhr = $.post( 'index_engine.php', $('#answerForm').serialize(), callBack)
    		.fail(function() {
    			alert( "FATAL ERROR Call LeChaps!!" );
    		})
    		function callBack(data){
    			$('#answerInput').val('');
    			$('#answerInput').focus();
    			switch(data) {
    				case 'REPONSE_KO':
    					text=libelle_reponseKO[Math.floor(Math.random() * libelle_reponseKO.length)];
        				break;
    				case 'REPONSE_OK':
    					$("#my_form").load("./template/user_form.php"); //chargement du formulaire pour identifier le gagnant
        				break;
        			case 'BRUTE_FORCE':
        				text='Respectez <?php echo FLOOD_TIME ?> secondes entre deux tentatives';
        				break;
        			case 'ROBOT':
        				text='Prouvez que vous n\'êtes pas un robot en cochant la case';
        				break;
        			case 'NOT_CURRENT':
        				text='Dommage quelqu\'un d\'autre à répondu correctement a cette énigme...changeons';
        				$("#my_form").load("./template/answer_form.php");	//chargement du formulaire avec une nouvelle énigme
        				$("footer").load("./template/footer.php"); 			//chargement du footer
        				break;
    				default:
    					text=data;	
    			}
    			$('#captcha').html('');
    			<?php if (CAPTCHA) echo '$.getScript("https://www.google.com/recaptcha/api.js");';?>
    			Materialize.toast(text, 10000, 'rounded')
    		}	
		}
	});
});
</script>
<?php if (CAPTCHA) echo "<script src='https://www.google.com/recaptcha/api.js'></script>";?>
<span class="card-title"><?php echo $fils['titre'] ?> : </span>
<p><?php echo $fils['libelle'] ?></p>
<div class="row">
	<form id="answerForm" name="answerForm">
		<input type="hidden" name="token" id="token" value="<?php echo $FORM_token?>" />
		<input type="hidden" name="long_id" id="long_id" value="<?php echo $fils['long_id']?>" />
		<div class="input-field">
			<i class="material-icons prefix">create</i>
			<input id="answerInput" name="answerInput" type="text" class="validate" maxlength="<?php echo MAX_LENGTH_ANSWER?>" data-length="<?php echo MAX_LENGTH_ANSWER?>" size="<?php echo MAX_LENGTH_ANSWER?>" autofocus>
			<label for="icon_prefix">Votre réponse</label>
		</div>
		<button class="btn waves-effect waves-light btn-large" type="submit" id="submitBtn">Envoyer<i class="material-icons right">send</i></button>
		<div id="captcha" class="g-recaptcha" data-sitekey="6LfKdh8TAAAAAIiwT4NrQkDUkND3Xp7DdTSRmlbn"></div>
	</form>
</div>