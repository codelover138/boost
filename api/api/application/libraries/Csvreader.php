<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CSVReader
{
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function readcsv($csv_file, $csv_has_headings = 0)
    {
        $file = fopen('resources/lists/import/'.$csv_file, 'r');
        $csv_array = array();
        while (($line = fgetcsv($file)) !== FALSE)
        {
            if(!empty($line) && !is_null($line[0]))
            {
                $csv_array[] = $line;
            }
        }
        fclose($file);

        if($csv_has_headings == 1)
        {
            unset($csv_array[0]);
        }
        return $csv_array;
    }

    public function write_csv($file_name, $data)
    {
        $this->CI->load->helper('file');

        if ( ! write_file('resources/lists/export/'.$file_name.'.csv', $data))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function delete_csv($file_name)
    {
        return unlink('resources/lists/export/'.$file_name.'.csv');
    }
}
?>