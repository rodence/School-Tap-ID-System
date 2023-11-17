<?php
$word = ".EN.";
$mystring = "AM.EN.001";

// Test if string contains the word 
if (strpos($mystring, $word) !== false) {
    echo "Word Found!";
} else {
    echo "Word Not Found!";
}
