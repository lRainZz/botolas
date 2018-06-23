<?php

// returns json encoded inline answer
function createInlineAnswerSingleText($text) {
    $inline_answer = array(
        array(
            "type" => "article",
            "id" => "1",
            "title" => "Olasified text:",
            "description" => $text,
            "input_message_content" => array(
                "message_text" => $text
            )
        )
    );

    return json_encode($inline_answer);
}

function get_ascii_bullshit() {
    return
        '`    __          ____     __    _ __`'.PHP_EOL.
        '`   / /_  __  __/ / /____/ /_  (_) /_`'.PHP_EOL.
        '`  / __ \/ / / / / / ___/ __ \/ / __/`'.PHP_EOL.
        '` / /_/ / /_/ / / (__  ) / / / / /_`'.PHP_EOL.
        '`/_.___/\__,_/_/_/____/_/ /_/_/\__/`'.PHP_EOL.PHP_EOL.
        'If this doesn\'t show correct, tilt your device!';
/*'` _           _ _     _     _ _`'.PHP_EOL.
'`| |         | | |   | |   (_) |`'.PHP_EOL.
'`| |__  _   _| | |___| |__  _| |_`'.PHP_EOL.
'`| \'_ \| | | | | / __| \'_ \| | __|`'.PHP_EOL.
'`| |_) | |_| | | \__ \ | | | | |_ `'.PHP_EOL.
'`|_.__/ \__,_|_|_|___/_| |_|_|\__|`';*/


}