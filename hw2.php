<?php

/*
CS 174 Assignment #2 - Classes and File Uploading

Kyle Hamilton -- February 19, 2024

This PHP application takes a .txt file of 400 numbers in a 20x20 matrix format as input
and finds the 5 adjacent numbers that multiply together to get the largest product. It then
takes that product and computes the sum of the factorial of each term of such product.
The application will reject any input that is not of .txt format or does not contain 400 numbers.
The application will change non-integer characters in the file to 0 and will still accept the file if it is
not in 20x20 format, even if it has whitespaces in between the characters.
*/

class fileOfNums
{

    const TOTAL_NUMS = 400; //total amount of numbers that should be in the file
    const TOTAL_IN_ROW = 20; //total amount of numbers that should be in each row of the array
    const NUM_OF_TERMS_TO_MULTIPLY = 5; //the amount of numbers to multiply together to find largest product
    
    public function file_input() {

        echo <<<_END
            <html><head><title>PHP Form Upload</title></head><body>
            <form method='post' action='hw2.php' enctype='multipart/form-data'>
            Select a TXT File:
            <input type='file' name='filename' size='10'>
            <input type='submit' value='Upload'></form>
        _END;
        if ($_FILES) {
            $name = $_FILES['filename']['name'];
            switch($_FILES['filename']['type']) {
                case 'text/plain' : $ext = 'txt'; break;
                default : $ext = ''; break;
            }
            if ($ext) {
                $n = "text.$ext";
                move_uploaded_file($_FILES['filename']['tmp_name'], $n);
                echo "Uploaded text '$name' as '$n':<br>";
                echo "<pre>";
                echo file_get_contents($n);
                echo "</pre>";
            }
            else echo "'$name' is not an accepted text file";
        }
        else echo "No text has been uploaded";
        echo "</body></html>";

    }

}

$fileOfNums = new fileOfNums();
$fileOfNums->file_input();


?>