<?php

include 'dolas.php';
include 'exmatriculation.php';
include 'misc.php';
include 'contains.php';
include 'config.php'; // quotes / API KEY / personal data etc.

$INFO =
    '+++ Version info on botolasbot: +++' . PHP_EOL . PHP_EOL
    . 'Botolas vers. 0.10 ' . PHP_EOL . ' + Last change: Olasifies everything in chat.' . PHP_EOL
    . 'Botolas vers. 0.20 ' . PHP_EOL . ' + Last change: Does now support commands.' . PHP_EOL
    . 'Botolas vers. 0.30 ' . PHP_EOL . ' + Last change: Inline mode implemented.' . PHP_EOL
    . 'Botolas vers. 1.00 ' . PHP_EOL . ' + Last change: Debug free inline version.' .PHP_EOL
    . 'Botolas vers. 1.01 ' . PHP_EOL . ' + Last change: Added some sweet commands.' .PHP_EOL
    . 'Botolas vers. 1.02 ' . PHP_EOL . ' + Last change: Added Paul.' .PHP_EOL
    . 'Botolas vers. 1.03 ' . PHP_EOL . ' + Last change: Added Tom.' . PHP_EOL
	. 'Botolas vers. 1.04 ' . PHP_EOL . ' + Last change: Fixed linebreak encodings.' . PHP_EOL 
	. 'Botolas vers. 1.05 ' . PHP_EOL . ' + Last change: Added tilt mode.' . PHP_EOL
	. 'Botolas vers. 1.06 ' . PHP_EOL . ' + Last change: added DHBW reacts.'. PHP_EOL
	. 'Botolas vers. 1.07 ' . PHP_EOL . ' + Last change: added Ricarda.'. PHP_EOL
	. 'Botolas vers. 1.08 ' . PHP_EOL . ' + Last change: generate md5 sum of files'. PHP_EOL
	. 'Botolas vers. 1.09 ' . PHP_EOL . ' + Last change: generate sha1 sum of files'. PHP_EOL
	. 'Botolas vers. 1.10 ' . PHP_EOL . ' + Last change: tweetolas added'. PHP_EOL
    . 'Botolas vers. 1.11 ' . PHP_EOL . ' + Last change: Embraced DSGVO, only chat able to use the bot is ' . PHP_EOL . '                "TIF15a Wurst Käse Bier Schnaps"' . PHP_EOL
    . 'Botolas vers. 1.12 ' . PHP_EOL . ' + Last change: Added corona help';

$dataHelper = new dataHelper();
$API_CALLS  = 'https://api.telegram.org/bot' . $dataHelper->API_KEY;
$FILE_CALLS = 'https://api.telegram.org/file/bot' . $dataHelper->API_KEY;

// get message contents in json format
$update = file_get_contents('php://input');
$update = json_decode($update, true);

// major variables for processing
$text = $update['message']['text'];
$file = $update['message']['document'];
$file_id = $update['message']['document']['file_id'];
$chat_id = $update['message']['chat']['id'];
$id_from = $update['message']['from']['id'];
$file_caption = $update['message']['caption'];
$user_from    = $update['message']['username'];

$inline_id   = $update['inline_query']['id'];
$inline_text = $update['inline_query']['query'];

$input_arr = explode(' ', $text);

$logging = false;

if ($logging) {
// logging w/o session
    if (!empty($text)) {
        doLog(date("Y-m-d H:i:s") . " | Message received, Chat_ID: " . $chat_id . " message: " . $text);
    }
}

if (($chat_id != $dataHelper->TIF_GROUP_ID ) && ($id_from != $dataHelper->DEV_ID)) {
	doLog("Unauthorized access " . $dataHelper->DEV_ID . ' - ' . $id_from);
	sendMessage('You are not allowed to use this bot!');
	exit;
}

function doLog($text) {
    file_put_contents("log.txt", $text . PHP_EOL, FILE_APPEND);
}

$command = $input_arr[0];

// filer and correct "@Botolasbot" command use
if (strstr($command, '@') && strstr($command, '/')) {
    $command_arr = explode('@', $command);
    $command = $command_arr[0];
}


// hash sum function
$file_caption_start = explode(' ', $file_caption);

$algo = $file_caption_start[0];

if ($algo == '/md5' || $algo == '/sha1') {
    if ($file_id != '' || $file_id != NULL) {
    
        $server_file = json_decode(getFile($file_id), true);
        
        $file_url = $FILE_CALLS . '/' . $server_file['result']['file_path'];

        $path_parts = pathinfo($file_url);

        $temp_file_name = 'tmp.' . $path_parts['extension'];

        file_put_contents($temp_file_name, fopen($file_url, 'r'));

        if ($algo == '/md5') {
            $hash = md5_file($temp_file_name); 
            $algo = 'md5';
        } else if ($algo == '/sha1') {
            $hash = sha1_file($temp_file_name);
            $algo = 'sha1';
        }
        
        unlink($temp_file_name);

        array_shift($file_caption_start);

        $file_caption = implode(' ', $file_caption_start);
    
        sendMessage('file name:  '. $GLOBALS['file']['file_name'] . PHP_EOL 
                   . $algo .' sum:  ' . $hash . PHP_EOL
                   .'caption:  ' . $file_caption);
    }    
}


try {
/*
commands - show available commands
exmat - [put name here] get an official exmatriculation
olas - [put text here] to olasify the text
version - get some version bla bla bla...
wow - be amazed by some sweet stickers
bullshit - send some ascii bullshit
paul - send a paul zite
tom - send a tom zite
tilt - send a triggered olas
ricarda - sends a meaningless message
md5 - genrates md5 sums of files
sha1 - generates sha1 sums of files
tweetolas - send a sweetolas tweetolas
corona - sends corona help
*/

	switch($command){
		case '/version':
			sendMessage($INFO);
			break;
		case '/olas':
			sendolas($text);
			break;
		case '/exmat':
			sendMessage(generate_exmatricl(get_name($input_arr)), 'Markdown');
			break;
		case '/wow':
			sendWowBoy();
			break;
		case '/commands':
			sendMessage( 'Available commands are:'. PHP_EOL . PHP_EOL
						.'/exmat [NAME_HERE]'.PHP_EOL
						.'/olas [TEXT_HERE]'.PHP_EOL
						.'/version'.PHP_EOL
						.'/commands'.PHP_EOL
						.'/wow'.PHP_EOL
						.'/paul'.PHP_EOL
						.'/tom'. PHP_EOL
						.'/tilt'. PHP_EOL
                        .'/ricarda'. PHP_EOL
                        .'/corona' . PHP_EOL
                        .'drag and drop a file with "/md5" as start of caption'. PHP_EOL
                        .'drag and drop a file with "/sha1" as start of caption');
			break;
		case '/bullshit':
			sendMessage(get_ascii_bullshit(), 'Markdown');
			break;
		case '/paul':
			sendZite('paul');
			break;
		case '/tom':
			sendZite('tom');
			break;
		case '/tilt':
			sendPhoto($dataHelper->triggeredPic);
			break;
		case '/ricarda':
            sendPhoto($dataHelper->ricardaPic, $dataHelper->ricardaText);
            break;
        case '/md5':
            sendMd5();
            break;
		case '/tweetolas':
			sendPhoto($dataHelper->tweetolas);
            break;
        case '/corona':
            sendPhoto($dataHelper->coronaPic);
            break;
		default:
			sendMessageOnTextFilter($text);
	}

} catch (Exception $e) {
	$error_log = fopen('error.txt', 'w');
	fwrite($error_log, 'ERR:' + $e->getMessage());
}

if(isset($inline_text) && isset($inline_id) && $inline_text !== '') {
    file_get_contents($GLOBALS['API_CALLS']
        . '/answerInlineQuery?inline_query_id=' . $inline_id
        . '&results=' . createInlineAnswerSingleText(dolas($inline_text))
        . '&cache_time=0'
    );
}

function sendMessageOnTextFilter($text) {
    global $dataHelper;

    if (checkForContains($text, '2.3')) {
        sendSticker($dataHelper->hereIGoKillingAgain);
    }

    if (checkForContains($text, 'dhbw')) {
        sendDHBWReact();
    }

    if (checkForContains($text, 'brüchi')) {
        sendPhoto($dataHelper->bruechiPic, $dataHelper->bruechiText);
    }

    if ($id_from == $dataHelper->OLAS_ID) {
        
        $dolasBitch = rand(1, 25);
        
        if ($dolasBitch == 1) {
            sendPhoto($dataHelper->olasBitchPic);
        } elseif ($dolasBitch % 5 == 0) {
            sendPhoto($dataHelper->getTomPicture, 'schnico wann merkst eig dass es niemanden tangiert');
        }
    }
	
	$randolas = rand(1, 50);
	
	if ($randolas == 25) {
		sendolas($text);
	}
}

function sendDHBWReact() {
    
    global $dataHelper;

	$pos = rand(1, 2);
	
	switch($pos) {
		case 1:
			sendMessage('DHBW - Die Hoden Baumeln Wieder');
			break;
		case 2:
			sendSticker($dataHelper->floorIsGoodOrganization);
			break;
	}
}


function sendWowBoy() {
    global $dataHelper;

    foreach($dataHelper->wowBoyArray as $sticker_id){
        sendSticker($sticker_id);
    }
}

function sendolas($text) {
    $dolased = dolas($text);
    
    if ($dolased !== '') {
        sendMessage($dolased);
    }
}

function sendZite ($ofWhom) {
    global $dataHelper;
    
    if ($ofWhom == 'tom') {
		sendphoto(getTomPicture(), getTomZite());
	} else if ($ofWhom == 'paul') {
		
		$sendZite = rand(0,20);
		
		if ($sendZite == 10) {  
            sendPhoto($dataHelper->paulSeenPhoto);
		} else {
            sendPhoto($dataHelper->paulPhoto, getPaulZite());	
		}	
	}
}

function getTomPicture() {
    global $dataHelper;
    $picNumber = rand(0, sizeof($dataHelper->tomPhotoArray)-1);
    return $dataHelper->tomPhotoArray[$picNumber];
}

function getTomZite() {
    global $dataHelper;
    $captionNumber = rand(0, sizeof($dataHelper->imTomArray)-1);
    return $dataHelper->imTomArray[$captionNumber];
}

function getPaulZite() {
    global $dataHelper;
    $captionNumber = rand(0, sizeof($dataHelper->imPaulArray)-1);
    return $dataHelper->imPaulArray[$captionNumber];
}

// API call sendMessage with given $text
function sendMessage($text, $parse_mode = 'HTML') {
    if(!($text == NULL || $text == '')) {
        $url = $GLOBALS['API_CALLS']
            . '/sendMessage'
            . '?chat_id=' . $GLOBALS['chat_id']
            . '&text=' . urlencode($text)
            . '&parse_mode=' . $parse_mode;

        file_get_contents($url);
    }
}

// API call sendSticker with given $sticker_id
function sendSticker($sticker_id) {
    if(!($sticker_id == NULL || $sticker_id == '')){
        $url = $GLOBALS['API_CALLS']
            .'/sendSticker'
            .'?chat_id='. $GLOBALS['chat_id']
            .'&sticker='. $sticker_id;

        file_get_contents($url);
    }
}

// API call sendPhoto with given $photo_id and $caption
function sendPhoto($photo_id, $caption = '') {
    if (!($photo_id == NULL)) {
        $url = $GLOBALS['API_CALLS']
            .'/sendPhoto'
            .'?chat_id=' . $GLOBALS['chat_id']
            .'&photo=' . $photo_id;

            if (!($caption == '')) {
                $url .= '&caption=' . urlencode($caption);
            }

        file_get_contents($url);
    }
}

// API call getFile with given file id
function getFile($file_id) {
    if (!($file_id == NULL)) {
        $url = $GLOBALS['API_CALLS']
            .'/getFile'
            .'?file_id=' . $GLOBALS['file_id'];

        return file_get_contents($url);
    }
}