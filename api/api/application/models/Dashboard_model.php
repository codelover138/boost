<?php

class Dashboard_model extends CI_Model
{
    public $table_prefix;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('generic_model');
        $this->table_prefix = $this->config->item('db_table_prefix');
    }

    public function get_total_revenue($start_date = null, $end_date = null)
{
    $current_year = date('Y');
    // Calculate total revenue from invoices
    $this->db->select_sum('total_amount', 'total_invoice_revenue');
    $this->db->where('status !=', 'draft');

    if ($start_date && $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
    } else {
        // Apply condition for current year
        $this->db->where('YEAR(date)', $current_year);
    }

    $invoice_query = $this->db->get('boost_invoices');
    $total_invoice_revenue = $invoice_query->row()->total_invoice_revenue ?? 0;

    // Calculate total credit notes amount
    $this->db->select_sum('sub_total', 'total_credit_notes');
    $this->db->where('status !=', 'draft');

    if ($start_date && $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
    } else {
        // Apply condition for current year
        $this->db->where('YEAR(date)', $current_year);
    }

    $credit_query = $this->db->get('boost_credit_notes');
    $total_credit_notes = $credit_query->row()->total_credit_notes ?? 0;

    // Calculate net revenue
    $net_revenue = $total_invoice_revenue - $total_credit_notes;

    return $net_revenue;
}


public function get_currency_details() {
    $this->db->select('boost_currencies.currency_name, boost_currencies.currency_symbol');
    $this->db->from('boost_organisations');
    $this->db->join('boost_currencies', 'boost_currencies.id = boost_organisations.currency_id', 'inner');
    
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return $query->row(); // Return the first result as an object
    } else {
        return null; // No data found
    }
}
public function get_invoice_count($start_date = null, $end_date = null)
{
    $this->db->select('COUNT(id) AS inv'); // Correct use of COUNT
    $this->db->where('status !=', 'draft');
    $current_year = date('Y');

    if ($start_date && $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
    } else {
        // Apply condition for current year
        $this->db->where('YEAR(date)', $current_year);
    }
    $query = $this->db->get('boost_invoices');
    return $query->row()->inv; // Return the total count
}

public function count_contacts_by_type($contact_type_id)
    {
        $this->db->where('contact_type_id', $contact_type_id);
        $this->db->from('boost_contacts');
        return $this->db->count_all_results(); // Returns the count of rows
    }



public function get_monthwise_revenue($start_date = null, $end_date = null)
{
    // -------------------------
    // Set Date Range for the Last 12 Months
    // -------------------------
    $end_date = date('Y-m-d'); // Current date (end of range)
    $start_date = date('Y-m-d', strtotime('-11 months', strtotime($end_date))); // 11 months ago

    // -------------------------
    // Initialize Last 12 Months with Zeros
    // -------------------------
    $monthwise_revenue = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = strtotime("-$i months", strtotime($end_date));
        $year = (int)date('Y', $date);
        $month = (int)date('m', $date);
        $monthwise_revenue["$year-$month"] = [
            'invoice_revenue' => 0,
            'credit_revenue' => 0,
        ];
    }

    // -------------------------
    // Fetch Month-wise Invoice Revenue
    // -------------------------
    $this->db->select('YEAR(date) as year, MONTH(date) as month, SUM(total_amount) as total_invoice_revenue');
    $this->db->where('status !=', 'draft');
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $this->db->group_by(['YEAR(date)', 'MONTH(date)']);
    $invoice_query = $this->db->get('boost_invoices');
    $invoice_revenues = $invoice_query->result_array();

    // Map Invoice Data to Months
    foreach ($invoice_revenues as $invoice) {
        $year = (int)$invoice['year'];
        $month = (int)$invoice['month'];
        if (isset($monthwise_revenue["$year-$month"])) {
            $monthwise_revenue["$year-$month"]['invoice_revenue'] += (float)$invoice['total_invoice_revenue'];
        }
    }

    // -------------------------
    // Fetch Month-wise Credit Notes Revenue
    // -------------------------
    $this->db->select('YEAR(date) as year, MONTH(date) as month, SUM(sub_total) as total_credit_notes');
    $this->db->where('status !=', 'draft');
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $this->db->group_by(['YEAR(date)', 'MONTH(date)']);
    $credit_query = $this->db->get('boost_credit_notes');
    $credit_revenues = $credit_query->result_array();

    // Map Credit Notes Data to Months
    foreach ($credit_revenues as $credit) {
        $year = (int)$credit['year'];
        $month = (int)$credit['month'];
        if (isset($monthwise_revenue["$year-$month"])) {
            $monthwise_revenue["$year-$month"]['credit_revenue'] += (float)$credit['total_credit_notes'];
        }
    }

    // -------------------------
    // Calculate Net Revenue for Each Month
    // -------------------------
    $revenues = [];
    foreach ($monthwise_revenue as $key => $data) {
        [$year, $month] = explode('-', $key);
        $revenues[] = [
            'month' => $this->getMonthName((int)$month) . " $year",
            'net_revenue' => $data['invoice_revenue'] - $data['credit_revenue'],
        ];
    }

    return $this->transformMonthwiseRevenue($revenues);
}



public function get_current_month_revenue($start_date = null, $end_date = null)
{
    $current_month = date('n'); // Numeric representation of the current month (1–12)

    // Calculate total revenue from invoices
    $this->db->select_sum('total_amount', 'total_invoice_revenue');
    $this->db->where('status !=', 'draft');

    if ($start_date && $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
    } else {
        // Apply condition for the current month
        $this->db->where('MONTH(date)', $current_month);
    }

    $invoice_query = $this->db->get('boost_invoices');
    $total_invoice_revenue = $invoice_query->row()->total_invoice_revenue ?? 0;

    // Calculate total credit notes amount
    $this->db->select_sum('sub_total', 'total_credit_notes');
    $this->db->where('status !=', 'draft');

    if ($start_date && $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
    } else {
        // Apply condition for the current month
        $this->db->where('MONTH(date)', $current_month);
    }

    $credit_query = $this->db->get('boost_credit_notes');
    $total_credit_notes = $credit_query->row()->total_credit_notes ?? 0;

    // Calculate net revenue
    $net_revenue = $total_invoice_revenue - $total_credit_notes;

    return $net_revenue;
}

public function get_top_organization()
{
    $current_year = date('Y'); // Get the current year

    $this->db->select('c.organisation');
    $this->db->from('boost_invoices i');
    $this->db->join('boost_contacts c', 'i.contact_id = c.id');
    $this->db->where('YEAR(i.date)', $current_year);
    $this->db->group_by('c.organisation');
    $this->db->order_by('SUM(i.total_amount)', 'DESC'); // Order by total revenue
    $this->db->limit(1);

    $query = $this->db->get();

    $result = $query->row(); // Get the top result
    return $result ? $result->organisation : null; // Return the name or null if no result
}

public function get_unpaid_invoices_count()
{
    $current_date = date('Y-m-d H:i:s'); // Get the current date and time in MySQL format

    $this->db->from('boost_invoices');
    $this->db->where('DATE(due_date) <', 'CURDATE()', false);
    $this->db->where('status !=', 'paid'); // Exclude invoices with status 'paid'
    //$this->db->where('due_date >', $current_date); // Check for due_date greater than the current date

    return $this->db->count_all_results(); // Return the count of rows
}


function transformMonthwiseRevenue($data)
{
    $months = [];
    $values = [];

    foreach ($data as $item) {
        $months[] = $item['month'];
        $values[] = $item['net_revenue'];
    }

    $monthString = json_encode($months);
    $valueString = json_encode($values);

    return [
        'month' => $monthString,
        'value' => $valueString,
    ];
}


public function get_average_payment_days()
{
    $this->db->select('AVG(DATEDIFF(p.payment_date, i.date)) AS avg_payment_days', false);
    $this->db->from('boost_invoices i');
    $this->db->join('boost_invoice_payments p', 'i.id = p.invoice_id');
    $this->db->where('p.payment_date IS NOT NULL');

    $query = $this->db->get();
    $result = $query->row();

    // Ensure the result is a number or return 0 if no data
    return $result ? round((float) $result->avg_payment_days,0) : '';
}



public function get_current_year_sales_growth()
{
    $sql = "
        SELECT 
            YEAR(date) AS year,
            SUM(total_amount) AS total_sales
        FROM 
            boost_invoices
        WHERE 
            status != 'draft'
            AND YEAR(date) IN (YEAR(CURDATE()), YEAR(CURDATE()) - 1)
        GROUP BY 
            YEAR(date)
        ORDER BY 
            YEAR(date) ASC
    ";

    $query = $this->db->query($sql);
    $sales_data = $query->result_array();

    // Initialize sales values
    $current_year_sales = 0;
    $previous_year_sales = 0;

    foreach ($sales_data as $data) {
        if ($data['year'] == date('Y')) {
            $current_year_sales = $data['total_sales'];
        } elseif ($data['year'] == date('Y') - 1) {
            $previous_year_sales = $data['total_sales'];
        }
    }

    // Calculate growth percentage
    if ($previous_year_sales > 0) {
        $growth_percentage = round((($current_year_sales - $previous_year_sales) / $previous_year_sales) * 100,2);
    } else {
        $growth_percentage = null; // Cannot calculate growth if previous year sales are 0
    }

    return $growth_percentage;
}






function getMonthName($month_number) {
    $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];

    if (isset($months[$month_number])) {
        return $months[$month_number];
    } else {
        return 'Invalid month number';
    }
}

public function get_monthwise_sales($start_date = null, $end_date = null)
{
    // -------------------------
    // Set Date Range for the Last 12 Months
    // -------------------------
    $end_date = date('Y-m-d'); // Current date (end of range)
    $start_date = date('Y-m-d', strtotime('-11 months', strtotime($end_date))); // 11 months ago

    // -------------------------
    // Initialize Last 12 Months with Zeros
    // -------------------------
    $revenues = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = strtotime("-$i months", strtotime($end_date));
        $month = (int)date('m', $date); // Get the month (1-12)
        $year = (int)date('Y', $date);  // Get the year
        $revenues["$year-$month"] = 0; // Initialize each month with 0
    }

    // -------------------------
    // Fetch Invoice Revenues
    // -------------------------
    $this->db->select('YEAR(date) as year, MONTH(date) as month, SUM(total_amount) as total_invoice_revenue');
    $this->db->where('status !=', 'draft');
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $this->db->group_by(['YEAR(date)', 'MONTH(date)']);
    $invoice_query = $this->db->get('boost_invoices');
    $invoice_revenues = $invoice_query->result_array();

    // Map Invoice Data to Revenues Array
    foreach ($invoice_revenues as $invoice) {
        $year = (int)$invoice['year'];
        $month = (int)$invoice['month'];
        $revenues["$year-$month"] += (float)$invoice['total_invoice_revenue'];
    }

    // -------------------------
    // Fetch Credit Note Revenues
    // -------------------------
    $this->db->select('YEAR(date) as year, MONTH(date) as month, SUM(sub_total) as total_credit_notes');
    $this->db->where('status !=', 'draft');
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $this->db->group_by(['YEAR(date)', 'MONTH(date)']);
    $credit_query = $this->db->get('boost_credit_notes');
    $credit_revenues = $credit_query->result_array();

    // Subtract Credit Notes from Revenues Array
    foreach ($credit_revenues as $credit) {
        $year = (int)$credit['year'];
        $month = (int)$credit['month'];
        $revenues["$year-$month"] -= (float)$credit['total_credit_notes'];
    }

    // -------------------------
    // Return Final Revenue Array (Indexed from Oldest to Newest Month)
    // -------------------------
    return array_values($revenues);
}


public function get_monthwise_expenses($start_date = null, $end_date = null)
{
    // -------------------------
    // Set Date Range for the Last 12 Months
    // -------------------------
    $end_date = date('Y-m-d'); // Current date (end of range)
    $start_date = date('Y-m-d', strtotime('-11 months', strtotime($end_date))); // 11 months ago

    // -------------------------
    // Fetch Month-wise Expenses
    // -------------------------
    $this->db->select('YEAR(date) as year, MONTH(date) as month, SUM(total_amount) as total_expense');
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $this->db->group_by(['YEAR(date)', 'MONTH(date)']);
    $expense_query = $this->db->get('boost_expenses');
    $expense_results = $expense_query->result_array();

    // -------------------------
    // Initialize Last 12 Months Array with Zeros
    // -------------------------
    $expenses = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = strtotime("-$i months", strtotime($end_date));
        $year = (int)date('Y', $date);
        $month = (int)date('m', $date);
        $expenses["$year-$month"] = 0; // Initialize to 0
    }

    // -------------------------
    // Map Expenses to the Last 12 Months
    // -------------------------
    foreach ($expense_results as $expense) {
        $year = (int)$expense['year'];
        $month = (int)$expense['month'];
        $expenses["$year-$month"] = (float)$expense['total_expense'];
    }

    // -------------------------
    // Reindex and Return Final Expenses Array
    // -------------------------
    return array_values($expenses);
}



public function get_month_year_revenue($date_input = null)
{
    $revenues = [];

    if ($date_input) {
        // Check if the input is in YYYY-MM format
        if (preg_match('/^\d{4}-\d{2}$/', $date_input)) {
            // Extract year and month
            list($year, $month) = explode('-', $date_input);
            $months = $this->getPreviousThreeMonths($year, $month);

            foreach ($months as $date) {
                $revenues[] = $this->calculateMonthRevenue($date['year'], $date['month']);
            }

        } elseif (preg_match('/^\d{4}$/', $date_input)) {
            // If the input is a year (YYYY), get the year and the previous two years
            $years = $this->getPreviousThreeYears($date_input);

            foreach ($years as $year) {
                $revenues[] = $this->calculateYearRevenue($year);
            }
        }
    } else {
        // Default to current year and previous two years if no input is provided
        $currentYear = date('Y');
        $years = $this->getPreviousThreeYears($currentYear);

        foreach ($years as $year) {
            $revenues[] = $this->calculateYearRevenue($year);
        }
    }

    return $revenues;
}
public function get_month_year_client_revenue($date_input = null)
{
    $revenues = [];

    if ($date_input) {
        // Check if the input is in YYYY-MM format (Month-Based)
        if (preg_match('/^\d{4}-\d{2}$/', $date_input)) {
            // Extract year and month
            list($year, $month) = explode('-', $date_input);
            $months = $this->getPreviousThreeMonths($year, $month);

            foreach ($months as $date) {
                $monthly_revenues = $this->calculateClientMonthRevenue($date['year'], $date['month']);

                foreach ($monthly_revenues as $revenue) {
                    $client_name = $revenue['client_name'];
                    if (!isset($revenues[$client_name])) {
                        $revenues[$client_name] = ['client_name' => $client_name, 'data' => []];
                    }
                    $revenues[$client_name]['data'][] = [
                        'period' => $revenue['period'],
                        'net_revenue' => $revenue['net_revenue'],
                    ];
                }
            }
           

        // Check if the input is in YYYY format (Year-Based)
        } elseif (preg_match('/^\d{4}$/', $date_input)) {
            // Get the input year and the previous two years
            $years = $this->getPreviousThreeYears($date_input);

            foreach ($years as $year) {
                $yearly_revenues = $this->calculateClientYearRevenue($year);

                foreach ($yearly_revenues as $revenue) {
                    $client_name = $revenue['client_name'];
                    if (!isset($revenues[$client_name])) {
                        $revenues[$client_name] = ['client_name' => $client_name, 'data' => []];
                    }
                    $revenues[$client_name]['data'][] = [
                        'period' => $revenue['period'],
                        'net_revenue' => $revenue['net_revenue'],
                    ];
                }
            }
        }
    } else {
        // Default to current year and previous two years if no input is provided
        $currentYear = date('Y');
        $years = $this->getPreviousThreeYears($currentYear);

        foreach ($years as $year) {
            $yearly_revenues = $this->calculateClientYearRevenue($year);

            foreach ($yearly_revenues as $revenue) {
                $client_name = $revenue['client_name'];
                if (!isset($revenues[$client_name])) {
                    $revenues[$client_name] = ['client_name' => $client_name, 'data' => []];
                }
                $revenues[$client_name]['data'][] = [
                    'period' => $revenue['period'],
                    'net_revenue' => $revenue['net_revenue'],
                ];
            }
        }
    }

    $filtered_revenues = array_filter($revenues, function ($list) {
        foreach ($list['data'] as $data) {
            if ($data['net_revenue'] != 0) {
                return true; 
            }
        }
        return false; 
    });

    return array_values($filtered_revenues);
}


public function get_month_year_client_expense($date_input = null)
{
    $expenses = [];
    $periods = []; // To store periods (months or years) dynamically

    if ($date_input) {
        // Check if the input is in YYYY-MM format (Month-Based)
        if (preg_match('/^\d{4}-\d{2}$/', $date_input)) {
            // Extract year and month
            list($year, $month) = explode('-', $date_input);
            $periods = $this->getPreviousThreeMonths($year, $month);

            foreach ($periods as $date) {
                $monthly_expense = $this->calculateClientMonthExpense($date['year'], $date['month']);

                foreach ($monthly_expense as $expense) {
                    $category_name = $expense['category_name'];
                    $period = $expense['period'];
                    $net_expense = $expense['net_expense'];

                    // Initialize category if not already set
                    if (!isset($expenses[$category_name])) {
                        $expenses[$category_name] = [
                            'category_name' => $category_name,
                            'data' => []
                        ];
                    }

                    // Append net expense for the current period
                    $expenses[$category_name]['data'][$period] = $net_expense;
                }
            }
        } 
        // Check if the input is in YYYY format (Year-Based)
        elseif (preg_match('/^\d{4}$/', $date_input)) {
            // Get the input year and the previous two years
            $periods = $this->getPreviousThreeYears($date_input);

            foreach ($periods as $date) {
                $yearly_expense = $this->calculateClientYearExpense($date);

                foreach ($yearly_expense as $expense) {
                    $category_name = $expense['category_name'];
                    $period = $expense['period'];
                    $net_expense = $expense['net_expense'];

                    // Initialize category if not already set
                    if (!isset($expenses[$category_name])) {
                        $expenses[$category_name] = [
                            'category_name' => $category_name,
                            'data' => []
                        ];
                    }

                    // Append net expense for the current period
                    $expenses[$category_name]['data'][$period] = $net_expense;
                }
            }
        }
        else {
        // Default to current year and previous two years if no input is provided
        $currentYear = date('Y');
        $periods = $this->getPreviousThreeYears($currentYear);

        foreach ($periods as $date) {
            $yearly_expense = $this->calculateClientYearExpense($date);

            foreach ($yearly_expense as $expense) {
                $category_name = $expense['category_name'];
                $period = $expense['period'];
                $net_expense = $expense['net_expense'];

                // Initialize category if not already set
                if (!isset($expenses[$category_name])) {
                    $expenses[$category_name] = [
                        'category_name' => $category_name,
                        'data' => []
                    ];
                }

                // Append net expense for the current period
                $expenses[$category_name]['data'][$period] = $net_expense;
            }
        }
    }
    
    // Filter out categories where all expenses are zero
    $filtered_expenses = array_filter($expenses, function ($list) {
        return array_sum($list['data']) != 0;
    });

    // Reformat the data to the desired structure [category_name, period1, period2, period3]
    $formatted_expenses = [];
    foreach ($filtered_expenses as $category) {
        $formatted_row = ['category_name' => $category['category_name']];

        // Ensure all three periods are present, default to 0 if not found
        foreach ($periods as $date) {
            if (isset($date['month'])) {
                $period = $this->getMonthNames($date['month']) . ' ' . $date['year'];
            } else {
                $period = $date;
            }
            $formatted_row[$period] = $category['data'][$period] ?? 0;
        }

        $formatted_expenses[] = $formatted_row;
    }
}

    return $formatted_expenses;
}



public function get_month_year_expense($date_input = null)
{
    $expenses = [];

    if ($date_input) {
        // Check if the input is in YYYY-MM format
        if (preg_match('/^\d{4}-\d{2}$/', $date_input)) {
            // Extract year and month
            list($year, $month) = explode('-', $date_input);
            $months = $this->getPreviousThreeMonths($year, $month);

            foreach ($months as $date) {
                $expenses[] = $this->calculateMonthExpense($date['year'], $date['month']);
            }

        } elseif (preg_match('/^\d{4}$/', $date_input)) {
            // If the input is a year (YYYY), get the year and the previous two years
            $years = $this->getPreviousThreeYears($date_input);

            foreach ($years as $year) {
                $expenses[] = $this->calculateYearExpense($year);
            }
        }
    } else {
        // Default to current year and previous two years if no input is provided
        $currentYear = date('Y');
        $years = $this->getPreviousThreeYears($currentYear);

        foreach ($years as $year) {
            $expenses[] = $this->calculateYearExpense($year);
        }
    }

    return $expenses;
}

/**
 * Helper function to get the previous three months including the given year and month.
 */
private function getPreviousThreeMonths($year, $month)
{
    $dates = [];
    for ($i = 0; $i < 3; $i++) {
        $dates[] = [
            'year' => date('Y', strtotime("-$i month", strtotime("$year-$month-01"))),
            'month' => date('m', strtotime("-$i month", strtotime("$year-$month-01")))
        ];
    }
    return $dates;
}

/**
 * Helper function to get the previous three years including the given year.
 */
private function getPreviousThreeYears($year)
{
    $years = [];
    for ($i = 0; $i < 3; $i++) {
        $years[] = $year - $i;
    }
    return $years;
}

/**
 * Calculate revenue for a specific month and year.
 */
private function calculateMonthRevenue($year, $month)
{
    // Calculate month-wise revenue from invoices
    $this->db->select('SUM(total_amount) as total_invoice_revenue');
    $this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $this->db->where('MONTH(date)', $month);
    $invoice_query = $this->db->get('boost_invoices');
    $invoice_revenue = $invoice_query->row()->total_invoice_revenue ?? 0;

    // Calculate month-wise credit notes amount
    $this->db->select('SUM(sub_total) as total_credit_notes');
    $this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $this->db->where('MONTH(date)', $month);
    $credit_query = $this->db->get('boost_credit_notes');
    $credit_revenue = $credit_query->row()->total_credit_notes ?? 0;

    // Calculate net revenue
    $net_revenue = $invoice_revenue - $credit_revenue;

    return [
        'period' => $this->getMonthNames($month) . ' ' . $year,
        'net_revenue' => $net_revenue,
    ];
}
private function calculateMonthExpense($year, $month)
{
    // Calculate month-wise expense from invoices
    $this->db->select('SUM(total_amount) as total_expense');
    //$this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $this->db->where('MONTH(date)', $month);
    $expense_query = $this->db->get('boost_expenses');
    $total_expense = $expense_query->row()->total_expense ?? 0;

    return [
        'period' => $this->getMonthNames($month) . ' ' . $year,
        'net_expense' => $total_expense,
    ];
}


private function calculateClientMonthExpense($year, $month)
{
    // Step 1: Get all expense categories
    $this->db->select('id, category_name');
    $this->db->order_by('category_name','ASC');
    $category_query = $this->db->get('boost_expenses_categories');
    $categories = $category_query->result();

    // Step 2: Get total expenses grouped by category_id
    $this->db->select('category_id, SUM(total_amount) as total_expense');
    //$this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $this->db->where('MONTH(date)', $month);
    $this->db->group_by('category_id'); // Group by category_id to get expenses per category
    $expense_query = $this->db->get('boost_expenses');
    $expense_results = $expense_query->result();

    // Step 3: Create an associative array for expenses by category ID
    $expense_lookup = [];
    foreach ($expense_results as $expense) {
        $expense_lookup[$expense->category_id] = $expense->total_expense ?? 0;
    }

    // Step 4: Calculate the net expense for each category
    $expenses = [];
    foreach ($categories as $category) {
        $category_id = $category->id;
        $category_name = $category->category_name;

        $net_expense = $expense_lookup[$category_id] ?? 0;

        $expenses[] = [
            'category_name' => $category_name,
            'period' => $this->getMonthNames($month) . ' ' . $year,
            'net_expense' => $net_expense,
        ];
    }

    return $expenses;
}


private function calculateClientYearExpense($year)
{
    $this->db->select('id, category_name');
    $this->db->order_by('category_name','ASC');
    $category_query = $this->db->get('boost_expenses_categories');
    $categories = $category_query->result();

    // Step 2: Get total expenses grouped by category_id
    $this->db->select('category_id, SUM(total_amount) as total_expense');
    //$this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $this->db->group_by('category_id'); // Group by category_id to get expenses per category
    $expense_query = $this->db->get('boost_expenses');
    $expense_results = $expense_query->result();

    // Step 3: Create an associative array for expenses by category ID
    $expense_lookup = [];
    foreach ($expense_results as $expense) {
        $expense_lookup[$expense->category_id] = $expense->total_expense ?? 0;
    }

    // Step 4: Calculate the net expense for each category
    $expenses = [];
    foreach ($categories as $category) {
        $category_id = $category->id;
        $category_name = $category->category_name;

        $net_expense = $expense_lookup[$category_id] ?? 0;

        $expenses[] = [
            'category_name' => $category_name,
            'period' =>$year,
            'net_expense' => $net_expense,
        ];
    }

    return $expenses;
}


private function calculateClientMonthRevenue($year, $month)
{
    // Get all clients to ensure we include each client in the results
    $this->db->select('id, organisation as client_name');
    $this->db->order_by('organisation','ASC');
    $clients_query = $this->db->get('boost_contacts');
    $clients = $clients_query->result();

    // Calculate client-wise revenue from invoices with client name
    $this->db->select('boost_invoices.contact_id, SUM(boost_invoices.total_amount) as total_invoice_revenue');
    $this->db->from('boost_invoices');
    $this->db->where('boost_invoices.status !=', 'draft');
    $this->db->where('YEAR(boost_invoices.date)', $year);
    $this->db->where('MONTH(boost_invoices.date)', $month);
    $this->db->group_by('boost_invoices.contact_id');
    $invoice_query = $this->db->get();
    $invoice_results = $invoice_query->result();

    // Calculate client-wise credit notes amount
    $this->db->select('boost_credit_notes.contact_id, SUM(boost_credit_notes.sub_total) as total_credit_notes');
    $this->db->from('boost_credit_notes');
    $this->db->where('boost_credit_notes.status !=', 'draft');
    $this->db->where('YEAR(boost_credit_notes.date)', $year);
    $this->db->where('MONTH(boost_credit_notes.date)', $month);
    $this->db->group_by('boost_credit_notes.contact_id');
    $credit_query = $this->db->get();
    $credit_results = $credit_query->result();

    // Create associative arrays for invoices and credit notes by client ID
    $invoice_lookup = [];
    foreach ($invoice_results as $invoice) {
        $invoice_lookup[$invoice->contact_id] = $invoice->total_invoice_revenue ?? 0;
    }

    $credit_lookup = [];
    foreach ($credit_results as $credit) {
        $credit_lookup[$credit->contact_id] = $credit->total_credit_notes ?? 0;
    }

    // Calculate net revenue for each client, default to 0 if no data is present
    $client_revenues = [];
    foreach ($clients as $client) {
        $contact_id = $client->id;
        $client_name = $client->client_name;

        $invoice_revenue = $invoice_lookup[$contact_id] ?? 0;
        $credit_revenue = $credit_lookup[$contact_id] ?? 0;
        $net_revenue = $invoice_revenue - $credit_revenue;

        $client_revenues[] = [
            'client_name' => $client_name,
            'period' => $this->getMonthNames($month) . ' ' . $year,
            'net_revenue' => $net_revenue,
        ];
    }

    return $client_revenues;
}


private function calculateClientYearRevenue($year)
{
    // Get all clients to ensure we include each client in the results
    $this->db->select('id, organisation as client_name');
    $this->db->order_by('organisation','ASC');
    $clients_query = $this->db->get('boost_contacts');
    $clients = $clients_query->result();

    // Calculate client-wise revenue from invoices with client name
    $this->db->select('boost_invoices.contact_id, SUM(boost_invoices.total_amount) as total_invoice_revenue');
    $this->db->from('boost_invoices');
    $this->db->where('boost_invoices.status !=', 'draft');
    $this->db->where('YEAR(boost_invoices.date)', $year);
    $this->db->group_by('boost_invoices.contact_id');
    $invoice_query = $this->db->get();
    $invoice_results = $invoice_query->result();

    // Calculate client-wise credit notes amount
    $this->db->select('boost_credit_notes.contact_id, SUM(boost_credit_notes.sub_total) as total_credit_notes');
    $this->db->from('boost_credit_notes');
    $this->db->where('boost_credit_notes.status !=', 'draft');
    $this->db->where('YEAR(boost_credit_notes.date)', $year);
    $this->db->group_by('boost_credit_notes.contact_id');
    $credit_query = $this->db->get();
    $credit_results = $credit_query->result();

    // Create associative arrays for invoices and credit notes by client ID
    $invoice_lookup = [];
    foreach ($invoice_results as $invoice) {
        $invoice_lookup[$invoice->contact_id] = $invoice->total_invoice_revenue ?? 0;
    }

    $credit_lookup = [];
    foreach ($credit_results as $credit) {
        $credit_lookup[$credit->contact_id] = $credit->total_credit_notes ?? 0;
    }

    // Calculate net revenue for each client, default to 0 if no data is present
    $client_revenues = [];
    foreach ($clients as $client) {
        $contact_id = $client->id;
        $client_name = $client->client_name;

        $invoice_revenue = $invoice_lookup[$contact_id] ?? 0;
        $credit_revenue = $credit_lookup[$contact_id] ?? 0;
        $net_revenue = $invoice_revenue - $credit_revenue;

        $client_revenues[] = [
            'client_name' => $client_name,
            'period' =>  $year,
            'net_revenue' => $net_revenue,
        ];
    }

    return $client_revenues;
}



/**
 * Calculate revenue for a specific year.
 */
private function calculateYearRevenue($year)
{
    // Calculate year-wise revenue from invoices
    $this->db->select('SUM(total_amount) as total_invoice_revenue');
    $this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $invoice_query = $this->db->get('boost_invoices');
    $invoice_revenue = $invoice_query->row()->total_invoice_revenue ?? 0;

    // Calculate year-wise credit notes amount
    $this->db->select('SUM(sub_total) as total_credit_notes');
    $this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $credit_query = $this->db->get('boost_credit_notes');
    $credit_revenue = $credit_query->row()->total_credit_notes ?? 0;

    // Calculate net revenue
    $net_revenue = $invoice_revenue - $credit_revenue;

    return [
        'period' => $year,
        'net_revenue' => $net_revenue,
    ];
}
private function calculateYearExpense($year)
{

    $this->db->select('SUM(total_amount) as total_expense');
   // $this->db->where('status !=', 'draft');
    $this->db->where('YEAR(date)', $year);
    $expense_query = $this->db->get('boost_expenses');
    $total_expense = $expense_query->row()->total_expense ?? 0;

    return [
        'period' => $year,
        'net_expense' => $total_expense,
    ];
}

/**
 * Get month name from month number.
 */
private function getMonthNames($month)
{
    return date("F", mktime(0, 0, 0, $month, 1));
}





}