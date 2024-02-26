<?php

/*
CS 174 Assignment #2 - Classes and File Uploading

Kyle Hamilton -- February 19, 2024

This PHP application takes a .txt file of 400 numbers in a 20x20 matrix format as input
and finds the 5 adjacent numbers that multiply together to get the largest product. It then
takes that product and computes the sum of the factorial of each term of such product.
The application will reject any input that is not of .txt format or does not contain at least 400 characters.
The application will change non-integer characters in the file to 0 and will still accept the file if it is
not in 20x20 format, even if it has whitespaces in between the characters.
*/

class ComputationOfNumsFile
{

    const TOTAL_NUMS = 400; //total amount of numbers that should be in the file
    const TOTAL_IN_ROW = 20; //total amount of numbers that should be in each row of the array
    const NUM_OF_MULTIPLICANDS = 5; //the amount of numbers to multiply together to find largest product
    
    public static function file_input() {
        /*
        This function handles a file to be uploaded by the user. It only accepts files with the 
        .txt file extension and sanitizes the file by allowing only alphanumeric characters and the period.
        It then displays the text file that the user uploaded.
        return (string): uploaded file.
        */

        echo <<<_END
            <html><head><title>PHP Form Upload</title></head><body>
            <form method='post' action='hw2.php' enctype='multipart/form-data'>
            Select a TXT File:
            <input type='file' name='filename' size='10'>
            <input type='submit' value='Upload'></form>
        _END;

        $n = "";

        if ($_FILES) {
            $name = $_FILES['filename']['name'];
            switch($_FILES['filename']['type']) {
                case 'text/plain' : $ext = 'txt'; break;
                default : $ext = ''; break;
            }
            if ($ext) {
                $n = $_FILES['filename']['tmp_name'];
                $n = preg_replace("/[^A-Za-z0-9\\\\:.]/", "", $n); //sanitize temporary file name
                echo "Uploaded text '$name' as '$n':<br>";
                echo "<pre>";
                echo file_get_contents($n);
                echo "</pre>";
            }
            else echo "'$name' is not an accepted text file";
        }
        else echo "No text has been uploaded";
        echo "</body></html>";

        return $n;

    }

    public static function sanitize_file($file) {
        /*
        This function sanitizes the uploaded temporary file that the user supplied. It opens the file and sanitizes it by
        first removing any whitespace and newline characters then replacing any other non integer characters with a 0. 
        It then checks if the file contents contains at least 400 digits then it populates the 2d array with the digits.
        param file (string): the temporary file uploaded by user over the php server.
        return (2d array): 2d 20x20 array of digits only.
        */

        $fh = fopen($file, 'r+') or die("Failed to open file");
        $text = file_get_contents($file);

        if ($text !== false) {
            $text = preg_replace('/\s+/', '', $text); // Removes whitespace
            $text = preg_replace('/\D/', '0', $text); // Replaces non-integer characters with '0'
        } else die("Error in reading file contents.");

        $digits_2d_array = array();

        if (strlen($text) >= self::TOTAL_NUMS) { // Checks if file contains at least 400 digits
            $index = 0;

            for ($i = 0; $i < self::TOTAL_IN_ROW; $i++) {
                for ($j = 0; $j < self::TOTAL_IN_ROW; $j++) {
                    $digits_2d_array[$i][$j] = intval($text[$index]);
                    $index++;
                }
            }
        } else die("Error: the file does not contain at least 400 characters");

        fclose($fh);

        return $digits_2d_array;
    }

    public static function largest_product($digits) {
        /*
        This function finds the largest product between five adjacent digits in the supplied array. It uses the four helper
        functions to account for the possible adjacencies of the five digits. 
        It then finds the max of the four and extracts the multiplicands of the largest product and returns in a packaged array.
        param digits (2d array): the 2d 20x20 array of digits.
        return (array): the max product and its mulitplicands.
        */

        $horizontal_prod = self::horizontal_product($digits);
        $vertical_prod = self::vertical_product($digits);
        $diagonal_top_prod = self::diagonal_top_product($digits);
        $diagonal_bottom_prod = self::diagonal_bottom_product($digits);

        $products = array($horizontal_prod[0], $vertical_prod[0], $diagonal_top_prod[0], $diagonal_bottom_prod[0]);
        $max_product = max($products);
        $max_index = array_search($max_product, $products);
        $max_multiplicands = array();

        if ($max_index == 0) {
            $max_multiplicands = $horizontal_prod[1];
        } else if ($max_index == 1) {
            $max_multiplicands = $vertical_prod[1];
        } else if ($max_index == 2) {
            $max_multiplicands = $diagonal_top_prod[1];
        } else {
            $max_multiplicands = $diagonal_bottom_prod[1];
        }

        return array($max_product, $max_multiplicands);
    }

    private static function horizontal_product($digits) {
        /*
        This function finds the largest product between five horizontal adjacent digits in the supplied array.
        It iterates through each row and starts extracting each 5 horizontal adjacent digits and getting the 
        product of each set and comparing the current set to the current max set and reassigns based on the comparison.
        param digits (2d array): the 2d 20x20 array of digits.
        return (array): the horizontal max product and its mulitplicands.
        */

        $max_product = 0;
        $max_multiplicands = array();

        foreach ($digits as $row_index => $row) { //iterate over each row
            for ($i = 0; $i <= self::TOTAL_IN_ROW - self::NUM_OF_MULTIPLICANDS; $i++) { //start extracting
                $product = 1;
                $multiplicands = array();

                for ($j = 0; $j < self::NUM_OF_MULTIPLICANDS; $j++) { //extract
                    $product *= $row[$i + $j];
                    $multiplicands[] = $row[$i + $j];
                }

                if ($product > $max_product) {
                    $max_product = $product;
                    $max_multiplicands = $multiplicands;
                }
            }
        }

        return array($max_product, $max_multiplicands);
    }

    private static function vertical_product($digits) {
        /*
        This function finds the largest product between five vertical adjacent digits in the supplied array.
        It iterates through each row and starts extracting each 5 vertical adjacent digits and getting the 
        product of each set and comparing the current set to the current max set and reassigns based on the comparison.
        param digits (2d array): the 2d 20x20 array of digits.
        return (array): the vertical max product and its mulitplicands.
        */

        $max_product = 0;
        $max_multiplicands = array();

        for ($col = 0; $col < self::TOTAL_IN_ROW; $col++) { //get column
            for ($row = 0; $row <= self::TOTAL_IN_ROW - self::NUM_OF_MULTIPLICANDS; $row++) { //start extracting
                $product = 1;
                $multiplicands = array();
                
                for ($i = 0; $i < self::NUM_OF_MULTIPLICANDS; $i++) { //extract
                    $product *= $digits[$row + $i][$col];
                    $mulitplicands[] = $digits[$row + $i][$col];
                }
                
                if ($product > $max_product) {
                    $max_product = $product;
                    $max_multiplicands = $multiplicands;
                }
            }
        }

        return array($max_product, $max_multiplicands);
    }

    private static function diagonal_top_product($digits) {
        /*
        This function finds the largest product between five top diagonal adjacent digits in the supplied array.
        It iterates through each row and starts extracting each 5 top diagonal adjacent digits and getting the 
        product of each set and comparing the current set to the current max set and reassigns based on the comparison.
        param digits (2d array): the 2d 20x20 array of digits.
        return (array): the top diagonal max product and its mulitplicands.
        */

        $max_product = 0;
        $max_multiplicands = array();

        for ($row = 0; $row <= self::TOTAL_IN_ROW - self::NUM_OF_MULTIPLICANDS; $row++) { //get row
            for ($col = 0; $col <= self::TOTAL_IN_ROW - self::NUM_OF_MULTIPLICANDS; $col++) { //get column
                $product = 1;
                $multiplicands = array();
                
                for ($i = 0; $i < self::NUM_OF_MULTIPLICANDS; $i++) { //extract using identical vertex
                    $product *= $digits[$row + $i][$col + $i];
                    $mulitplicands[] = $digits[$row + $i][$col + $i];
                }
                
                if ($product > $max_product) {
                    $max_product = $product;
                    $max_multiplicands = $multiplicands;
                }
            }
        }

        return array($max_product, $max_multiplicands);
    }

    private static function diagonal_bottom_product($digits) {
        /*
        This function finds the largest product between five bottom diagonal adjacent digits in the supplied array.
        It iterates through each row and starts extracting each 5 bottom diagonal adjacent digits and getting the 
        product of each set and comparing the current set to the current max set and reassigns based on the comparison.
        param digits (2d array): the 2d 20x20 array of digits.
        return (array): the bottom diagonal max product and its mulitplicands.
        */

        $max_product = 0;
        $max_multiplicands = array();

        for ($row = self::NUM_OF_MULTIPLICANDS - 1; $row < self::TOTAL_IN_ROW; $row++) { //get row
            for ($col = 0; $col <= self::TOTAL_IN_ROW - self::NUM_OF_MULTIPLICANDS; $col++) { //get column
                $product = 1;
                $multiplicands = array();
                
                for ($i = 0; $i < self::NUM_OF_MULTIPLICANDS; $i++) { //extract using offset vertex
                    $product *= $digits[$row - $i][$col + $i];
                    $mulitplicands[] = $digits[$row - $i][$col + $i];
                }
                
                if ($product > $max_product) {
                    $maxProduct = $product;
                    $max_multiplicands = $multiplicands;
                }
            }
        }

        return array($max_product, $max_multiplicands);
    }

    public static function factorial_sum($num) {
        /*
        This function calculates the sum of the factorial of each of the digits within the largest product computed earlier.
        It first splits the number to get each of the digits and then uses the factorial helper function to add to the sum.
        param num (int): the largest adjacent product.
        return (array): the computed sum and the numbers used in that sum.
        */

        $num_array = str_split($num);
        $sum = 0;
        foreach($num_array as $digit) {
            $sum += self::factorial($digit);
        }

        return array($sum, $num_array);
    }

    private static function factorial($n) {
        /*
        This function uses recursion to compute the factorial of the given integer.
        param n (int): number to be factoialed.
        return (int): computed factorial.
        */

        if ($n == 0) {
            return 1;
        } else {
            return $n * self::factorial($n - 1);
        }
    }

    public static function print_output($prod_array, $fac_array) {
        /*
        This function extracts the data from the arrays and outputs in a formatted string to clearly display to the user the
        result of the supplied text file.
        param prod_array (array): array containing the largest product and its multiplicands.
        param fac_array (array): array containing the computed factorial sum and its numbers.
        return (string): The output string containing all the info to display the largest product and the computed factorial sum.
        */

        $largest_prod = $prod_array[0];
        $largest_multiplicands = $prod_array[1];
        $largest_sum = $fac_array[0];
        $largest_sum_array = $fac_array[1];
        echo "Largest product: " . implode(" * ", $largest_multiplicands) . " = $largest_prod - Sum of Factorial = " . implode("! + ", $largest_sum_array) . "! = $largest_sum";
    }

}

$obj = new ComputationOfNumsFile();
$file = $obj::file_input();
$digits_array = $obj::sanitize_file($file);
$largest_prod = $obj::largest_product($digits_array);
$fac_sum = $obj::factorial_sum($largest_prod[0]);
$obj::print_output($largest_prod, $fac_sum);

?>