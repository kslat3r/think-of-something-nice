<form action="<?php echo $Page->original_dir; ?>" method="post">
    <fieldset>
		<div>
			<label for="loginUsername" class="">Username</label>
        	<input type="text" id="loginUsername" name="loginUsername" value="" class="text " />
        </div>
        <div>
        	<label for="loginPassword" class="">Password</label>
        	<input type="password" id="loginPassword" name="loginPassword" value="" class="text " />
        </div>
        <div class="submit">
        	<input type="submit" name="submit" id="submit" value="Login" class="" />                	
        </div>            
    </fieldset>
	<div class="clear"></div>
</form>
