<?php

/*
CS 174 Assignment #4 - PHP + MySQL

Kyle Hamilton -- March 24, 2024

This PHP application takes a .txt file of transactions that the user has
input and must update the balance of said user with the user's input email.
The email and balance of the user will be stored in a MySQL database, and will
display the balance of all registered users.
*/

class LRU_Cache {

    private $cache_size;

    public function __construct($cache_size) {
    /*
    This constructor initializes the cache size property.
    param cache_size (int): a positive integer to set cache size.
    */

        $this->cache_size = $cache_size;
    }

    public static function file_input() {
        /*
        This function handles a file to be uploaded by the user. It only accepts files with the 
        .txt file extension and sanitizes the file by allowing only alphanumeric characters and the period.
        It then displays the text file that the user uploaded.
        return (string): uploaded file.
        */

        echo <<<_END
            <html><head><title>PHP Form Upload</title></head><body>
            <form method='post' action='midterm1.php' enctype='multipart/form-data'>
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
            }
            else echo "'$name' is not an accepted text file";
        }
        else echo "No text has been uploaded";
        echo "</body></html>";

        return $n;

    }

    public function sanitize_file($file) {
        /*
        This function sanitizes the uploaded temporary file that the user supplied. It opens the file and sanitizes it by
        first removing any whitespace and newline characters then replacing any other non integer characters with a 0. 
        It then checks if the file contents contains at least 400 digits then it populates the 2d array with the digits.
        param file (string): the temporary file uploaded by user over the php server.
        return (2d array): 2d 20x20 array of digits only.
        

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
        */
        echo $this->cache_size;
    }

    private function is_full() {
        echo "";
    }

    function get($key) {
        echo "";
    }

    private function reset() {
        echo "";
    }

    function put($key, $value, $reset) {
        echo "";
    }

    function test_put() {
        echo "";
    }


}

$cache = new LRU_Cache(2);
$file = $cache::file_input();
$cache->sanitize_file($file);


?>