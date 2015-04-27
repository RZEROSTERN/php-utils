<?php

/**
 *  Class CSVExporter
 *  This class allows you to make a CSV file from embedded data.
 *  @author RZEROSTERN
 *  @license  Beerware for everyone :)
 *  ----------------------------------------------------------------------------
 *  "THE BEER-WARE LICENSE" (Revision 42):
 *  
 *  RZEROSTERN wrote this file. As long as you retain this notice you
 *  can do whatever you want with this stuff. If we meet some day, and you think
 *  this stuff is worth it, you can buy me a beer in return.
 *
 *  RZEROSTERN
 *  ----------------------------------------------------------------------------
 */
class CSVExporter {
    private $path;
    private $columns;
    private $rows;

    /**
     *  Constructor
     *  @param p_pathToExport   Name of the file to save
     */
    public function __construct($p_pathToExport){
        $this->path = $p_pathToExport;
        $this->columns = array();
        $this->rows = array();
    }

    /**
     *  add_column
     *  Adds a column to your data.
     *  @param column_name
     */
    public function add_column($column_name){
        array_push($this->columns, $column_name);
    }

    /**
     *  add_column
     *  Adds a row to your data.
     *  @param column_name
     */
    public function add_row($row_data){
        array_push($this->rows, $row_data);
    }

    /**
     *  download_file
     *  Retrieves the csv file to the user as an attachment.
     */
    public function download_file(){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$this->path);

        $output = fopen("php://output", "w");

        // Headers
        fputcsv($output, $this->columns);
        // Data
        foreach($this->rows as $row){
            fputcsv($output, $row);
        }
    }
} 
