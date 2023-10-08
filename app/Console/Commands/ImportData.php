<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;

class ImportData extends Command
{
    protected $signature = 'job_task:import_file {path_to_file}';
    protected $description = 'Parse and display data from a fixed-width text file';

    public function handle()
    {
        // Retrieve the 'path' argument from the command when executing 
        $pathToFile = $this->argument('path_to_file');

        // Path to the .csv file
        $csvFilePath = storage_path('files/import_file_specs.csv');
        
        // Using Reader::createFromPath from league/csv library
        $csv = Reader::createFromPath($csvFilePath, 'r');
        // Set the first row of the .csv file as header row
        $csv->setHeaderOffset(0);

        // Initialize an associative array to store field specifications(key=>value pair)
        $recordData = [];

        // Iterate over each row in the CSV file to extract field specifications
        foreach ($csv as $row) {
            // Extract the 'record_type' from the CSV row
            $recordType = $row['record_type'];

            // If the 'recordType' doesn't exist in the recordData array, create an empty array for it
            if (!array_key_exists($recordType, $recordData)) {
                $recordData[$recordType] = [];
            }

            // Extract the 'description' field and truncate it to a maximum of 28 characters
            //(I set it to 28 because the description is too long for a column name)

            $description = substr($row['description'], 0, 28);

            // If 'description' is empty after truncation, set it to 'Undefined'
            // I found that there are some empty description fields
            if (empty($description)) {
                $description = 'Undefined';
            }

            // Store the field specification in the recordData array
            // Storage\files\data.json- I saved the specification in this file
            // Created a nested array to store the Record Type and Description and values for each description
            $recordData[$recordType][$description] = [(int) $row['start_range'] - 1, (int) $row['length']];
        }

        // Check if the specified file exists
        if (!File::exists($pathToFile)) {
            $this->error("File not found at '{$pathToFile}'");
            return 1;
        }

        // Open the specified file in read mode
        $file = fopen($pathToFile, 'r');

        // If the file is successfully opened
        if ($file) {
            // Initialize an empty associative array to store imported records
            $importRecords = [];

            // Reading the file line by line 
            while (($line = fgets($file)) !== false) {
                // Initialize an array to store the data for the current record
                $obj = [];

                // Extract the 'recordType' from a fixed position in the line (characters 17 to 18)
                // As it is mentioned in the task 
                $recordType = substr($line, 17, 2);

                // If 'recordType' is empty, skip the current line
                if (empty($recordType)) {
                    continue;
                }

                // If 'recordType' doesn't exist in the importRecords array, create an empty array for it
                if (!array_key_exists($recordType, $importRecords)) {
                    $importRecords[$recordType] = [];
                }

                // Iterate over the field specifications for the current 'recordType'
                foreach ($recordData[$recordType] as $key => $value) {
                    // If 'key' is empty, set it to "undefined"
                    $key = (empty($key)) ? "undefined" : $key;

                    // Extract data for the field from the fixed-width positions specified in 'value'
                    $obj[$key] = substr($line, $value[0], $value[1]);
                }

                // Add the current record to the importRecords array
                array_push($importRecords[$recordType], $obj);
            }

            // Iterate over the imported records
            foreach ($importRecords as $table_name => $rows) {
                foreach ($rows as $column_name => $row_data) {
                    // Here goes the logic to import the extracted data to the database.
                    // For each Record Type we should have separate table.
                    // The column names are the description from the .csv file for each record type.

                }
            }

            // Convert the importRecords array to JSON format with pretty printing
            $jsonData = json_encode($importRecords, JSON_PRETTY_PRINT);

            // Define the path for the JSON file where the data will be saved
            $filePath = storage_path('files/extracted_data_2.json');

            // Attempt to write the JSON data to the specified file
            if (file_put_contents($filePath, $jsonData) === false) {
                die("Error writing to JSON file");
            } else {
                echo "Data saved to $filePath successfully.";
            }

            // Close the file when done
            fclose($file);
        }
    }
}
