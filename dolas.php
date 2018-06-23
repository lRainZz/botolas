<?php
// returns olasified text
function dolas($text) {

    $arr = explode(' ', $text);
    
    // delete olas
    if ($arr[0] == '/olas') {
        array_shift($arr);
        
        if (($arr[0] == '/olas') || ($arr[0] == '')) {
            return '';
        }        
    }

    $arr_out = array();

    foreach($arr as $word) {
        // strings containing empty, space or olas not allowed
        if ($word == "" || $word == " ") {
            continue;
        } elseif ((strpos(strtolower($word), 'olas') !== false) || strlen($word) <= 2) {
            array_push($arr_out, $word);
            continue;
        }

        $last_match = no_special_chars($word);

		// if word is uppercase so should olas
		if (strtoupper($word) == $word) {
		  $olasSuffix = 'OLAS';
		} else {
		  $olasSuffix = 'olas';
		}
		
		if ($last_match > 0) {
            $push = dolas_special_char_word($word, $last_match, $olasSuffix);
            array_push($arr_out, $push);
        } else {
            $push = del_vocals($word) . $olasSuffix;
            array_push($arr_out, $push);
        }
    }

    if (count($arr_out) > 0) {
        $text_out = implode(' ', $arr_out);
    } else {
        $text_out = $text;
    }

    return $text_out;
}

// olasifys words containing special chars
function dolas_special_char_word($word, $last_match, $olasSuffix) {
    $word_pre = substr($word, 0, -$last_match);
    $word_post = substr($word, -$last_match);

    // gets rid of olas in fornt of emojis or only special char words
    if (($word_pre == "" || $word_pre == " ") && ($word_post !== "" || $word_post !== " ")) {
        $push = del_vocals($word_post) . $olasSuffix;
    } else {
        $push = del_vocals($word_pre) . $olasSuffix . $word_post;
    }

    return $push;
}

// checks how many letters from the end of the word are special chars:
function no_special_chars($word){
    $last_match = 0;
    $regex = '/[^a-zA-Z0-9öäüßÖÄÜ]/';

    for($counter=1; $counter<=strlen($word); $counter++){
        if( preg_match($regex, substr($word, -$counter, 1))){
            $last_match = $counter;
        }
    }

    return $last_match;
}

// deletes vocals from end of words
// TODO: not deleting on last two chars vocals
function del_vocals($word){
    $regex = '/[aeiouAEIOU]/';

    if (preg_match($regex, substr($word, -1)) > 0){
        $word = substr($word, 0, strlen($word)-1);
    }

    return $word;
}