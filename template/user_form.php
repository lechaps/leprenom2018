<?php
require('../config/constants.php');
require('../config/database.php');

$db_Obj 	= BDD_Connection();
if (!(isset($_SESSION['long_id_BINGO']) and ($_SESSION['long_id_BINGO']!=''))) {
	header('Location: index.php');
	die();
}
$long_id=$_SESSION['long_id_BINGO'];
if (!checkWinnerWaiting($db_Obj, $long_id))
{
	header('Location: index.php');
	die();
}
unset($db_Obj);

$FORM_token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
$_SESSION['form_token'] = $FORM_token;
?>
<script type="text/javascript">
$(document).ready(function() {
	$('input#emailInput').characterCounter();
    $('#userForm').validate({
    	rules: {
        	emailInput: {
            	required	: true,
            	minlength	: 3,
            	email		: true,
            	maxlength	: <?php echo MAX_LENGTH_EMAIL ?>
            },
            pseudoInput	: {
            	required	: true,
            	minlength	: 3,
            	maxlength	: <?php echo MAX_LENGTH_WINNER ?>
        	}
		},
		submitHandler: function(form) {
			//jQuery("#submitBtn").hide();
			//event.preventDefault();
    		var jqxhr = $.post('register_engine.php', $('#userForm').serialize(), callBack)
    		.fail(function() {
    			alert( "FATAL ERROR Call LeChaps!!" );
    		})
    		function callBack(data){
    			$('#emailInput').focus();
    			switch(data) {
        			case 'ALREADY_USER':
        				text='Ce nom d\'utilisateur est déjà utilisé par un autre mail';
        				$('#pseudoInput').val('');
        				$('#pseudoInput').focus();
        				break;
        			case 'GOOD':
        				$("#my_form").load("./template/answer_form.php"); //chargement du formulaire avec une nouvelle énigme
        				$("footer").load("./template/footer.php"); 			//chargement du footer
        				break;
    				default:
    					text=data;	
    			}
    			Materialize.toast(text, 5000, 'rounded')
    		}	
		}
	});
});
</script>
<?php if (CAPTCHA) echo "<script src='https://www.google.com/recaptcha/api.js'></script>";?>
<span class="card-title">Bravo vous avez trouvé la bonne réponse</span>
<p>Vous marquez un point. Saisissez votre email et un pseudo. Si vous êtes déjà connu sous cet email vous ajouterez 
un point à votre score et mettez à jour votre pseudo. Dans le cas contraire vous ferez votre entrée au classement.</p>
<div class="row">
	<form id="userForm" name="userForm">
		<input type="hidden" name="token" id="token" value="<?php echo $FORM_token?>" />
		<input type="hidden" name="long_id" id="long_id" value="<?php echo $long_id?>" />
		<div class="input-field">
			<i class="material-icons prefix">contact_mail</i>
			<input id="emailInput" name="emailInput" type="text" class="validate" maxlength="<?php echo MAX_LENGTH_EMAIL?>" data-length="<?php echo MAX_LENGTH_EMAIL?>" size="<?php echoMAX_LENGTH_EMAIL?>" autofocus>
			<label for="contact_mail">Saisissez votre email</label>
		</div>
		<div class="input-field">	
			<i class="material-icons prefix">account_box</i>
			<input id="pseudoInput" name="pseudoInput" type="text" class="validate" maxlength="<?php echo MAX_LENGTH_WINNER?>" data-length="<?php echo MAX_LENGTH_WINNER?>" size="<?php echo MAX_LENGTH_WINNER?>">
			<label for="contact_mail">Pseudo (afficher dans le classement)</label>
		</div>
		<div class="input-field">
			<button class="btn waves-effect waves-light btn-large" type="submit" id="submitBtn">Envoyer<i class="material-icons right">send</i></button>
			<div id="captcha" class="g-recaptcha" data-sitekey="6LfKdh8TAAAAAIiwT4NrQkDUkND3Xp7DdTSRmlbn"></div>
		</div>
	</form>
</div>