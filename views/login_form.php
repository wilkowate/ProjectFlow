<?php

?>

<form action="processors/login.php" class="uniForm" method="post"> 
	<fieldset class="inlineLabels"> 
		<div class="ctrlHolder"> 
			<label for="login">Login*</label> 
			<input type="text" id="login" name="login" value="piotrek" size="35" class="textInput required" maxlength="30" /> 
		</div> 
		<div class="ctrlHolder"> 
			<label for="haslo">Hasło*</label> 
			<input type="password" id="password" name="password" value="piotrek" size="35" class="textInput required" maxlength="30" />  
		</div> 
		<div class="buttonHolder"> 
			<!--<a id="passwordForgot" href="#passwordReminder" rel="facebox">Forgot your password?</a>-->
			<button id="loginBtn" type="submit" class="primaryAction">Zaloguj się</button> 
		</div> 
	</fieldset> 
</form>		