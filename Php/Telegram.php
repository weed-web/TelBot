<?php

if (file_exists('TelegramErrorLogger.php')) {
    require_once 'TelegramErrorLogger.php';
}

class Telegram
{
    const INLINE_QUERY = 'inline_query';
    const CALLBACK_QUERY = 'callback_query';
    const EDITED_MESSAGE = 'edited_message';
    const EDITED_CHANNEL_POST = 'edited_channel_post';
    const REPLY = 'reply';
    const MESSAGE = 'message';
    const FORWARDED = 'forwarded';	
    const PHOTO = 'photo';
    const VIDEO = 'video';
    const AUDIO = 'audio';
    const VOICE = 'voice';
    const DOCUMENT = 'document';
    const ANIMATION = 'animation';
    const STICKER = 'sticker';	
    const LOCATION = 'location';
    const CONTACT = 'contact';
    const DICE = 'dice';    
    const NEW_CHAT_MEMBER = 'new_chat_member';
    const LEFT_CHAT_MEMBER = 'left_chat_member';
    const CHANNEL_POST = 'channel_post';

    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
	private $proxy;

    public function __construct($bot_token, $log_errors = true, array $proxy=array())
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
		$this->proxy = $proxy;
    }

    public function endpoint($api, array $content, $post = true)
    {
        $url = 'https://api.telegram.org/bot'.$this->bot_token.'/'.$api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }

        return json_decode($reply, true);
    }

    public function getMe()
    {
        return $this->endpoint('getMe', [], false);
    }

    public function respondSuccess()
    {
        http_response_code(200);

        return json_encode(['status' => 'success']);
    }

    public function sendMessage(array $content)
    {
        return $this->endpoint('sendMessage', $content);
    }

	public function sendDice(array $content)
    {
        return $this->endpoint('sendDice', $content);
    }

    public function sendPoll(array $content)
    {
        return $this->endpoint('sendPoll', $content);
    }	

    public function forwardMessage(array $content)
    {
        return $this->endpoint('forwardMessage', $content);
    }

    public function sendPhoto(array $content)
    {
        return $this->endpoint('sendPhoto', $content);
    }

    public function sendAudio(array $content)
    {
        return $this->endpoint('sendAudio', $content);
    }

    public function sendAnimation(array $content)
    {
        return $this->endpoint('sendAnimation', $content);
    }

    public function sendDocument(array $content)
    {
        return $this->endpoint('sendDocument', $content);
    }

    public function sendSticker(array $content)
    {
        return $this->endpoint('sendSticker', $content);
    }

    public function sendVideo(array $content)
    {
        return $this->endpoint('sendVideo', $content);
    }

    public function sendVoice(array $content)
    {
        return $this->endpoint('sendVoice', $content);
    }

    public function sendLocation(array $content)
    {
        return $this->endpoint('sendLocation', $content);
    }

    public function editMessageLiveLocation(array $content)
    {
        return $this->endpoint('editMessageLiveLocation', $content);
    }

    public function stopMessageLiveLocation(array $content)
    {
        return $this->endpoint('stopMessageLiveLocation', $content);
    }

    public function setChatStickerSet(array $content)
    {
        return $this->endpoint('setChatStickerSet', $content);
    }

    public function deleteChatStickerSet(array $content)
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }

    public function sendMediaGroup(array $content)
    {
        return $this->endpoint('sendMediaGroup', $content);
    }

    public function sendVenue(array $content)
    {
        return $this->endpoint('sendVenue', $content);
    }

    public function sendContact(array $content)
    {
        return $this->endpoint('sendContact', $content);
    }

    public function sendChatAction(array $content)
    {
        return $this->endpoint('sendChatAction', $content);
    }

    public function getUserProfilePhotos(array $content)
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }

    public function getFile($file_id)
    {
        $content = ['file_id' => $file_id];

        return $this->endpoint('getFile', $content);
    }

    public function kickChatMember(array $content)
    {
        return $this->endpoint('kickChatMember', $content);
    }

    public function leaveChat(array $content)
    {
        return $this->endpoint('leaveChat', $content);
    }

    public function unbanChatMember(array $content)
    {
        return $this->endpoint('unbanChatMember', $content);
    }

    public function getChat(array $content)
    {
        return $this->endpoint('getChat', $content);
    }

    public function getChatAdministrators(array $content)
    {
        return $this->endpoint('getChatAdministrators', $content);
    }

    public function getChatMembersCount(array $content)
    {
        return $this->endpoint('getChatMembersCount', $content);
    }

    public function getChatMember(array $content)
    {
        return $this->endpoint('getChatMember', $content);
    }
	
    public function answerInlineQuery(array $content)
    {
        return $this->endpoint('answerInlineQuery', $content);
    }

    public function setGameScore(array $content)
    {
        return $this->endpoint('setGameScore', $content);
    }

    public function answerCallbackQuery(array $content)
    {
        return $this->endpoint('answerCallbackQuery', $content);
    }

    public function editMessageText(array $content)
    {
        return $this->endpoint('editMessageText', $content);
    }

    public function editMessageCaption(array $content)
    {
        return $this->endpoint('editMessageCaption', $content);
    }

    public function editMessageReplyMarkup(array $content)
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }

    public function downloadFile($telegram_file_path, $local_file_path)
    {
        $file_url = 'https://api.telegram.org/file/bot'.$this->bot_token.'/'.$telegram_file_path;
        $in = fopen($file_url, 'rb');
        $out = fopen($local_file_path, 'wb');

        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    public function setWebhook($url, $certificate = '')
    {
        if ($certificate == '') {
            $requestBody = ['url' => $url];
        } else {
            $requestBody = ['url' => $url, 'certificate' => "@$certificate"];
        }

        return $this->endpoint('setWebhook', $requestBody, true);
    }

    public function deleteWebhook()
    {
        return $this->endpoint('deleteWebhook', [], false);
    }

    public function getData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');

            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function Text()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['data'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['text'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['text'];
        }

        return @$this->data['message']['text'];
    }

    public function Caption()
    {
        return @$this->data['message']['caption'];
    }

    public function Entities()
    {
        return @$this->data['message']['entities'];
    }

    public function ReplyMarkups()
    {
        return @$this->data['message']['reply_markup']['inline_keyboard'];
    }	

    public function ChatID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['chat']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['chat']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['chat']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }
        return $this->data['message']['chat']['id'];
    }

    public function MessageID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['message_id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['message_id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['message_id'];
        }
        return $this->data['message']['message_id'];
    }

    public function ReplyToMessageID()
    {
        return $this->data['message']['reply_to_message']['message_id'];
    }

    public function ReplyToMessageText()
    {
        return $this->data['message']['reply_to_message']['text'];
    }

    public function ReplyToMessageFromUsername()
    {
        return $this->data['message']['reply_to_message']['from']['username'];
    }

    public function ReplyToMessageFromUserFirstName()
    {
        return $this->data['message']['reply_to_message']['from']['first_name'];
    }

    public function ReplyToMessageFromUserLastName()
    {
        return $this->data['message']['reply_to_message']['from']['last_name'];
    }	

    public function ReplyToMessageFromUserID()
    {
        return $this->data['message']['reply_to_message']['from']['id'];
    }	

    public function ReplyToMessageForwardFromUserID()
    {
        return $this->data['message']['reply_to_message']['forward_from']['id'];
    }

    public function Inline_Query()
    {
        return $this->data['inline_query'];
    }

	public function Inline_Query_ID()
    {
        return $this->data['inline_query']['id'];
    }

	public function Inline_Query_Text()
    {
        return $this->data['inline_query']['query'];
    }	

    public function Callback_Query()
    {
        return $this->data['callback_query'];
    }

    public function Callback_ID()
    {
        return $this->data['callback_query']['id'];
    }

    public function Callback_Data()
    {
        return $this->data['callback_query']['data'];
    }

    public function Callback_Message()
    {
        return $this->data['callback_query']['message'];
    }

    public function Callback_ChatID()
    {
        return $this->data['callback_query']['message']['chat']['id'];
    }

    public function Date()
    {
        return $this->data['message']['date'];
    }

    public function FirstName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['first_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['first_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['first_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['first_name'];
        }		
        return @$this->data['message']['from']['first_name'];
    }

    public function LastName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['last_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['last_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['last_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['last_name'];
        }		
        return @$this->data['message']['from']['last_name'];
    }

    public function Username()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['username'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['username'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['username'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['username'];
        }
        return @$this->data['message']['from']['username'];
    }

    public function IsBot()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['is_bot'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['is_bot'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['is_bot'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['is_bot'];
        }
        if ($type == self::FORWARDED) {
            return $this->data['message']['forward_from']['is_bot'];
        }
        return @$this->data['message']['from']['is_bot'];
    }	

    public function NewChatMember()
    {
        return $this->data['message']['new_chat_members'];
    }	

    public function Location()
    {
        return $this->data['message']['location'];
    }

    public function UpdateID()
    {
        return $this->data['update_id'];
    }

    public function UpdateCount()
    {
        return count($this->updates['result']);
    }

	public function GetPhotoId()
	{
        $update = $this->data;
        if(isset($update["message"]["photo"])) {
            return $this->data["message"]["photo"][count($this->data["message"]["photo"])-1]["file_id"];
        }
        else {
            return NULL;
        }
	}

	public function GetPhotoUid()
	{
        $update = $this->data;
        if(isset($update["message"]["photo"])) {
            return $this->data["message"]["photo"][count($this->data["message"]["photo"])-1]["file_unique_id"];
        }
        else {
            return NULL;
        }
	}

    public function GetGifzId()
    {
        $update = $this->data;
        if(isset($update['message']['animation'])) {
            return $this->data["message"]["animation"]["file_id"];
        }
        else {
            return NULL;
        }
    }

    public function GetGifzUid()
    {
        $update = $this->data;
        if(isset($update['message']['animation'])) {
            return $this->data["message"]["animation"]["file_unique_id"];
        }
        else {
            return NULL;
        }
    }

    public function GetVidzId()
    {
        $update = $this->data;
        if(isset($update['message']['video'])) {
            return $this->data["message"]["video"]["file_id"];
        }
        else {
            return NULL;
        }
    }

    public function GetVidzUid()
    {
        $update = $this->data;
        if(isset($update['message']['video'])) {
            return $this->data["message"]["video"]["file_unique_id"];
        }
        else {
            return NULL;
        }
    }

    public function UserID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return $this->data['callback_query']['from']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return $this->data['channel_post']['from']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }			
        return $this->data['message']['from']['id'];
    }

	public function getContactPhoneNumber()
	{
		if ($this->getUpdateType() == 'contact')
			return $this->data["message"]["contact"]["phone_number"];
	}

	public function getContactFirstName()
	{
		if ($this->getUpdateType() == 'contact')
			return $this->data["message"]["contact"]["first_name"];
	}	

	public function Latitude()
	{
		if ($this->getUpdateType() == 'location')
			return $this->data["message"]["location"]["latitude"];
	}

	public function Longitude()
	{
		if ($this->getUpdateType() == 'location')
			return $this->data["message"]["location"]["longitude"];
	}	

	public function photoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][0]["file_id"];
	}

	public function smallPhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][0]["file_id"];
	}

	public function middlePhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][1]["file_id"];
	}

	public function bigPhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][2]["file_id"];
	}

	public function voiceFileID()
	{
		if ($this->getUpdateType() == 'voice')
			return $this->data["message"]["voice"]["file_id"];
	}

	public function videoFileID()
	{
		if ($this->getUpdateType() == 'video')
			return $this->data["message"]["video"]["file_id"];
	}

	public function audioFileID()
	{
		if ($this->getUpdateType() == 'audio')
			return $this->data["message"]["audio"]["file_id"];
	}

	public function documentFileID()
	{
		if ($this->getUpdateType() == 'document')
			return $this->data["message"]["document"]["file_id"];
	}

	public function documentMimeType()
	{
		if ($this->getUpdateType() == 'document')
			return $this->data["message"]["document"]["mime_type"];
	}

	public function getDocumentMimeTypeExtension()
	{
		if ($this->getUpdateType() == 'document'){
			$mime_type = $this->data["message"]["document"]["mime_type"];
			if($mime_type == 'application/vnd.android.package-archive')
				return 'apk';
			elseif($mime_type == 'application/zip')
				return 'zip';
			elseif($mime_type == 'application/x-rar')
				return 'rar';
			elseif($mime_type == 'application/msword')
				return 'doc';
			elseif($mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
				return 'docx';
			elseif($mime_type == 'application/vnd.ms-excel')
				return 'xls';			
			elseif($mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
				return 'xlsx';			
			elseif($mime_type == 'application/javascript')
				return 'js';			
			elseif($mime_type == 'application/x-php')
				return 'php';
			elseif($mime_type == 'text/x-sql')
				return 'sql';			
		}
	}	

    public function FromID()
    {
        return $this->data['message']['forward_from']['id'];
    }

    public function FromChatID()
    {
        return $this->data['message']['forward_from_chat']['id'];
    }

    public function messageFromGroup()
    {
        if ($this->data['message']['chat']['type'] == 'private') {
            return false;
        }
        return true;
    }
    
    public function getChatType()
    {
        return $this->data['message']['chat']['type'];
    }

    public function messageFromGroupTitle()
    {
        if ($this->data['message']['chat']['type'] != 'private') {
            return $this->data['message']['chat']['title'];
        }
    }

    public function buildKeyBoard(array $options, $onetime = false, $resize = false, $selective = true)
    {
        $replyMarkup = [
            'keyboard'          => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard'   => $resize,
            'selective'         => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }

    public function buildInlineKeyBoard(array $options)
    {
        $replyMarkup = [
            'inline_keyboard' => $options,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }

    public function buildInlineKeyboardButton($text, $url = '', $callback_data = '', $switch_inline_query = null, $switch_inline_query_current_chat = null, $callback_game = '', $pay = '')
    {
        $replyMarkup = [
            'text' => $text,
        ];
        if ($url != '') {
            $replyMarkup['url'] = $url;
        } elseif ($callback_data != '') {
            $replyMarkup['callback_data'] = $callback_data;
        } elseif (!is_null($switch_inline_query)) {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        } elseif (!is_null($switch_inline_query_current_chat)) {
            $replyMarkup['switch_inline_query_current_chat'] = $switch_inline_query_current_chat;
        } elseif ($callback_game != '') {
            $replyMarkup['callback_game'] = $callback_game;
        } elseif ($pay != '') {
            $replyMarkup['pay'] = $pay;
        }
        return $replyMarkup;
    }

    public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
    {
        $replyMarkup = [
            'text'             => $text,
            'request_contact'  => $request_contact,
            'request_location' => $request_location,
        ];
        return $replyMarkup;
    }

    public function buildKeyBoardHide($selective = true)
    {
        $replyMarkup = [
            'remove_keyboard' => true,
            'selective'       => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }

    public function buildForceReply($selective = true)
    {
        $replyMarkup = [
            'force_reply' => true,
            'selective'   => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup, true);
        return $encodedMarkup;
    }

    public function sendInvoice(array $content)
    {
        return $this->endpoint('sendInvoice', $content);
    }

    public function answerShippingQuery(array $content)
    {
        return $this->endpoint('answerShippingQuery', $content);
    }

    public function answerPreCheckoutQuery(array $content)
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }

    public function sendVideoNote(array $content)
    {
        return $this->endpoint('sendVideoNote', $content);
    }

    public function restrictChatMember(array $content)
    {
        return $this->endpoint('restrictChatMember', $content);
    }

    public function promoteChatMember(array $content)
    {
        return $this->endpoint('promoteChatMember', $content);
    }

    public function exportChatInviteLink(array $content)
    {
        return $this->endpoint('exportChatInviteLink', $content)["result"];
    }

    public function setChatPhoto(array $content)
    {
        return $this->endpoint('setChatPhoto', $content);
    }

    public function deleteChatPhoto(array $content)
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }

    public function setChatTitle(array $content)
    {
        return $this->endpoint('setChatTitle', $content);
    }

    public function setChatDescription(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }
	
    public function setChatAdministratorCustomTitle(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }	

    public function pinChatMessage(array $content)
    {
        return $this->endpoint('pinChatMessage', $content);
    }

    public function unpinChatMessage(array $content)
    {
        return $this->endpoint('unpinChatMessage', $content);
    }

    public function getStickerSet(array $content)
    {
        return $this->endpoint('getStickerSet', $content);
    }

    public function uploadStickerFile(array $content)
    {
        return $this->endpoint('uploadStickerFile', $content);
    }

    public function createNewStickerSet(array $content)
    {
        return $this->endpoint('createNewStickerSet', $content);
    }

    public function addStickerToSet(array $content)
    {
        return $this->endpoint('addStickerToSet', $content);
    }

    public function setStickerPositionInSet(array $content)
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }

    public function deleteStickerFromSet(array $content)
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }

    public function deleteMessage(array $content)
    {
        return $this->endpoint('deleteMessage', $content);
    }

    public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true)
    {
        $content = ['offset' => $offset, 'limit' => $limit, 'timeout' => $timeout];
        $this->updates = $this->endpoint('getUpdates', $content);
        if ($update) {
            if (count($this->updates['result']) >= 1) { //for CLI working.
                $last_element_id = $this->updates['result'][count($this->updates['result']) - 1]['update_id'] + 1;
                $content = ['offset' => $last_element_id, 'limit' => '1', 'timeout' => $timeout];
                $this->endpoint('getUpdates', $content);
            }
        }
        return $this->updates;
    }

    public function serveUpdate($update)
    {
        $this->data = $this->updates['result'][$update];
    }

    function getDiceValue(){
        return $this->data['message']['dice']['value'];
    }

    public function getUpdateType()
    {
        $update = $this->data;
        if (isset($update['inline_query'])) {
            return self::INLINE_QUERY;
        }
        if (isset($update['callback_query'])) {
            return self::CALLBACK_QUERY;
        }
        if (isset($update['message']['reply_to_message'])) {
			if(isset($update['message']['animation']))
				return self::ANIMATION;
			elseif(isset($update['message']['document']))
				return self::DOCUMENT;
			elseif(isset($update['message']['sticker']))
				return self::STICKER;
			elseif(isset($update['message']['photo']))
				return self::PHOTO;
			elseif(isset($update['message']['video']))
				return self::VIDEO;
			elseif(isset($update['message']['audio']))
				return self::AUDIO;
			elseif(isset($update['message']['voice']))
				return self::VOICE;
            else
				return self::REPLY;
        }		
        if (isset($update['edited_channel_post'])) {
            return self::EDITED_CHANNEL_POST;
        }
		if (isset($update['edited_message'])) {
            return self::EDITED_MESSAGE;
        }
        if (isset($update['message']['forward_from']) || isset($update['message']['forward_from_chat'])) {
            return self::FORWARDED;
        }
        if (isset($update['message']['new_chat_members'])) {
            return self::NEW_CHAT_MEMBER;
        }
        if (isset($update['message']['left_chat_member'])) {
            return self::LEFT_CHAT_MEMBER;
        }
        if (isset($update['message']['text'])) {
            return self::MESSAGE;
        }
        if (isset($update['message']['photo'])) {
            return self::PHOTO;
        }
        if (isset($update['message']['video'])) {
            return self::VIDEO;
        }
        if (isset($update['message']['audio'])) {
            return self::AUDIO;
        }
        if (isset($update['message']['voice'])) {
            return self::VOICE;
        }
        if (isset($update['message']['contact'])) {
            return self::CONTACT;
        }
        if (isset($update['message']['location'])) {
            return self::LOCATION;
        }
        if (isset($update['message']['document'])) {
            return self::DOCUMENT;
		}
        if (isset($update['message']['animation'])) {
            return self::ANIMATION;
        }
		if (isset($update['message']['sticker'])) {
            return self::STICKER;
        }
        if (isset($update['channel_post'])) {
            return self::CHANNEL_POST;
        }
        if (isset($update['message']['dice'])) {
            return self::DICE;
        }
        return false;
    }

    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
		echo "inside curl if";
		if (!empty($this->proxy)) {
			echo "inside proxy if";
			if (array_key_exists("type", $this->proxy)) {
				curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy["type"]);
			}
			
			if (array_key_exists("auth", $this->proxy)) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy["auth"]);
			}
			
			if (array_key_exists("url", $this->proxy)) {
				echo "Proxy Url";
				curl_setopt($ch, CURLOPT_PROXY, $this->proxy["url"]);
			}
			
			if (array_key_exists("port", $this->proxy)) {
				echo "Proxy port";
				curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy["port"]);
			}
		}
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
		echo $result;
        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }
        return $result;
    }
}

if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        .($postname ?: basename($filename))
        .($mimetype ? ";type=$mimetype" : '');
    }
}
