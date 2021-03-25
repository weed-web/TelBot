<?php

    include("Telegram.php");
    date_default_timezone_set("Asia/Tehran");
    $token = "";
    $admin = [];
    $telegram = new Telegram($token);
    $text = $telegram->Text();
    $chat_id = $telegram->ChatID();
    $message_id = $telegram->MessageID();
    $callback_data = $telegram->Callback_Data();
    $callback_query = $telegram->Callback_Query();
    $user_id = $telegram->UserID();
    $menu = [["Total Users", "Ban User", "Unban User"], ["List Users", "Broadcast"]];
    if(isset($_GET['setWebhook'])) {echo file_get_contents("https://api.telegram.org/bot".$token."/setWebhook?url=https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);}
    if(isset($_GET['deleteWebhook'])) {echo file_get_contents("https://api.telegram.org/bot".$token."/deleteWebhook");}
    if(!is_dir(dirname(__FILE__).'/files')){mkdir(dirname(__FILE__).'/files');}
    if(!file_exists(dirname(__FILE__).'/files/admin.json')){$admz = fopen(dirname(__FILE__).'/files/admin.json', 'w'); fclose($admz);}
    if(!file_exists(dirname(__FILE__).'/files/users.json')){$user = fopen(dirname(__FILE__).'/files/users.json', 'w'); fclose($user);}
    $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
    if(is_null($users[strval($chat_id)])) {$users[$chat_id]["ban"] = 0; $chatz = fopen(dirname(__FILE__)."/files/users.json", "w"); fwrite($chatz, json_encode($users, JSON_PRETTY_PRINT)); fclose($chatz);}
    $admin_check = json_decode(file_get_contents(dirname(__FILE__).'/files/admin.json'), true);
    foreach((array)$admin as $sadmin) {if(is_null($admin_check[$sadmin])) {$flg = fopen(dirname(__FILE__).'/files/admin.json', 'w'); $admin_check[$sadmin] = array("Ban User" => 0, "Unban User" => 0, "Broadcast" => 0); fwrite($flg, json_encode($admin_check, JSON_PRETTY_PRINT)); fclose($flg);}}
    foreach((array)$admin_check as $wadmin => $key) {if(!in_array($wadmin, $admin)) {unset($admin_check[$wadmin]); unlink(dirname(__FILE__).'/files/admin.json'); $json = fopen(dirname(__FILE__).'/files/admin.json', 'w'); fwrite($json, json_encode($admin_check, JSON_PRETTY_PRINT)); fclose($json);}}

    if(in_array($user_id, $admin)) {
        $f = json_decode(file_get_contents(dirname(__FILE__).'/files/admin.json'), true);
        if($text == "/start") {
            $btn = $telegram->buildKeyBoard($menu);
            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "Welcome to Admin Panel !", "reply_markup" => $btn];
            $telegram->sendMessage($content);
        }
        elseif($text == "Back") {
            if($f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Ban User"] == 1 or $f[$chat_id]["Unban User"] == 1) {
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Ban User"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $btn = $telegram->buildKeyBoard($menu);
            $content = ["chat_id" => $chat_id, "text" => "Back to main menu", "reply_markup" => $btn];
            $telegram->sendMessage($content);
        }
        elseif($text == "Total Users") {
            $usrs = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            $content = ["chat_id" => $chat_id, "text" => 'User count : '.count((array)$usrs)];
            $telegram->sendMessage($content);
        }
        elseif($text == "Ban User") {
            $f[$chat_id]["Ban User"] = 1;
            if($f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Broadcast"] == 1) {
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send the chatid to ban user from bot", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "Unban User") {
            $f[$chat_id]["Unban User"] = 1;
            if($f[$chat_id]["Ban User"] == 1 or $f[$chat_id]["Broadcast"] == 1) {
                $f[$chat_id]["Ban User"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send the chatid to unban user from bot", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "List Users") {
            $usrz = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            if(count((array)$usrz) > 0) {
                $usrz_array = array_chunk($usrz, 10, TRUE);
                $nums = count((array)$usrz_array);
                $data = "Listing Saved Users\n\n";
                foreach((array)$usrz_array[0] as $key => $val) {
                    $data = $data."Profile Link: [Click to see](tg://user?id=".$key.")\nUser ChatId: `".$key."`\n\n";
                }
                $list = array(array($telegram->buildInlineKeyboardButton("<<","","usr_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_usr_1",""), $telegram->buildInlineKeyboardButton(">>","","usr_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                $keyb = $telegram->buildInlineKeyBoard($list);
                $content = ["chat_id" => $chat_id, "text" => $data, "parse_mode" => "Markdown", "reply_markup" => $keyb];
                $telegram->sendMessage($content);
            }
            else {
                $content = ["chat_id" => $chat_id, "text" => "There is no users to show"];
                $telegram->sendMessage($content);
            }
        }
        elseif($text == "Broadcast") {
            $f[$chat_id]["Broadcast"] = 1;
            if($f[$chat_id]["Ban User"] == 1 or $f[$chat_id]["Unban User"] == 1) {
                $f[$chat_id]["Ban User"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send your message to broadcast", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($callback_query != NULL and $callback_query != "") {
            $clbk = explode("_", $callback_data);
            if($clbk[0] == "usr") {
                $usrz = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
                $array = array_chunk($usrz, 10, TRUE);
                $nums = count((array)$array);
                if($nums == 0) {
                    $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => "There is no users to show"];
                    $telegram->editMessageText($content);
                }
                else {
                    if($clbk[1] == "next") {
                        $data = "Listing Saved Users\n\n";
                        if((int)$clbk[2] >= $nums) {
                            foreach((array)$array[$nums-1] as $key => $val) {
                                $data = $data."Profile Link: [Click to see](tg://user?id=".$key.")\nUser ChatId: `".$key."`\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","usr_previous_".$nums,""), $telegram->buildInlineKeyboardButton("Page ".$nums,"","page_usr_".$nums,""), $telegram->buildInlineKeyboardButton(">>","","usr_next_".$nums,"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "Markdown", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go forward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]] as $key => $val) {
                                $data = $data."Profile Link: [Click to see](tg://user?id=".$key.")\nUser ChatId: `".$key."`\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","usr_previous_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]+1),"","page_usr_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton(">>","","usr_next_".strval((int)$clbk[2]+1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "Markdown", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                    elseif($clbk[1] == "previous") {
                        $data = "Listing Saved Users\n\n";
                        if((int)$clbk[2] <= 1) {
                            foreach((array)$array[0] as $key => $val) {
                                $data = $data."Profile Link: [Click to see](tg://user?id=".$key.")\nUser ChatId: `".$key."`\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","usr_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_usr_1",""), $telegram->buildInlineKeyboardButton(">>","","usr_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "Markdown", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go backward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]-2] as $key => $val) {
                                $data = $data."Profile Link: [Click to see](tg://user?id=".$key.")\nUser ChatId: `".$key."`\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","usr_previous_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]-1),"","page_usr_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton(">>","","usr_next_".strval((int)$clbk[2]-1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "Markdown", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                }
            }
            elseif($clbk[0] == "page") {
                if($clbk[1] == "usr") {
                    $usrz = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
                    $content = ["callback_query_id" => $callback_query["id"], "text" => "Page ".$clbk[2]." of ".count((array)array_chunk($usrz, 10, TRUE))];
                    $telegram->answerCallbackQuery($content);
                }
            }
            elseif($callback_data == "Close") {
                $content = ["callback_query_id" => $callback_query["id"], "text" => "Closed"];
                $telegram->answerCallbackQuery($content);
                $content = ["chat_id" => $chat_id, "message_id" => $message_id];
                $telegram->deleteMessage($content);
            }
        }
        elseif(!empty($message_id) and $f[$chat_id]["Broadcast"] == 1) {
            $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            foreach((array)$users as $key => $val) {
                $content = ["chat_id" => (int)$key, "from_chat_id" => $chat_id, "message_id" => $message_id];
                $telegram->forwardMessage($content);
            }
            $content = ["chat_id" => $chat_id, "text" => "Your message successfully send to all of the users"];
            $telegram->sendMessage($content);
        }
        elseif($f[$chat_id]["Ban User"] == 1 and !empty($text)) {
            $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            if(isset($users[$text])) {
                if($users[$text]["ban"] == 0) {
                    $users[$text]["ban"] = 1;
                    $chatz = fopen(dirname(__FILE__)."/files/users.json", "w");
                    fwrite($chatz, json_encode($users, JSON_PRETTY_PRINT));
                    fclose($chatz);
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "parse_mode" => "Markdown", "text" => "User with `".$text."` id banned successfully"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "parse_mode" => "Markdown", "text" => "Currenlty the user with `".$text."` id is banned"];
                    $telegram->sendMessage($content);
                }
            }
            else {
                $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "User not found"];
                $telegram->sendMessage($content);
            }
        }
        elseif($f[$chat_id]["Unban User"] == 1 and !empty($text)) {
            $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            if(isset($users[$text])) {
                if($users[$text]["ban"] == 1) {
                    $users[$text]["ban"] = 0;
                    $chatz = fopen(dirname(__FILE__)."/files/users.json", "w");
                    fwrite($chatz, json_encode($users, JSON_PRETTY_PRINT));
                    fclose($chatz);
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "parse_mode" => "Markdown", "text" => "User with `".$text."` id unbanned successfully"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "parse_mode" => "Markdown", "text" => "Currenlty the user with `".$text."` is not banned"];
                    $telegram->sendMessage($content);
                }
            }
            else {
                $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "User not found"];
                $telegram->sendMessage($content);
            }
        }
        else {
            $file_headers = @get_headers($text);
            if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "Unvalid Link!"];
                $telegram->sendMessage($content);
            }
            else {
                $final_result = ouo($text);
                $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => $final_result, "disable_web_page_preview" => true];
                $telegram->sendMessage($content);
            }
        }
    }
    else {
        $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
        if($users[$chat_id]["ban"] == 0) {
            if($text == "/start") {
                $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "سلام!\nلینک‌های کوتاه خود را برای ربات ارسال کنید تا لینک اصلی برای شما ارسال شود."];
                $telegram->sendMessage($content);
            }
            elseif(!empty($text)) {
                $file_headers = @get_headers($text);
                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "لینک ارسالی معتبر نیست!"];
                    $telegram->sendMessage($content);
                }
                else {
                    $final_result = ouo($text);
                    $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => $final_result, "disable_web_page_preview" => true];
                    $telegram->sendMessage($content);
                }
            }
        }
        else {
            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "شما از ربات بن شده‌اید!"];
            $telegram->sendMessage($content);
        }
    }

    function getbetween($string, $start = '', $end = '') {if(strpos($string, $start)) {$startCharCount = strpos($string, $start) + strlen($start); $firstSubStr = substr($string, $startCharCount, strlen($string)); $endCharCount = strpos($firstSubStr, $end); if($endCharCount == 0) {$endCharCount = strlen($firstSubStr);} return substr($firstSubStr, 0, $endCharCount);} else {return '';}}
    function postrequest($url, $fields, $headers) {$ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_HEADER, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); return curl_exec($ch);}
    function ouo($url) {$surl = explode("/", $url); $ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_HEADER, true); $result = curl_exec($ch); $_token = getbetween($result, '<input name="_token" type="hidden" value="', '">'); $xtoken ='03AGdBq27aG8-DhLdx2leUYSAfc_LZN91MWJDmP0mJYMvIrB5Rs4qyT1OZOLxgod3XoK2GtcudVA8VEIBHycaVrh4lR1QBouZHMMNMKdTshSeKFQ-1ChyeMLNd2LAOcJnxta1lFMa0-oiBlqpfnP3TKuv11UfE5_12vWQC9Lyh5hyAdeODN4032yIc3kUuwMlaHac-p0CDaj4oa-NuRqi7TkxpUkdKo0rCDpRGN05VntnsZXMgQu-5Vj3ikwqsNfk7miziXwt9GgSQMLvGEkrBqRJuVLxy-ACOo5cvWTmTtB6snuDa8C_ZbJPhxQDiOb_6DK5j9bSDk0tC-OP0h7nCew_f9xLq75jFfxuGWl8AVdAKgHA60JoYwGqjIeH1wNaH8G5fRrjDs36zv91W4skgDAMZznw_9grLD9EjSvOy_pEL2jSUiVaKlCge6OxdvivK46xB-Dibu2RZ'; $vtoken = getbetween($result, '<input id="v-token" name="v-token" type="hidden" value="', '">'); $fields = '_token='.$_token.'&x-token='.$xtoken.'&v-token='.$vtoken; preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches); $cookies = array(); foreach($matches[1] as $item) {parse_str($item, $cookie); $cookies = array_merge($cookies, $cookie);} $headers = ['Host: '.$surl[2], 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0', 'Accept: text/html;q=0.5', 'Accept-Language: en-US,en;q=0.5', 'Content-Type: application/x-www-form-urlencoded', 'Origin: https://'.$surl[2], 'Connection: keep-alive', 'Referer: '.$url, 'Cookie: language='.$cookies['language'].'; __cfduid='.$cookies['__cfduid'].'; ouoio_session='.$cookies['ouoio_session'], 'Upgrade-Insecure-Requests: 1', 'Content-Length: '.strlen($fields)]; $cfduid = $cookies['__cfduid']; $result = postrequest('https://'.$surl[2].'/go/'.$surl[3], $fields, $headers); $fields = '_token='.$_token.'&x-token='; preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches); foreach($matches[1] as $item) {parse_str($item, $cookie); $cookies = array_merge($cookies, $cookie);} $headers = ['Host: '.$surl[2], 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0', 'Accept: text/html;q=0.5', 'Accept-Language: en-US,en;q=0.5', 'Content-Type: application/x-www-form-urlencoded', 'Origin: https://'.$surl[2], 'Connection: keep-alive', 'Referer: '.'https://'.$surl[2].'/go/'.$surl[3], 'Cookie: __cfduid='.$cfduid.'; ouoio_session='.$cookies['ouoio_session'].'; language='.$cookies['language'], 'Upgrade-Insecure-Requests: 1', 'Content-Length: '.strlen($fields)]; $result = postrequest('https://'.$surl[2].'/xreallcygo/'.$surl[3], $fields, $headers); return getbetween($result, '<title>Redirecting to ', '</title>');}

?>
