<?php if (count($AllPosts) > 0) { ?>
	<form name="moderate_form" method="post" action="/admin" id="moderate_form">
		<fieldset>
			<div>
				<table>
					<tr>
						<th>Public?</th>						
						<th>Name</th>
						<th>Date created</th>
						<th>Content</th>
						<th>Tags <small>(comma-seperated)</small></th>
						<th>Delete?</th>
					</tr>	
					<?php foreach ($AllPosts as $Post) { ?>
						
						<?php
						
							//get upload
							
							$data = array();
							$data['post_id'] = $Post->id();
	
							$Upload = $Uploads->find($data, 1);
							
						?>
						
						<tr>
							<td><input type="checkbox" name="moderate[]" value="<?php echo $Post->id(); ?>"<?php echo $Post->moderated() == true ? ' checked="checked"' : ''; ?> /></td>							
							<td><?php echo Util::excerpt_char($Post->name() != null ? Util::html($Post->name()) : '<em>Anonymous</em>', 30); ?></td>
							<td><a href="/admin/thing/<?php echo $Post->id(); ?>"><?php echo date('H:i d/m/Y', strtotime($Post->created_date())); ?></a></td>
							<td><?php echo is_object($Upload) ? '<img src="/assets/images/paperclip.png" alt="" />&nbsp;' : ''; ?><?php echo Util::excerpt_char(Util::html($Post->somethingnice()), 50); ?></td>
							<td><input type="text" name="tags[]" value="<?php echo $Tags->get_tags_list($Post); ?>" /></td>
							<td><input type="checkbox" name="delete[]" value="<?php echo $Post->id(); ?>" /></td>
						</tr>			
					<?php } ?>	
				</table>
			</div>
			<div class="submit">
				<input type="submit" name="submit" value="Submit" class="submit" title="Submit"/>				
				<input type="hidden" name="postedcheck" value="ModerateForm" />		
			</div>
		</fieldset>
	</form>
<?php }
else { ?>
	<em>No nice things</em>
<?php } ?>