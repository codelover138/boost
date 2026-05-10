<style>
    .category-row td:first-child {
        padding-left: 30px; /* Adjust the padding as needed */
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<div class="container-fluid bg-white doc-spaced">
    <div class="mainFilterContainer">
        <div class="container-fluid filters">
            <div class="filter_selection_container">
                <form id="businessReportForm" method="get" action="<?php echo base_url('reports/business_report'); ?>"
                    autocomplete="off">
                    <!-- Month Picker -->
                    <div class="form-group col-xs-12 col-sm-3 pull-left-sm">
                        <label for="month" class="control-label pull-left-sm">Report By Month</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs clear-left-sm">
                            <input type="month" class="form-control" name="month" id="month">
                        </div>
                    </div>

                    <!-- Year Picker -->
                    <div class="form-group col-xs-12 col-sm-3 pull-left-sm">
                        <label for="year" class="control-label pull-left-sm">Report By Year</label>
                        <div class="col-sm-12 pull-left-sm no-gutter-xs clear-left-sm">
                            <input type="text" class="form-control" name="year" id="year" placeholder="Select Year">
                        </div>
                    </div>


                    <!-- Buttons -->
                    <div style="text-align:right;" class="form_section container-fluid filter_buttons_right">
                        <button class="btn btn-success" type="submit">Generate Report</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>


    <?php if (count($get_month_year_revenue) > 0 && count($get_month_year_expense) > 0) { ?>


    <div class="container-fluid reportsHeadingsContainer">
        <h2>Business Report</h2>
        <h3><?php echo $company_data['company_name']; ?></h3>
        <div class="clearfix formSpacer"></div>
    </div>



    <!-- List Area -->
    <div class="container-fluid listInvoiceTable">
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
        echo '<td colspan="5" style="border: 1px solid lightgrey !important;"> 
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
                <td style="border: 1px solid lightgrey !important;" >
                    <?php echo htmlspecialchars($client['client_name']); ?>
                </td>
                <?php
        foreach ($client['data'] as $val) {
            $client_total += $val['net_revenue'];
            echo '<td  style="text-align: right; border: 1px solid lightgrey !important;">' .  number_format($val['net_revenue']) . '</td>';
        }
        echo '<td  style="text-align: right;border: 1px solid lightgrey !important;">' .  number_format($client_total) . '</td>';
        ?>
            </tr>
            <?php } } ?>


            <!-- showing revenue total -->
            <?php
            // Example placeholder for dynamic rows
            ?>
            <!-- Revenue Row -->
            <?php if (count($get_month_year_revenue) > 0) {
        echo '<tr class="list_item_row"  style="background-color: lightgrey;">';
        echo '<td style="text-align: left;"> Total
              </td>';
        $total_revenue = 0;
        foreach ($get_month_year_revenue as $key => $value) {
            $total_revenue += $value['net_revenue'];
            echo '<td style="text-align: right;border: 1px solid lightgrey !important;">' .  number_format($value['net_revenue']) . '</td>';
        }
        echo '<td style="text-align: right;border: 1px solid lightgrey !important;">' .  number_format($total_revenue) . '</td>';
        echo '</tr>';
    } ?>

            <!-- showing revenue total -->


            <!-- expense row -->
            <?php if(count($get_month_year_expense) >0){?>
            <tr class="list_item_row" id="expenseRow">
                <?php echo '<td colspan="5" style="text-align: left;border: 1px solid lightgrey !important;"> 
                <b>Expenses</b>
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
               <td style="border: 1px solid lightgrey !important;"><?php echo htmlspecialchars($category['category_name']); ?></td>
                <?php
        foreach ($category as $key=>$val) {
            if($category['category_name'] !== $category[$key]) $category_total += $category[$key];
            if($category['category_name'] !== $category[$key]) echo '<td  style="text-align: right;border: 1px solid lightgrey !important;">' .  number_format($category[$key]) . '</td>';
        }
        echo '<td  style="text-align: right;border: 1px solid lightgrey !important;">' .number_format($category_total) . '</td>';
        ?>
            </tr>
            <?php } } ?>

            <!-- expense row -->
            <?php if(count($get_month_year_expense) >0){?>
            <tr class="list_item_row" id="expenseRow"  style="background-color: lightgrey;">
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
            <tr class="list_item_row" >
                <td style="text-align: left;"><b>Profit</b></td>
                <?php
                foreach($differences as $key=>$value){
                echo '<td style="text-align: right;"><b>'.number_format($value ).'</b></td>';
                } ?>
                <td style="text-align: right;">
                   <b> <?php echo number_format($profit); ?></b>
                </td>
            </tr>
            <?php    }      ?>
            <!-- total row -->

        </table>
    </div>

    <!-- List Area -->



    <div style="text-align:right;" class="form_section container-fluid filter_buttons_right">
        <button type="button" class="btn btn-info"
            style="margin-left: 1px; margin-right: 1px;" onclick="printReport()">Print
            Report</button>
        <ul class="nav nav-pills pull-right">
            <li class="dropdown" role="presentation">
                <button aria-expanded="true" role="button" aria-haspopup="true" data-toggle="dropdown"
                    class="btn btn-success pull-right" type="button" href="#">
                    Export Report
                </button>
                <ul role="menu" class="dropdown-menu right-aligned-arrow">
                     <li> <a href="#" id="downloadExcel" <i class="fas fa-file-excel"></i> <b style="font-family: sans-serif;">Export Excel</b></a></li>
                     
                     <li> <a href="#" id="downloadPdf"> <i class="fas fa-file-pdf"></i> <b  style="font-family: sans-serif;">Export PDF</b></a></li>
                </ul>
            </li>
        </ul>
    </div>

    <?php }?>
    <div class="clearfix"><br></div>
</div>

<!-- JavaScript to Deselect Inputs and Prevent Default Submission -->
<script>
document.getElementById('month').addEventListener('change', function() {
    document.getElementById('year').value = '';
});

document.getElementById('year').addEventListener('input', function() {
    document.getElementById('month').value = '';
});

document.getElementById('year').addEventListener('change', function() {
    document.getElementById('month').value = '';
});
// Prevent form submission by default
document.getElementById('businessReportForm').addEventListener('submit', function(event) {
    event.preventDefault();
    // Add any conditions to allow submission
    if (validateForm()) {
        this.submit(); // Manually submit the form if validation passes
    }
});

// Example validation function (modify as needed)
function validateForm() {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;

    if (!month && !year) {
        alert('Please select either a month or a year.');
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('downloadExcel').addEventListener('click', function() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        if (!month && !year) {
            alert('Please select either a month or a year.');
            return false;
        }
        let url = '<?php echo base_url("reports/download/excel"); ?>';
        if (month || year) {
            url += '?month=' + encodeURIComponent(month) + '&year=' + encodeURIComponent(year);
        }
        window.location.href = url;
    });
})

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('downloadPdf').addEventListener('click', function() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        if (!month && !year) {
            alert('Please select either a month or a year.');
            return false;
        }
        let url = '<?php echo base_url("reports/download/pdf"); ?>';
        if (month || year) {
            url += '?month=' + encodeURIComponent(month) + '&year=' + encodeURIComponent(year);
        }
        window.location.href = url;
    });
})

function toggleClientRevenue() {
    var clientRows = document.querySelectorAll(
        '[id^="clientRevenueRow_"]');
    var icon = document.querySelector('#revenueRow td span');
    var shouldShow = Array.from(clientRows).some(row => row.style.display === 'none' || row.style.display === '');
    clientRows.forEach(row => {
        row.style.display = shouldShow ? 'table-row' : 'none';
    });
    icon.innerHTML = shouldShow ? '&#x25B2;' : '&#x25BC;';
}

function toggleCategoryExpense() {
    var rows = document.querySelectorAll(
        '[id^="categoryRow_"]');
    var icon = document.querySelector('#expenseRow td span');
    var shouldShow = Array.from(rows).some(row => row.style.display === 'none' || row.style.display === '');
    rows.forEach(row => {
        row.style.display = shouldShow ? 'table-row' : 'none';
    });
    icon.innerHTML = shouldShow ? '&#x25B2;' : '&#x25BC;';
}

function printReport() {
    // Get the table container you want to print
    var contentToPrint = document.querySelector('.listInvoiceTable').outerHTML;

    // Create a new window to print content
    var printWindow = window.open('', '', 'width=800,height=600');

    // Define the content style (you can style the print layout here if needed)
    var styles = `
        <style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 8px; text-align: center; border: 1px solid #ddd; }
            h2, h3 { text-align: center; }
        </style>
    `;

    // Write the content to the new window
    printWindow.document.write('<html><head>' + styles + '</head><body>');
    printWindow.document.write('<h2>Business Report</h2>');
    printWindow.document.write('<h3>' + document.querySelector('.reportsHeadingsContainer h3').textContent + '</h3>');
    printWindow.document.write(contentToPrint); // Insert the table content
    printWindow.document.write('</body></html>');

    // Wait for the content to load and then print
    printWindow.document.close(); // Needed for IE
    printWindow.focus(); // Needed for IE
    printWindow.print();
    printWindow.close(); // Close the print window after printing
}


// Initialize Bootstrap Datepicker for Year Picker
$(document).ready(function() {
    let year = '<?php echo $year; ?>';
    let month = '<?php echo $month; ?>';
    const currentMonth = new Date().toISOString().slice(0, 7);
    if(month) document.getElementById('month').value = month;
    else if(year) document.getElementById('year').value = year;
    else document.getElementById('month').value = currentMonth;

    $('#year').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    }).on('changeDate', function() {
        console.log('Datepicker year changed');
        $('#month').val(''); // Use jQuery to clear the month input
    });
});
</script>