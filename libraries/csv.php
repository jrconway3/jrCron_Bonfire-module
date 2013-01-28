<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter CSV Class
 *
 * Generate CSV files from Arrays or Queries.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Farhan Khwaja; converted to Library by David A Conway Jr.
 * @license         http://philsturgeon.co.uk/code/dbad-license
 * @link            http://www.code2learn.com/2012/03/generating-csv-file-using-codeigniter.html
 */
class CSV {

    /**
      * Convert Array to CSV
      *
      * @author Farhan Khwaja
      */
    function array_to_csv($array, $download = "")
    {
        if ($download != "")
        {    
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $download . '"');
        }        

        ob_start();
        $f = fopen($download, 'wb') or show_error("Can't open php://output");
        $n = 0;        
        foreach ($array as $line)
        {
            $n++;
            if ( ! fputcsv($f, $line))
            {
                show_error("Can't write line $n: $line");
            }
        }
        fclose($f) or show_error("Can't close php://output");
        $str = ob_get_contents();
        ob_end_clean();

        if ($download == "")
        {
            return $str;    
        }
        else
        {    
            echo $str;
        }        
    }

    /**
      * Convert Array to CSV
      *
      * @author Farhan Khwaja
      */
    function query_to_csv($query, $headers = TRUE, $download = "")
    {
        if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
        {
            show_error('invalid query');
        }
        
        $array = array();
        
        if ($headers)
        {
            $line = array();
            foreach ($query->list_fields() as $name)
            {
                $line[] = $name;
            }
            $array[] = $line;
        }
        
        foreach ($query->result_array() as $row)
        {
            $line = array();
            foreach ($row as $item)
            {
                $line[] = $item;
            }
            $array[] = $line;
        }

        echo array_to_csv($array, $download);
    }

}

/* End of file Curl.php */
/* Location: ./application/libraries/Curl.php */