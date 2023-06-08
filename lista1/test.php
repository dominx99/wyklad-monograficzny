<?php

// Input the dataset
$data = [5.0, 18, 15.2, 5.0, 7.8, 13.5, 13.1];

// Convert the data array to a string representation
$dataString = json_encode($data);

// Prepare the command to run the Python script with the data as an argument
$command = "python3 2-2.py \"$dataString\"";

// Execute the command and capture the output
$output = shell_exec($command);

// Print the output
echo $output;
