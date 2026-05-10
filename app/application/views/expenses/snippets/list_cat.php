
  <tr>
    <td colspan="6" class="section_heading"><?php echo $category_name; ?></td>                  
  </tr>
  <tr class="table_break">
    <td colspan="6"></td>                 
  </tr>
  <tr class="table_spacer">
    <td colspan="6"></td>                 
  </tr>
  
  <?php
  foreach($expenses as $expense_key => $expense_data){
	  //var_dump($expense_data);
  ?>
  
      <tr class="list_item_row">
        <td class="greyed"><?php echo date("d/m/Y",strtotime($expense_data['date'])); ?></td>
        <td>
            <a href="<?php echo base_url('contacts/'.$expense_data['supplier_id']); ?>"><?php echo $expense_data['vendour_name']; ?></a>
        </td>
        <td>
            <a href="<?php echo base_url('contacts/'.$expense_data['contact_id']); ?>"><?php echo $expense_data['client_name']; ?></a>
        </td> 
        <td>
            <?php echo ucwords($expense_data['author_first_name'].' '.$expense_data['author_last_name']); ?>
        </td>
        <td>
            <?php echo $expense_data['notes']; ?>
        </td>
        <td class="text-right greyed">
            <?php echo number_format($expense_data['total_amount'],2,'.',','); ?>
        </td>                  
      </tr>            
  
  <?php
  }
  ?>
       
  <tr class="table_spacer">
    <td colspan="6"></td>                 
  </tr>
  <tr class="table_break">
    <td colspan="6"></td>                 
  </tr>
  <tr class="table_totals">
    <td class="section_heading">Total</td>
    <td colspan="5" class="section_heading text-right"><?php echo $expense_data['currency_symbol'].number_format($total_amount,2,'.',','); ?></td>                 
  </tr>
  <tr class="table_break">
    <td colspan="6"></td>                 
  </tr>
   <tr  class="table_spacer large">
    <td colspan="6"></td>                 
  </tr>