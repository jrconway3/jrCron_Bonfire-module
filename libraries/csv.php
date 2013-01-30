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
      * Initialize CSV
      *
      * @author David A Conway Jr.
      * @param string $file: the name of the file to create
      * @param string $job : name of the cron job to save file for; optional
      * @return path to the csv file generated
      */
    function init_csv($file, $job = '') {
        // Exports Folder Exists?
        $dir = dirname(dirname(BASEPATH)) . '/public/exports';
        if(!is_dir($dir)) {
            mkdir($dir);
        }

        // Create For Cron Job?
        if(!empty($job)) {
            // Save in Job Folder
            $dir .= '/' . $job;
            if(!is_dir($dir)) {
                mkdir($dir);
            }
        }

        // Clean URL
        $folders = explode('/', $file);
        $file    = end($folders);
        $path    = $dir . '/' . $file . '.csv';

        // Return Filename
        return $path;
    }

    /**
      * Get CSV HREF
      *
      * @author David A Conway Jr.
      * @param string $file: the name of the file to create
      * @param string $job : name of the cron job to save file for; optional
      * @return path to the csv file generated
      */
    function href_csv($file, $job = '') {
        // Exports Folder Exists?
        $dir = base_url() . 'exports';

        // Create For Cron Job?
        if(!empty($job)) {
            // Save in Job Folder
            $dir .= '/' . $job;
        }

        // Clean URL
        $folders = explode('/', $file);
        $file    = end($folders);
        $href    = $dir . '/' . $file . '.csv';

        // Return File HREF
        return $href;
    }

    /**
      * Add CSV Columns
      *
      * @author David A Conway Jr.
      * @param array  $cols: fields to add in first row
      * @param string $file: the name of the file to create
      * @param string $job : name of the cron job to save file for; optional
      * @return path to the csv file generated
      */
    function add_csv_cols($cols, $file, $job = '') {
        // Initialize CSV
        $path = $this->init_csv($file, $job);

        // Generate CSV File
        ob_start();
        if(!$f = fopen($path, 'wb')) {
            throw(new Exception("Error opening invalid CSV: $path"));
            return false;
        }

        // Create First Row
        if(!fputcsv($f, $cols)) {
            throw(new Exception("Error saving CSV columns: $path"));
            return false;
        }

        // End Process
        if(!fclose($f)) {
            throw(new Exception("Cannot close invalid file: $path"));
            return false;
        }
        $str = ob_get_contents();
        ob_end_clean();

        // Return Filename
        return $path;
    }

    /**
      * Add CSV Row
      *
      * @author David A Conway Jr.
      * @param array  $row : fields to add in new row
      * @param string $file: the name of the file to create
      * @param string $job : name of the cron job to save file for; optional
      * @return path to the csv file generated
      */
    function add_csv_row($row, $file, $job = '') {
        // Initialize CSV
        $path = $this->init_csv($file, $job);

        // Generate CSV File
        ob_start();
        if(!$f = fopen($path, 'ab')) {
            throw(new Exception("Error opening invalid CSV: $path"));
            return false;
        }

        // Create First Row
        if(!fputcsv($f, $row)) {
            throw(new Exception("Error saving CSV row: $path"));
            return false;
        }

        // End Process
        if(!fclose($f)) {
            throw(new Exception("Cannot close invalid file: $path"));
            return false;
        }
        $str = ob_get_contents();
        ob_end_clean();

        // Return Filename
        return $path;
    }

    /**
      * Convert Array to CSV
      *
      * @author Farhan Khwaja
      */
    function array_to_csv($array, $download = "") {
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
    function query_to_csv($query, $headers = TRUE, $download = "") {
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