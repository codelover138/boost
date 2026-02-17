<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .table_header td {
            font-weight: bold;
            text-align: center;
        }
        .category-row td {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container-fluid reportsHeadingsContainer">
        <h2>Business Report</h2>
     
    </div>

 <table class="table listInvoiceTable altTable" style="font-weight:bold;">
            <tr class="table_header" class="client-revenue-row">
                <td style="text-align: left;">Report Period</td>
                <?php if(count($get_month_year_revenue) >0){
                    foreach($get_month_year_revenue as $key=>$value){
                        echo '<td style="text-align: center;">'.ucwords($value['period']).'</td>';
                    }
                } else{?>
                <td>Cloumn 1</td>
                <td>Cloumn 2</td>
                <td>Cloumn 3 <?php } ?>
                </td>
                <td style="text-align: center;">Total</td>
            </tr>
            <?php
            // Example placeholder for dynamic rows
            ?>
            <!-- Revenue Row -->
            <?php if (count($get_month_year_revenue) > 0) {
        echo '<tr class="list_item_row client-revenue-row" id="revenueRow">';
        echo '<td colspan="5" style="text-align: left;"> 
                <b>Income</b> 
              </td>
               
              </tr>';
    } ?>

            <!-- Expandable Client Revenue Row -->
            <?php if (!empty($get_month_year_client_revenue)) {
    foreach ($get_month_year_client_revenue as $index => $client) {
        $client_total = 0;
            $row_id = 'clientRevenueRow_' . $index; ?>
            <!-- Client Revenue Row -->
            <tr class="category-row" id="<?php echo $row_id; ?>">
                <td >
                    <?php echo htmlspecialchars($client['client_name']); ?>
                </td>
                <?php
        foreach ($client['data'] as $val) {
            $client_total += $val['net_revenue'];
            echo '<td  style="text-align: right;">' .  number_format($val['net_revenue']) . '</td>';
        }
        echo '<td  style="text-align: right;">' .  number_format($client_total) . '</td>';
        ?>
            </tr>
            <?php } } ?>


            <!-- showing revenue total -->
            <?php
            // Example placeholder for dynamic rows
            ?>
            <!-- Revenue Row -->
            <?php if (count($get_month_year_revenue) > 0) {
        echo '<tr class="list_item_row" style="background-color: lightgrey;">';
        echo '<td style="text-align: left;"> Total
              </td>';
        $total_revenue = 0;
        foreach ($get_month_year_revenue as $key => $value) {
            $total_revenue += $value['net_revenue'];
            echo '<td style="text-align: right;">' .  number_format($value['net_revenue']) . '</td>';
        }
        echo '<td style="text-align: right;">' .  number_format($total_revenue) . '</td>';
        echo '</tr>';
    } ?>

            <!-- showing revenue total -->



           


            <!-- expense row -->
            <?php if(count($get_month_year_expense) >0){?>
            <tr class="list_item_row" id="expenseRow">
                <?php echo '<td colspan="5" style="text-align: left;"> 
                <b>Expense</b>
              </td>
              </tr>
              ';
                 ?>
                <?php } ?>
                <!-- expense row -->

                <!-- Expandable category expense Row -->
                <?php if (!empty($get_month_year_client_expense)) {
    foreach ($get_month_year_client_expense as $index => $category) {
        $category_total = 0;
            $category_row_id = 'categoryRow_' . $index; ?>
                <!--expense Row -->
            <tr class="category-row" id="<?php echo $category_row_id; ?>">
               <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                <?php
        foreach ($category as $key=>$val) {
            if($category['category_name'] !== $category[$key]) $category_total += $category[$key];
            if($category['category_name'] !== $category[$key]) echo '<td  style="text-align: right;">' .  number_format($category[$key]) . '</td>';
        }
        echo '<td  style="text-align: right;">' .number_format($category_total) . '</td>';
        ?>
            </tr>
            <?php } } ?>

            <!-- expense row -->
            <?php if(count($get_month_year_expense) >0){?>
            <tr class="list_item_row" id="expenseRow" style="background-color: lightgrey;">
                <?php echo '<td style="text-align: left;"> 
                <b>Total</b>
              </td>';
                     $total_expense=0;
                    foreach($get_month_year_expense as $key=>$value){
                        $total_expense =$total_expense+$value['net_expense'];
                        echo '<td style="text-align: right;">'.number_format($value['net_expense'] ).'</td>';
                    }
                    echo '<td style="text-align: right;">'.number_format($total_expense).'</td>';
                 ?>

            </tr>
            <?php } ?>
            <!-- expense row -->


           

            <!-- total row -->
            <?php if (count($get_month_year_revenue) > 0 && count($get_month_year_expense) > 0) { 
                $expense_lookup = array_column($get_month_year_expense, 'net_expense', 'period');
                $differences = [];
                $total_revenue=0;
                $total_expense=0;
                $profit=0;
                foreach ($get_month_year_revenue as $revenue) {
                    $period = $revenue['period'];
                    $net_revenue = $revenue['net_revenue'];
                    $total_revenue=$total_revenue+$revenue['net_revenue'];
                    $net_expense = $expense_lookup[$period] ?? 0; 
                    $total_expense=$total_expense+$net_expense;
                    $differences[] = $net_revenue - $net_expense;
                }
                 $profit= $total_revenue - $total_expense;
                ?>
            <tr class="list_item_row">
                <td style="text-align: left;">Profit</td>
                <?php
                foreach($differences as $key=>$value){
                echo '<td style="text-align: right;">'.number_format($value ).'</td>';
                } ?>
                <td style="text-align: right;">
                    <?php echo number_format($profit); ?>
                </td>
            </tr>
            <?php    }      ?>
            <!-- total row -->

        </table>

</body>
</html>
