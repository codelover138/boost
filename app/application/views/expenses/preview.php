<?php
	$expense_data = array_values($request['data'])[0];
	$id = $expense_data['id'];
	$doc_date = date("d M Y", strtotime($expense_data['date']));
	$amount = $expense_data['currency_symbol'] . number_format($expense_data['total_amount'], 2, '.', ',');
?>

<!-- content area -->
<div class="container-fluid bg-white doc-spaced">
    <div class="col-xs-12">
        <h3 class="minimise-margin-bottom">Expense Details</h3>
        <div class="clearfix grey-border-bottom form-group"></div>
    </div>

    <div class="form_section">
        <div class="col-xs-12 col-sm-6">
            <table class="table table-condensed preview-details-table">
                <tr>
                    <td><strong>Date</strong></td>
                    <td><?php echo $doc_date; ?></td>
                </tr>
                <tr>
                    <td><strong>Vendor</strong></td>
                    <td><?php echo htmlspecialchars($expense_data['vendour_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Amount</strong></td>
                    <td><?php echo $amount; ?></td>
                </tr>
                <tr>
                    <td><strong>Category</strong></td>
                    <td><?php echo htmlspecialchars($expense_data['category_name']); ?></td>
                </tr>
            </table>
        </div>

        <div class="col-xs-12 col-sm-6">
            <table class="table table-condensed preview-details-table">
                <?php if (!empty($expense_data['client_name'])): ?>
                <tr>
                    <td><strong>Client</strong></td>
                    <td><?php echo htmlspecialchars($expense_data['client_name']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($expense_data['notes'])): ?>
                <tr>
                    <td><strong>Notes</strong></td>
                    <td><?php echo htmlspecialchars($expense_data['notes']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($expense_data['file_name'])): ?>
                <tr>
                    <td><strong>Receipt</strong></td>
                    <td><a class="openImageModal action_links" href="<?php echo $expense_data['file_name']; ?>" target="_blank">View Attachment</a></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="clearfix formSpacer"></div>
</div>
<!-- END content area -->
