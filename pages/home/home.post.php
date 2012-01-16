<form id="niceForm" method="post" action="/home" enctype="multipart/form-data">
	<fieldset class="<?php echo ($Posts->has_posted($Session) || (isset($CurrentUser) && $CurrentUser != false)) ? 'small' : ''; ?>">
		<div>
			<div class="your_name_cont">
				<label for="name">Your name</label>
			</div>
			<span class="or">-or-</span>
			<div class="anon_cont">
				<input type="checkbox" name="remain_anon" id="remain_anon" value="1" class="checkbox"<?php echo $ThingForm->get_value('remain_anon') == true ? ' checked="checked"' : ''; ?> />
				<label for="remain_anon">remain anonymous</label>
			</div>
			<div class="clear">&nbsp;</div>
			<input type="text" name="name" id="name" value="<?php echo Util::html($ThingForm->get_value('name')); ?>" class="text" />			
		</div>
		<div>
			<label for="somethingnice">Your something nice</label>
			<textarea name="somethingnice" id="somethingnice" rows="5" cols="30"><?php echo Util::html($ThingForm->get_value('somethingnice')); ?></textarea>
		</div>
		<div>
			<label for="upload">Add a supplementary image</label>
			<div class="clear">&nbsp;</div>
			<input type="file" name="upload" id="upload" class="file" />
			<div class="clear">&nbsp;</div>
			<span class="annotation">JPEG, GIF, PNG file formats accepted. Max. 1MB in size.</span>
		</div>
		<?php if ($Session->get('human') == false) { ?>
			<div>
				<label id="areyouhuman">Are you human?</label>
				<div class="clear">&nbsp;</div>
				<?php echo recaptcha_get_html($Conf->recaptcha_public_key); ?>
			</div>
		<?php } ?>
		<hr/>
		<div class="submit">
			<input type="submit" name="submit" value="Submit" class="submit" title="Send"/>
			<input type="hidden" name="postedcheck" value="ThingForm" />		
		</div>		
	</fieldset>		
</form>
<?php if ($Posts->has_posted($Session) || (isset($CurrentUser) && $CurrentUser != false)) { ?>
	<div class="sidebar">	
		<div id="tag_cloud">
			<h2>Tag cloud</h2>
			<?php echo $Tags->get_tag_cloud(); ?>		
		</div>	
		<div id="contact">
			<h2>On the web</h2>
			<a href="http://www.twitter.com/onlynicethings"><img src="/assets/images/twitter.png" alt="Follow us on Twitter" /></a>		
		</div>
	</div>
	<div class="clear">&nbsp;</div>
<?php } ?>