<!-- list area -->
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Flowbite -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!--<script src="https://code.highcharts.com/modules/exporting.js"></script>-->
<!--<script src="https://code.highcharts.com/modules/export-data.js"></script>-->
<!--<script src="https://code.highcharts.com/modules/accessibility.js"></script>-->

<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.3/dist/flowbite.min.js"></script>

<style>
    body h1 {
        font-size: 23px; !important;
    }
</style>

<script>

$(document).ready(function () {
    if (typeof Highcharts === 'undefined') {
        $.getScript("https://code.highcharts.com/highcharts.js", function () {
            loadCharts();
        });
    } else {
        loadCharts();
    }

    function loadCharts() {
       
    Highcharts.chart('container-revinue-data', {

        title: {
            text: '',
            align: 'left'
        },
        chart: {
            type: 'column'
        },
        xAxis: {

            categories: <?php echo $month_wise_sales['month']; ?>,
            crosshair: true,
            accessibility: {
                description: 'Revenue'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Value'
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Sales',
            color: '#15A236',
            data: <?php echo $month_wise_sales['value']; ?>,
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px',
                    fontWeight: 'bold'
                }
            }
        }],
        credits: {
            enabled: false
        },
        exporting: {
            enabled: true,
        }
    });

const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const months = [];
const currentDate = new Date();
const currentMonth = currentDate.getMonth(); // 0-based index for months
const currentYear = currentDate.getFullYear();

for (let i = 11; i >= 0; i--) {
    let monthIndex = (currentMonth - i + 12) % 12;
    let year = currentYear - (i > currentMonth ? 1 : 0);
    months.push(`${monthNames[monthIndex]} ${year}`);
}



    Highcharts.chart('container-business-overview', {
        title: {
            text: '',
            align: 'left'
        },

        yAxis: {
            title: {
                text: 'Value'
            }
        },

        xAxis: {
            categories: months, // Use dynamic month labels
            title: {
                text: 'Month'
            }
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        series: [{
                name: 'Sales',
                data: <?php echo json_encode($get_monthwise_sales)?>
            },
            {
                name: 'Expense',
                data: <?php echo json_encode($get_monthwise_expenses)?>
            },
            {
                name: 'Profit',
                data: <?php echo json_encode($get_monthwise_profit)?>
            }
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: true
        }
    });
}
});


</script>



<div>
    <div class="row">
        <!-- Panel 1 -->
        <div class="col-lg-3">
            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-money text-green-500 fa-md"></i> <span><b> &nbsp; Annual Revenue</b></span>

                <a href="<?php echo base_url('invoices'); ?>" class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($currency) ? $currency['currency_symbol'] : '$'); ?>
                        <?php echo (isset($total_revenue) ? number_format(round($total_revenue,2)) : 'Data not available'); ?>
                    </h2>
                </a>
                <?php if($get_current_month_revenue){ ?>
                <p class="font-semibold"> Current month
                    revenue:<?php echo (isset($get_current_month_revenue) ? number_format(round($get_current_month_revenue,2)) : 'Data not available'); ?>
                    <?php }?>
            </div>
        </div>


        <!-- Panel 2 -->
        <div class="col-lg-3">

            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-file-text text-indigo-500 fa-md"></i> <span><b> &nbsp; Invoices</b></span>

                <a href="<?php echo base_url('invoices'); ?>" class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($total_invoice) ? $total_invoice : 'Data not available'); ?>
                    </h2>
                </a>
                <p> &nbsp;</p>
            </div>

        </div>
        <!-- Panel 3 -->
        <div class="col-lg-3">

            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-users text-blue-500 fa-md"></i> <span><b> &nbsp; Active Clients</b></span>

                <a href="<?php echo base_url('contacts'); ?>" class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($active_client) ? $active_client : 'Data not available'); ?>
                    </h2>
                </a>
                <p> &nbsp;</p>
            </div>
        </div>

        <!-- Panel 4 -->
        <div class="col-lg-3">
            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-line-chart text-purple-500 fa-md"></i> <span><b>&nbsp; Growth</b></span>

                <span class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo ((isset($get_current_year_sales_growth) && $get_current_year_sales_growth >0) ? $get_current_year_sales_growth .' %': '0 %'); ?>
                    </h2>
                </span>
                <p> &nbsp;</p>
            </div>
        </div>
    </div>

    <div class="clearfix"><br></div>
   

    <div class="row">
        <!-- Panel 1 -->
        <div class="col-lg-4">
            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-trophy fa-md text-yellow-500"></i> <span><b> &nbsp; Top Client</b></span>

                <a href="<?php echo base_url('contacts'); ?>" class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($get_top_organization) ? $get_top_organization : 'Data not available'); ?>
                    </h2>
                </a>
            </div>

        </div>

        <!-- Panel 2 -->
        <div class="col-lg-4">
            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-exclamation-triangle text-red-500 fa-md"></i> <span><b> &nbsp; Overdue
                        Invoices</b></span>

                <a href="<?php echo base_url('contacts'); ?>" class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($get_unpaid_invoices_count) ? $get_unpaid_invoices_count : 'Data not available'); ?>
                    </h2>
                </a>
            </div>
        </div>
        <!-- Panel 3 -->
        <div class="col-lg-4">
            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <i class="fa fa-clock-o text-blue-500 fa-md"></i> <span><b> &nbsp; Average Payment Time
                    </b></span>

                <span class="mt-2">
                    <h2
                        class="mb-2 mt-4 text-4xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                        <?php echo (isset($get_average_payment_days) ? $get_average_payment_days .' Days': 'Data not available'); ?>
                    </h2>
                </span>
            </div>
        </div>

    </div>

    <div class="clearfix"><br></div>
    <div class="row">
        <!-- Panel 1 -->
        <div class="col-lg-6">

            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h4><b>Revenue Breakdown</b></h4>

                <div id="container-revinue-data"></div>
            </div>
        </div>
        <div class="col-lg-6">

            <div
                class="p-6 bg-white border border-gray-200 text-center rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h4><b>Business Overview</b></h4>

                <div id="container-business-overview"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"><br></div>

</div>
<!-- END list area -->