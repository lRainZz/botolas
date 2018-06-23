<?php

//returns ascii exmatriculation with given $name
function generate_exmatricl($name) {
    //34 chars between #  #
    $exmatricl =
        '`####################################`'.PHP_EOL
        .'`####################################`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#         Congratulations,         #`'.PHP_EOL
        .           get_name_line($name)         .PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#           This is your           #`'.PHP_EOL
        .'`#   > official exmatriculation <   #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#  You will not be able to study   #`'.PHP_EOL
        .'`#   > Applied computer science <   #`'.PHP_EOL
        .'`#           any further.           #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#  Kind regards                    #`'.PHP_EOL
        .'`#  G. Bethlen                      #`'.PHP_EOL
        .'`#  U. Baum                         #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#  PS: Don\'t come back please.     #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`#                                  #`'.PHP_EOL
        .'`####################################`'.PHP_EOL
        .'`####################################`'.PHP_EOL.PHP_EOL.
        'If this doesn\'t show correct, tilt your device!';

    /*$exmatricl = 'Congratulations,' .PHP_EOL .
        $name .PHP_EOL.PHP_EOL.
        'You have been exmatriculated.' .PHP_EOL.PHP_EOL.
        'Kind regards,' . PHP_EOL .
        'G. Bethlen' .PHP_EOL.
        'U. Baum' .PHP_EOL.PHP_EOL.
        'PS: Please don\'t come back.';*/
    return $exmatricl;
}

// divides name input from command
function get_name($arr_of_char){
    array_shift($arr_of_char);
    return implode(' ', $arr_of_char);
}

// calculates lines for exmatriculation
function get_name_line($name) {
    $arr_word = explode(' ', $name);
    $line_arr = array();
    $name_line = '';

    //  # 34 spaces #
    if (strlen($name) > 32) {
        if (count($arr_word) == 1) {
            $line_arr = cut_name($name);
        } elseif(count($arr_word) > 1) {
            $count = 0;
            foreach($arr_word as $word) {
                if(strlen($word) <= 32) {
                    array_push($line_arr, $word);
                } elseif(strlen($word) > 32) {
                    $splice_arr = cut_name($word);
                    foreach($splice_arr as $splice){
                        array_push($line_arr, $splice);
                    }
                }
            }
        }

        foreach($line_arr as $line){
            $name_line .= '`#' . str_pad($line, 34, ' ', STR_PAD_BOTH) . '#`' . PHP_EOL;
        }

    } else {
        $name_line = '`#' . str_pad($name, 34, ' ', STR_PAD_BOTH) . '#`';
    }

    return $name_line;
}

function cut_name($name){
    $line_arr = array();

    for($i = 1; $i <= ceil((strlen($name) /32)); $i++) {
        $curr_line = substr($name, (($i*32)-32), 32);
        array_push($line_arr, $curr_line);
    }

    return $line_arr;
}