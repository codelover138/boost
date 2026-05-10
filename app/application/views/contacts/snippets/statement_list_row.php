<?php
	if($document_number != 'unrecognised'){
?>

         <tr class="list_item_row">
            <td class="greyed"><?php echo $date_created; ?></td>
            <td>
            	<?php if($link_section !== false){ ?>
                    <a class="greyLink" href="<?php echo base_url($link_section.'/'.$id); ?>">
                        #<?php echo $document_number; ?>
                    </a>
                <?php }else{ ?>
               			#<?php echo $document_number; ?>
                <?php } ?>
            </td>
            <td><?php echo $debt; ?></td>
            <td><?php echo $paid; ?></td> 
            <td><?php echo $running_balance; ?></td>                    
         </tr>
 
 <?php
	}
 ?>
