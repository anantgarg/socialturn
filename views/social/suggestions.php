	<div class="row">
		<div class="col-sm-8">
			<h4>Suggestions</h4>
			<p>Running out of ideas? Some curated suggestions which you can use</p>
		</div>
		<div class="col-sm-4 pull-right">
			<div class="btn-group spacer">
				  <button type="button" class="btn btn-primary">Viewing <?php echo ucwords($currentSuggestion);?> Suggestions</button>
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="<?php echo BASE_URL;?>social/suggestions/<?php echo $current;?>">All</a></li>
					<?php foreach ($list as $item):?>
						<li><a href="<?php echo BASE_URL;?>social/suggestions/<?php echo $current;?>/<?php echo $item;?>"><?php echo ucwords($item);?></a></li>
					<?php endforeach;?>
				  </ul>
				</div>
		</div>
	</div>

	<?php if (!empty($suggestions)): foreach ($suggestions as $post):?>
	
	<form class="" action="<?php echo BASE_URL;?>social/post/<?php echo $current;?>" method="post">
	<div class="row spacer-sm queue post">
			<div class="col-xs-10">
				<h5 class="name"><?php echo $post['text'];?></h5>
				<?php if (!empty($post['media'])):?>
				<img class="media" src="<?php echo $post['media'];?>">
				<?php endif;?>
				<div class="scheduled">via @<?php echo $post['screen_name'];?></div>
			</div>
			<div class="col-xs-2 pull-right">
				<input type="hidden" value="<?php echo $post['id'];?>" name="suggestion_id">
				<input type="hidden" value="<?php echo $post['text'];?>" name="message">
				<input type="hidden" value="<?php echo $post['screen_name'];?>" name="screen_name">
				<input type="hidden" value="<?php echo $post['media'];?>" name="media">
				<button name="when" type="submit" value="now" class="btn btn-sm spacer-sm btn-primary confirm">Share Now</button>
				<button name="when" type="submit" value="queue" class="btn btn-xs spacer-xs btn-border" >Add to Queue</button>
			</div>
	</div>
	</form>
	<?php endforeach; endif;?>