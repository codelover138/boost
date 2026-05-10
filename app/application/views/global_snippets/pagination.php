<ul class="pagination">
    
   <?php
   		
		if(isset($previous)){
			echo '<li><a href="'.base_url(reset($pages_links)).'"><</a></li>';
			echo '<li><a href="'.base_url($previous).'">Prev</a></li>';
		}
   		
   		foreach($pages_links as $key => $link){			
			if($key == $current_page_link_index){
				echo '<li class="active"><a href="'.base_url($link).'">'.($key+1).'</a></li>';
			}else{
				echo '<li><a href="'.base_url($link).'">'.($key+1).'</a></li>';
			}
		}
		
		if(isset($next)){			
			echo '<li><a href="'.base_url($next).'">Next</a></li>';
			echo '<li><a href="'.base_url(end($pages_links)).'">></a></li>';
		}
   ?>
</ul>