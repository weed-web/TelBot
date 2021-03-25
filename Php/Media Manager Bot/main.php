<?php

    include("Telegram.php");
    date_default_timezone_set("Asia/Tehran");
    $token = "1111111:AAAAAABBBBBBBB_CCCCCCCCC"; # put your robot token here (remove example)
    $admin = [111111, 222222]; # put your robot admins chatid here (remove examples)
    $telegram = new Telegram($token);
    $text = $telegram->Text();
    $chat_id = $telegram->ChatID();
    $message_id = $telegram->MessageID();
    $callback_data = $telegram->Callback_Data();
    $callback_query = $telegram->Callback_Query();
    $callback_chat_id = $telegram->Callback_ChatID();
    $reply_to_message_id = $telegram->ReplyToMessageID();
    $caption = $telegram->Caption();
    $user_id = $telegram->UserID();
    $msgType = $telegram->getUpdateType();
    $vid_id = $telegram->GetVidzId();
    $vid_uid = $telegram->GetVidzUid();
    $gif_id = $telegram->GetGifzId();
    $gif_uid = $telegram->GetGifzUid();
    $pht_id = $telegram->GetPhotoId();
    $pht_uid = $telegram->GetPhotoUid();
    $result = $telegram->getData();
    $menu = [["Add Media", "Delete Media"], ["Total Videos", "Total Gifs", "Total Photos"], ["Total Users", "Ban User", "Unban User", "List Users"], ["Rename Caption", "Set Default Caption"], ["List Videos", "List Gifs", "List Photos", "Broadcast"]];
    if(isset($_GET['setWebhook'])) {echo file_get_contents("https://api.telegram.org/bot".$token."/setWebhook?url=https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);}
    if(isset($_GET['deleteWebhook'])) {echo file_get_contents("https://api.telegram.org/bot".$token."/deleteWebhook");}
    if(!is_dir(dirname(__FILE__).'/files')){mkdir(dirname(__FILE__).'/files');}
    if(!file_exists(dirname(__FILE__).'/files/admin.json')){$admz = fopen(dirname(__FILE__).'/files/admin.json', 'w'); fclose($admz);}
    if(!file_exists(dirname(__FILE__).'/files/videos.json')){$vidz = fopen(dirname(__FILE__).'/files/videos.json', 'w'); fclose($vidz);}
    if(!file_exists(dirname(__FILE__).'/files/gifs.json')){$gifz = fopen(dirname(__FILE__).'/files/gifs.json', 'w'); fclose($gifz);}
    if(!file_exists(dirname(__FILE__).'/files/photos.json')){$phtz = fopen(dirname(__FILE__).'/files/photos.json', 'w'); fclose($phtz);}
    if(!file_exists(dirname(__FILE__).'/files/users.json')){$user = fopen(dirname(__FILE__).'/files/users.json', 'w'); fclose($user);}
    if(!file_exists(dirname(__FILE__).'/files/default_caption.txt')){$cptn = fopen(dirname(__FILE__).'/files/default_caption.txt', 'w'); fclose($cptn);}
    $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
    if(is_null($users[strval($chat_id)])) {$users[$chat_id]["ban"] = 0; $chatz = fopen(dirname(__FILE__)."/files/users.json", "w"); fwrite($chatz, json_encode($users, JSON_PRETTY_PRINT)); fclose($chatz);}
    $admin_check = json_decode(file_get_contents(dirname(__FILE__).'/files/admin.json'), true);
    foreach((array)$admin as $sadmin) {if(is_null($admin_check[$sadmin])) {$flg = fopen(dirname(__FILE__).'/files/admin.json', 'w'); $admin_check[$sadmin] = array("Add Media" => 0, "Delete Media" => 0, "Rename Caption" => 0, "Set Default Caption" => 0, "Ban User" => 0, "Unban User" => 0, "Broadcast" => 0); fwrite($flg, json_encode($admin_check, JSON_PRETTY_PRINT)); fclose($flg);}}
    foreach((array)$admin_check as $wadmin => $key) {if(!in_array($wadmin, $admin)) {unset($admin_check[$wadmin]); unlink(dirname(__FILE__).'/files/admin.json'); $json = fopen(dirname(__FILE__).'/files/admin.json', 'w'); fwrite($json, json_encode($admin_check, JSON_PRETTY_PRINT)); fclose($json);}}

    if(in_array($user_id, $admin)) {
        $f = json_decode(file_get_contents(dirname(__FILE__).'/files/admin.json'), true);
        if($text == "/start") {
            $btn = $telegram->buildKeyBoard($menu);
            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "Welcome to Admin Panel !", "reply_markup" => $btn];
            $telegram->sendMessage($content);
        }
        elseif($text == "Add Media") {
            $f[$chat_id]["Add Media"] = 1;
            if($f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Ban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send the file to add", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "Delete Media") {
            $f[$chat_id]["Delete Media"] = 1;
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] = 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Ban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send the file to delete", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "Rename Caption") {
            $f[$chat_id]["Rename Caption"] = 1;
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] = 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Ban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            $content = ["chat_id" => $chat_id, "text" => "Please send the file to rename the caption", "reply_markup" => $btn];
            $telegram->sendMessage($content);
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "Set Default Caption") {
            $f[$chat_id]["Set Default Caption"] = 1;
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or $f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Ban User"] = 0;
                $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                fclose($file);
            }
            $dcap = file_get_contents(dirname(__FILE__).'/files/default_caption.txt');
            $content = [["Back"]];
            $btn = $telegram->buildKeyBoard($content);
            if($dcap == "") {
                $content = ["chat_id" => $chat_id, "text" => "Please send your text to use as files static caption (it can be your channel id or etc.)\nCurrently you don't have any caption", "reply_markup" => $btn];
                $telegram->sendMessage($content);
            }
            else {
                $content = ["chat_id" => $chat_id, "text" => "Please send your text to use as files static caption (it can be your channel id or etc.)\nYour current caption is : `".$dcap."`", "reply_markup" => $btn];
                $telegram->sendMessage($content);
            }
            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
            fclose($file);
        }
        elseif($text == "Total Videos") {
            $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
            $content = ["chat_id" => $chat_id, "text" => 'Video count : '.count((array)$vidz)];
            $telegram->sendMessage($content);
        }
        elseif($text == "Total Gifs") {
            $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
            $content = ["chat_id" => $chat_id, "text" => 'Gif count : '.count((array)$gifz)];
            $telegram->sendMessage($content);
        }
        elseif($text == "Total Photos") {
            $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
            $content = ["chat_id" => $chat_id, "text" => 'Photo count : '.count((array)$gifz)];
            $telegram->sendMessage($content);
        }
        elseif($text == "Total Users") {
            $usrs = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
            $content = ["chat_id" => $chat_id, "text" => 'User count : '.count((array)$usrs)];
            $telegram->sendMessage($content);
        }
        elseif($text == "Ban User") {
            $f[$chat_id]["Ban User"] = 1;
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Unban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
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
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Ban User"] = 0;
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
        elseif($text == "Broadcast") {
            $f[$chat_id]["Broadcast"] = 1;
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Unban User"] == 1 or $f[$chat_id]["Ban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
                $f[$chat_id]["Unban User"] = 0;
                $f[$chat_id]["Ban User"] = 0;
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
        elseif($text == "List Videos") {
            $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
            if(count((array)$vidz) > 0) {
                $vidz_array = array_chunk($vidz, 10, TRUE);
                $nums = count((array)$vidz_array);
                $data = "Listing Saved Videos\n\n";
                foreach((array)$vidz_array[0] as $key => $val) {
                    $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                }
                $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_vid_1",""), $telegram->buildInlineKeyboardButton(">>","","vid_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                $keyb = $telegram->buildInlineKeyBoard($list);
                $content = ["chat_id" => $chat_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                $telegram->sendMessage($content);
            }
            else {
                $content = ["chat_id" => $chat_id, "text" => "There is no video to show"];
                $telegram->sendMessage($content);
            }
        }
        elseif($text == "List Gifs") {
            $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
            if(count((array)$vidz) > 0) {
                $vidz_array = array_chunk($vidz, 10, TRUE);
                $nums = count((array)$vidz_array);
                $data = "Listing Saved Gifs\n\n";
                foreach((array)$vidz_array[0] as $key => $val) {
                    $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                }
                $list = array(array($telegram->buildInlineKeyboardButton("<<","","gif_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_gif_1",""), $telegram->buildInlineKeyboardButton(">>","","gif_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                $keyb = $telegram->buildInlineKeyBoard($list);
                $content = ["chat_id" => $chat_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                $telegram->sendMessage($content);
            }
            else {
                $content = ["chat_id" => $chat_id, "text" => "There is no gif to show"];
                $telegram->sendMessage($content);
            }
        }
        elseif($text == "List Photos") {
            $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
            if(count((array)$vidz) > 0) {
                $vidz_array = array_chunk($vidz, 10, TRUE);
                $nums = count((array)$vidz_array);
                $data = "Listing Saved Photos\n\n";
                foreach((array)$vidz_array[0] as $key => $val) {
                    $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                }
                $list = array(array($telegram->buildInlineKeyboardButton("<<","","pht_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_pht_1",""), $telegram->buildInlineKeyboardButton(">>","","pht_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                $keyb = $telegram->buildInlineKeyBoard($list);
                $content = ["chat_id" => $chat_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                $telegram->sendMessage($content);
            }
            else {
                $content = ["chat_id" => $chat_id, "text" => "There is no photo to show"];
                $telegram->sendMessage($content);
            }
        }
        elseif($text == "Back") {
            if($f[$chat_id]["Add Media"] == 1 or $f[$chat_id]["Delete Media"] == 1 or $f[$chat_id]["Broadcast"] == 1 or $f[$chat_id]["Rename Caption"] == 1 or gettype($f[$chat_id]["Rename Caption"]) == "string" or $f[$chat_id]["Set Default Caption"] == 1 or $f[$chat_id]["Ban User"] == 1 or $f[$chat_id]["Unban User"] == 1) {
                $f[$chat_id]["Add Media"] = 0;
                $f[$chat_id]["Delete Media"] = 0;
                $f[$chat_id]["Broadcast"] = 0;
                $f[$chat_id]["Rename Caption"] = 0;
                $f[$chat_id]["Set Default Caption"] = 0;
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
        elseif($callback_query != NULL and $callback_query != "") {
            $clbk = explode("_", $callback_data);
            if($clbk[0] == "vid") {
                $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
                $array = array_chunk($vidz, 10, TRUE);
                $nums = count((array)$array);
                if($nums == 0) {
                    $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => "There is no video to show"];
                    $telegram->editMessageText($content);
                }
                else {
                    if($clbk[1] == "next") {
                        $data = "Listing Saved Videos\n\n";
                        if((int)$clbk[2] >= $nums) {
                            foreach((array)$array[$nums-1] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_".$nums,""), $telegram->buildInlineKeyboardButton("Page ".$nums,"","page_vid_".$nums,""), $telegram->buildInlineKeyboardButton(">>","","vid_next_".$nums,"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go forward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]+1),"","page_vid_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton(">>","","vid_next_".strval((int)$clbk[2]+1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                    elseif($clbk[1] == "previous") {
                        $data = "Listing Saved Videos\n\n";
                        if((int)$clbk[2] <= 1) {
                            foreach((array)$array[0] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_vid_1",""), $telegram->buildInlineKeyboardButton(">>","","vid_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go backward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]-2] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]-1),"","page_vid_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton(">>","","vid_next_".strval((int)$clbk[2]-1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                }
            }
            elseif($clbk[0] == "gif") {
                $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
                $array = array_chunk($gifz, 10, TRUE);
                $nums = count((array)$array);
                if($nums == 0) {
                    $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => "There is no gif to show"];
                    $telegram->editMessageText($content);
                }
                else {
                    if($clbk[1] == "next") {
                        $data = "Listing Saved Gifs\n\n";
                        if((int)$clbk[2] >= $nums) {
                            foreach((array)$array[$nums-1] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","gif_previous_".$nums,""), $telegram->buildInlineKeyboardButton("Page ".$nums,"","page_gif_".$nums,""), $telegram->buildInlineKeyboardButton(">>","","gif_next_".$nums,"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go forward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","gif_previous_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]+1),"","page_gif_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton(">>","","gif_next_".strval((int)$clbk[2]+1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                    elseif($clbk[1] == "previous") {
                        $data = "Listing Saved Gifs\n\n";
                        if((int)$clbk[2] <= 1) {
                            foreach((array)$array[0] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","gif_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_gif_1",""), $telegram->buildInlineKeyboardButton(">>","","gif_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go backward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]-2] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","vid_previous_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]-1),"","page_vid_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton(">>","","vid_next_".strval((int)$clbk[2]-1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                }
            }
            elseif($clbk[0] == "pht") {
                $phtz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                $array = array_chunk($phtz, 10, TRUE);
                $nums = count((array)$array);
                if($nums == 0) {
                    $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => "There is no photo to show"];
                    $telegram->editMessageText($content);
                }
                else {
                    if($clbk[1] == "next") {
                        $data = "Listing Saved Photos\n\n";
                        if((int)$clbk[2] >= $nums) {
                            foreach((array)$array[$nums-1] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","pht_previous_".$nums,""), $telegram->buildInlineKeyboardButton("Page ".$nums,"","page_pht_".$nums,""), $telegram->buildInlineKeyboardButton(">>","","pht_next_".$nums,"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go forward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","pht_previous_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]+1),"","page_pht_".strval((int)$clbk[2]+1),""), $telegram->buildInlineKeyboardButton(">>","","pht_next_".strval((int)$clbk[2]+1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                    elseif($clbk[1] == "previous") {
                        $data = "Listing Saved Photos\n\n";
                        if((int)$clbk[2] <= 1) {
                            foreach((array)$array[0] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","pht_previous_1",""), $telegram->buildInlineKeyboardButton("Page 1","","page_pht_1",""), $telegram->buildInlineKeyboardButton(">>","","pht_next_1","")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                            $content = ["callback_query_id" => $callback_query["id"], "text" => "Can't go backward"];
                            $telegram->answerCallbackQuery($content);
                        }
                        else {
                            foreach((array)$array[(int)$clbk[2]-2] as $key => $val) {
                                $data = $data."File Link: "."<a href='t.me/VasayaBot?start=".$key."'>Click to see</a>\n\n";
                            }
                            $list = array(array($telegram->buildInlineKeyboardButton("<<","","pht_previous_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton("Page ".strval((int)$clbk[2]-1),"","page_pht_".strval((int)$clbk[2]-1),""), $telegram->buildInlineKeyboardButton(">>","","pht_next_".strval((int)$clbk[2]-1),"")), array($telegram->buildInlineKeyboardButton("Close","","Close","")));
                            $keyb = $telegram->buildInlineKeyBoard($list);
                            $content = ["chat_id" => $chat_id, "message_id" => $message_id, "text" => $data, "parse_mode" => "HTML", "reply_markup" => $keyb];
                            $telegram->editMessageText($content);
                        }
                    }
                }
            }
            elseif($clbk[0] == "usr") {
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
                switch ($clbk[1]) {
                    case "vid":
                        $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
                        $content = ["callback_query_id" => $callback_query["id"], "text" => "Page ".$clbk[2]." of ".count((array)array_chunk($vidz, 10, TRUE))];
                        $telegram->answerCallbackQuery($content);
                        break;
                    case "gif":
                        $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
                        $content = ["callback_query_id" => $callback_query["id"], "text" => "Page ".$clbk[2]." of ".count((array)array_chunk($gifz, 10, TRUE))];
                        $telegram->answerCallbackQuery($content);
                        break;
                    case "pht":
                        $phtz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                        $content = ["callback_query_id" => $callback_query["id"], "text" => "Page ".$clbk[2]." of ".count((array)array_chunk($phtz, 10, TRUE))];
                        $telegram->answerCallbackQuery($content);
                        break;
                    case "usr":
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
        elseif($vid_uid != NULL and $vid_id != NULL and $f[$chat_id]["Rename Caption"] != 1 and $f[$chat_id]["Broadcast"] != 1) {
            $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
            if($f[$chat_id]["Add Media"] == 1) {
                $fff = 0;
                foreach((array)$vidz as $key => $val) {
                        if($key == "V_".$vid_uid) {
                            $fff = 1;
                        }
                }
                if($fff == 0) {
                    $vidz = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
                    $vidz["V_".$vid_uid]["file_id"] = $vid_id;
                    $vidz["V_".$vid_uid]["caption"] = $caption;
                    unlink(dirname(__FILE__).'/files/videos.json');
                    $vidz_file = fopen(dirname(__FILE__).'/files/videos.json', 'a+');
                    fwrite($vidz_file, json_encode($vidz, JSON_PRETTY_PRINT));
                    fclose($vidz_file);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File added to the database"."\n"."Accessing file with t.me/VasayaBot?start="."V_".$vid_uid." link"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File existed in the database"."\n"."Accessing file with t.me/VasayaBot?start=".$key." link"];
                    $telegram->sendMessage($content);
                }
            }
            elseif($f[$chat_id]["Delete Media"] == 1) {
                if(isset($vidz["V_".$vid_uid])) {
                    unset($vidz["V_".$vid_uid]);
                    unlink(dirname(__FILE__).'/files/videos.json');
			        $json = fopen(dirname(__FILE__).'/files/videos.json', 'w');
			        fwrite($json, json_encode($vidz, JSON_PRETTY_PRINT));
			        fclose($json);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File deleted successfully"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                    $telegram->sendMessage($content);
                }
            }
        }
        elseif($gif_uid != NULL and $gif_id != NULL and $f[$chat_id]["Rename Caption"] != 1 and $f[$chat_id]["Broadcast"] != 1) {
            $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
            if($f[$chat_id]["Add Media"] == 1) {
                $fff = 0;
                foreach((array)$gifz as $key => $val) {
                        if($key == "G_".$gif_uid) {
                            $fff = 1;
                        }
                }
                if($fff == 0) {
                    $gifz = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
                    $gifz["G_".$gif_uid]["file_id"] = $gif_id;
                    $gifz["G_".$gif_uid]["caption"] = $caption;
                    unlink(dirname(__FILE__).'/files/gifs.json');
                    $gifz_file = fopen(dirname(__FILE__).'/files/gifs.json', 'a+');
                    fwrite($gifz_file, json_encode($gifz, JSON_PRETTY_PRINT));
                    fclose($gifz_file);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File added to the database"."\n"."Accessing file with t.me/VasayaBot?start="."G_".$gif_uid." link"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File existed in the database"."\n"."Accessing file with t.me/VasayaBot?start=".$key." link"];
                    $telegram->sendMessage($content);
                }
            }
            elseif($f[$chat_id]["Delete Media"] == 1) {
                if(isset($gifz["G_".$gif_uid])) {
                    unset($gifz["G_".$gif_uid]);
                    unlink(dirname(__FILE__).'/files/gifs.json');
			        $json = fopen(dirname(__FILE__).'/files/gifs.json', 'w');
			        fwrite($json, json_encode($gifz, JSON_PRETTY_PRINT));
			        fclose($json);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File deleted successfully"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                    $telegram->sendMessage($content);
                }
            }
        }
        elseif($pht_uid != NULL and $pht_id != NULL and $f[$chat_id]["Rename Caption"] != 1 and $f[$chat_id]["Broadcast"] != 1) {
            $phtz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
            if($f[$chat_id]["Add Media"] == 1) {
                $fff = 0;
                foreach((array)$phtz as $key => $val) {
                        if($key == "P_".$pht_uid) {
                            $fff = 1;
                        }
                }
                if($fff == 0) {
                    $phtz = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                    $phtz["P_".$pht_uid]["file_id"] = $pht_id;
                    $phtz["P_".$pht_uid]["caption"] = $caption;
                    unlink(dirname(__FILE__).'/files/photos.json');
                    $phtz_file = fopen(dirname(__FILE__).'/files/photos.json', 'a+');
                    fwrite($phtz_file, json_encode($phtz, JSON_PRETTY_PRINT));
                    fclose($phtz_file);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File added to the database"."\n"."Accessing file with t.me/VasayaBot?start="."P_".$pht_uid." link"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File existed in the database"."\n"."Accessing file with t.me/VasayaBot?start=".$key." link"];
                    $telegram->sendMessage($content);
                }
            }
            elseif($f[$chat_id]["Delete Media"] == 1) {
                if(isset($phtz["P_".$pht_uid])) {
                    unset($phtz["P_".$pht_uid]);
                    unlink(dirname(__FILE__).'/files/photos.json');
			        $json = fopen(dirname(__FILE__).'/files/photos.json', 'w');
			        fwrite($json, json_encode($phtz, JSON_PRETTY_PRINT));
			        fclose($json);
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File deleted successfully"];
                    $telegram->sendMessage($content);
                }
                else {
                    $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                    $telegram->sendMessage($content);
                }
            }
        }
        elseif($vid_uid != NULL and $vid_id != NULL or $gif_uid != NULL and $gif_id != NULL or $pht_uid != NULL and $pht_id != NULL and $f[$chat_id]["Rename Caption"] == 1) {
            if($vid_uid != NULL) {
                if(file_exists(dirname(__FILE__).'/files/videos.json')) {
                    $vid_code = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
                    if(isset($vid_code["V_".$vid_uid])) {
                        if($vid_code["V_".$vid_uid]["caption"] != NULL) {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nYour current file caption : `".$vid_code["V_".$vid_uid]["caption"]."`"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "V_".$vid_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                        else {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nCurrently your file has no caption"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "V_".$vid_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                    }
                    else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
                    }
                }
            }
            if($gif_uid != NULL) {
                if(file_exists(dirname(__FILE__).'/files/gifs.json')) {
                    $gif_code = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
                    if(isset($gif_code["G_".$gif_uid])) {
                        if($gif_code["G_".$gif_uid]["caption"] != NULL) {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nYour current file caption : `".$gif_code["G_".$gif_uid]["caption"]."`"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "G_".$gif_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                        else {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nCurrently your file has no caption"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "G_".$gif_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                    }
                    else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
                    }
                }
            }
            if($pht_uid != NULL) {
                if(file_exists(dirname(__FILE__)."/files/photos.json")) {
                    $pht_code = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                    if(isset($pht_code["P_".$pht_uid])) {
                        if($pht_code["P_".$pht_uid]["caption"] != NULL) {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nYour current file caption : `".$pht_code["P_".$pht_uid]["caption"]."`"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "P_".$pht_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                        else {
			                $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Ok, now send your text to change the caption\nCurrently your file has no caption"];
                            $telegram->sendMessage($content);
                            $f[$chat_id]["Rename Caption"] = "P_".$pht_uid;
                            $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                            fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                            fclose($file);
                        }
                    }
                    else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
                    }
                }
            }
        }
        elseif(gettype($f[$chat_id]["Rename Caption"]) == "string" and !empty($text)) {
            $rcpt = explode("_", $f[$chat_id]["Rename Caption"]);
            if($rcpt[0] == "V") {
                if(file_exists(dirname(__FILE__).'/files/videos.json')) {
                    $vid_code = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
			        if(isset($vid_code[$f[$chat_id]["Rename Caption"]])) {
			            $vid_code[$f[$chat_id]["Rename Caption"]]["caption"] = $text;
                        $file = fopen(dirname(__FILE__).'/files/videos.json', "w");
                        fwrite($file, json_encode($vid_code, JSON_PRETTY_PRINT));
                        fclose($file);
                        $f[$chat_id]["Rename Caption"] = 1;
                        $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                        fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                        fclose($file);
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "The caption renamed successfully"];
                        $telegram->sendMessage($content);
			        }
                }
            }
            if($rcpt[0] == "G") {
                if(file_exists(dirname(__FILE__).'/files/gifs.json')) {
                    $gif_code = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
			        if(isset($gif_code[$f[$chat_id]["Rename Caption"]])) {
			            $gif_code[$f[$chat_id]["Rename Caption"]]["caption"] = $text;
                        $file = fopen(dirname(__FILE__).'/files/gifs.json', "w");
                        fwrite($file, json_encode($gif_code, JSON_PRETTY_PRINT));
                        fclose($file);
                        $f[$chat_id]["Rename Caption"] = 1;
                        $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                        fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                        fclose($file);
                        $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "The caption renamed successfully"];
                        $telegram->sendMessage($content);
			        }
                }
            }
            if($rcpt[0] == "P") {
                if(file_exists(dirname(__FILE__).'/files/photos.json')) {
                    $pht_code = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                    if(isset($pht_code[$f[$chat_id]["Rename Caption"]])) {
			            $pht_code[$f[$chat_id]["Rename Caption"]]["caption"] = $text;
                        $file = fopen(dirname(__FILE__).'/files/photos.json', "w");
                        fwrite($file, json_encode($pht_code, JSON_PRETTY_PRINT));
                        fclose($file);
                        $f[$chat_id]["Rename Caption"] = 1;
                        $file = fopen(dirname(__FILE__).'/files/admin.json', "w");
                        fwrite($file, json_encode($f, JSON_PRETTY_PRINT));
                        fclose($file);
                        $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "The caption renamed successfully"];
                        $telegram->sendMessage($content);
			        }
                }
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
        elseif($f[$chat_id]["Set Default Caption"] == 1 and !empty($text)) {
            $file = fopen(dirname(__FILE__).'/files/default_caption.txt', "w");
            fwrite($file, $text);
            fclose($file);
            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "Your static caption text `".$text."` saved"];
            $telegram->sendMessage($content);
        }
        $final = explode(" ", $text);
        if($final[0] == "/start" and count($final) == 2) {
            if(file_exists(dirname(__FILE__).'/files/videos.json') and file_exists(dirname(__FILE__).'/files/gifs.json') and file_exists(dirname(__FILE__)."/files/photos.json") and file_exists(dirname(__FILE__)."/files/default_caption.txt")) {
                $lnk = explode("_", $final[1]);
                $capt = file_get_contents(dirname(__FILE__).'/files/default_caption.txt');
                if($lnk[0] == "V") {
                    $vid_code = json_decode(file_get_contents(dirname(__FILE__).'/files/videos.json'), true);
			        if(isset($vid_code[$final[1]])) {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "caption" => $vid_code[$final[1]]["caption"]."\n".$capt, "video" => $vid_code[$final[1]]["file_id"]];
                        $telegram->sendVideo($content);
			        }
			        else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
		            }
                }
                elseif($lnk[0] == "G") {
		            $gif_code = json_decode(file_get_contents(dirname(__FILE__).'/files/gifs.json'), true);
			        if(isset($gif_code[$final[1]])) {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "caption" => $vid_code[$final[1]]["caption"]."\n".$capt, "animation" => $gif_code[$final[1]]["file_id"]];
                        $telegram->sendAnimation($content);
			        }
			        else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
		            }
                }
                elseif($lnk[0] == "P") {
		            $pht_code = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
			        if(isset($pht_code[$final[1]])) {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "caption" => $pht_code[$final[1]]["caption"]."\n".$capt, "photo" => $pht_code[$final[1]]["file_id"]];
                        $telegram->sendPhoto($content);
			        }
			        else {
			            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "File not found"];
                        $telegram->sendMessage($content);
		            }
                }
                else {
			        $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "Invalid link"];
                    $telegram->sendMessage($content);
                }
            }
        }
    }
    else {
        $final = explode(" ", $text);
        $users = json_decode(file_get_contents(dirname(__FILE__).'/files/users.json'), true);
        if($users[$chat_id]["ban"] == 0) {
            if($final[0] == "/start" and count($final) == 2) {
                if(file_exists(dirname(__FILE__).'/files/videos.json') and file_exists(dirname(__FILE__)."/files/gifs.json") and file_exists(dirname(__FILE__)."/files/photos.json") and file_exists(dirname(__FILE__)."/files/default_caption.txt")) {
                    $lnk = explode("_", $final[1]);
                    $capt = file_get_contents(dirname(__FILE__).'/files/default_caption.txt');
                    if($lnk[0] == "V") {
                        $vid_code = json_decode(file_get_contents(dirname(__FILE__)."/files/videos.json"), true);
                        if(isset($vid_code[$final[1]])) {
                            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "caption" => $vid_code[$final[1]]["caption"]."\n".$capt, "video" => $vid_code[$final[1]]];
                            $telegram->sendVideo($content);
                        }
                        else {
                            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "فایل یافت نشد!"];
                            $telegram->sendMessage($content);
                        }
                    }
                    elseif($lnk[0] == "G") {
                        $gif_code = json_decode(file_get_contents(dirname(__FILE__)."/files/gifs.json"), true);
                        if(isset($gif_code[$final[1]])) {
                            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "caption" => $gif_code[$final[1]]["caption"]."\n".$capt, "animation" => $gif_code[$final[1]]];
                            $telegram->sendAnimation($content);
                        }
                        else {
                            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "فایل یافت نشد!"];
                            $telegram->sendMessage($content);
                        }
                    }
                    elseif($lnk[0] == "P") {
                        $pht_code = json_decode(file_get_contents(dirname(__FILE__).'/files/photos.json'), true);
                        if(isset($pht_code[$final[1]])) {
                            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "caption" => $pht_code[$final[1]]["caption"]."\n".$capt, "photo" => $pht_code[$final[1]]["file_id"]];
                            $telegram->sendPhoto($content);
                        }
                        else {
                            $content = ["chat_id" => $chat_id, 'reply_to_message_id' => $message_id, "text" => "فایل یافت نشد!"];
                            $telegram->sendMessage($content);
                        }
                    }
                    else {
                        $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "لینک اشتباه است"];
                        $telegram->sendMessage($content);
                    }
                }
            }
        }
        else {
            $content = ["chat_id" => $chat_id, "reply_to_message_id" => $message_id, "text" => "شما از ربات بن شده‌اید!"];
            $telegram->sendMessage($content);
        }
    }

?>
