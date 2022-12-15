<?php
function string_between_two_string($str, $starting_word, $ending_word)
{
	$subtring_start = strpos($str, $starting_word);
	//Adding the starting index of the starting word to
	//its length would give its ending index
	$subtring_start += strlen($starting_word);
	//Length of our required sub string
	$size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
	// Return the substring from the index substring_start of length size
	return substr($str, $subtring_start, $size);
}

function insert ($string, $keyword, $body) {
	return substr_replace($string, PHP_EOL . $body, strpos($string, $keyword) + strlen($keyword), 0);
}