<ul id="social">
	<li class="facebook">
		<fb:like href="http://<?php echo $Conf->http_host; ?>/thing/<?php echo $SelectedPost->id(); ?>" send="false" layout="button_count" width="" font="" show_faces="false"></fb:like>
	</li>	
	<li class="twitter">
		<a href="http://twitter.com/share" class="twitter-share-button" data-text="Think of Something Nice - <?php echo Util::excerpt_char($SelectedPost->somethingnice(), 50); ?>" data-count="horizontal">Tweet</a>
	</li>
	<li class="google">
		<g:plusone size="medium"></g:plusone>
	</li>
</ul>
<div class="clear">&nbsp;</div>
<div id="thing">
	<div class="name text">
		<h2>Name</h2>
		<p><?php echo $SelectedPost->name() == null ? '<em>Anonymous</em>' : Util::html($SelectedPost->name()); ?></p>
	</div>
	<div class="datecreated text">
		<h2>Date created</h2>
		<p><?php echo date('H:i d/m/Y', strtotime($SelectedPost->created_date())); ?></p>
	</div>
	<div class="somethingnice text">
		<h2>Something nice</h2>
		<p><?php echo nl2br(Util::html($SelectedPost->somethingnice())); ?></p>
	</div>
	<?php if (is_object($Upload)) { ?>
		<div class="upload text">
			<h2>Supplementary image</h2>
			<div class="polaroid <?php echo $SelectedPost->id() % 2 == 0 ? 'polaroid_left' : 'polaroid_right'; ?>">
				<img src="/assets/uploads/<?php echo $Upload->name(); ?>" alt="" />
			</div>
		</div>
	<?php } ?>
	<div class="tags text">
		<h2>Tags</h2>
		<p>
			<?php if (is_array($AllTags) && count($AllTags) > 0) { ?>
				<ul class="tags">
					<?php foreach ($AllTags as $Tag) { ?>
						<li><a href="/tag/<?php echo $Tag->value(); ?>"><?php echo $Tag->value(); ?></a></li>
					<?php } ?>				
				</ul>
			<?php }
			else { ?>
				
			<?php } ?>
		</p>
	</div>
	<div class="clear">&nbsp;</div>
</div>
