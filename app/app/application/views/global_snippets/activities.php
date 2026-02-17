<?php

	if(isset($data)){
		//print_r($data);
		foreach($data as $activity_key => $activity_data){

			?><div class="activity_item <?php echo $activity_data['type']; ?>">
				<div class="title">
					<a href="<?php echo $activity_data['link']; ?>"><?php echo  $activity_data['label']; ?></a>: <?php echo $activity_data['short_message']; ?>
				</div>
				<span class="listDateItalic"><?php echo date("M j, g:ma",strtotime($activity_data['date_created'])); ?></span>                   
			</div>
			<?php
			
		}
	}
?>