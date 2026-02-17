<table style="border-top:1px solid #e8e8e8; border-bottom:1px solid #e8e8e8;">
    <div style="border-top:1px solid #e8e8e8; border-bottom:1px solid #e8e8e8; margin-bottom:10px;">
        <tr style="font-weight:bold;">
            <th style="width:60%;">Item:</th>
            <th style="width:13.33%;">Qty:</th>
            <th style="width:13.33%;">Rate:</th>
            <th style="width:13.33%;">Total ($invoice_db_data->currency_short_code):</th>
        </tr>
    </div>

    <div></div>

    $invoice_items

    <div style="border-bottom:1px solid #e8e8e8;"></div>
    <tr>
        <td style="width:65%;">&nbsp;</td>
        <td style="width:70%;">
            <table align="right"
                   style="width:50%; font-weight:bold; text-align:right; border-bottom:1px solid #e8e8e8;">
                <tr>
                    <td>Subtotal:</td>
                    <td style="color:$text_color3;">$invoice_db_data->sub_total</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td style="color:$text_color3;">$invoice_db_data->discount_total</td>
                </tr>
                <tr>
                    <td>Vat Amount:</td>
                    <td style="color:$text_color3;">$invoice_db_data->vat_amount</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width:65%;">&nbsp;</td>
        <td style="width:50%;">
            <table align="right" style="width:50%; font-weight:bold; margin-left:300px; text-align:right;">
                <tr style="font-size:10px; font-weight:normal; line-height: normal;">
                    <td><h3 style="font-weight:normal">Total:</h3></td>
                    <td style="color:$text_color2;"><h3 style="font-weight:normal">$invoice_db_data->currency_symbol
                            $invoice_db_data->total_amount</h3></td>
                </tr>
            </table>
        </td>
    </tr>
</table>