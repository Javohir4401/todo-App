<?php

use App\Bot;
use App\Todo;

$bot  = new Bot();

$update = json_decode(file_get_contents('php://input'));
$chatId = $update->message->chat->id;
$massage = $update?->message;
$text = $message->text;

$callbackQuery = $update->callback_query;
$callbackQueryID = $callbackQuery->id;
$callbackData = $callbackQuery->data;
$callbackUserID = $callbackQuery->from->id;
$callbackChatId = $callbackQuery->message->chat->id;
$callbackMessageId = $callbackQuery->message->message_id;


if($callbackQuery){
    if(mb_stripos($callbackData, 'task_:') !== false) {
        $textId = explode('task_', $callbackData)[1];
        $todo = new Todo()->getTodo($textId);
        $bot->makeRequest('editMessageText', [
            'chat_id' => $callbackChatId,
            'message_id' => $callbackMessageId,
            'text' => 'Edit Boldi',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['callback_data' => 'completed_' . $todo['id'], 'text' => 'Completed'],
                        ['callback_data' => 'in_progress_' . $todo['id'], 'text' => 'In_Progress'],
                        ['callback_data' => 'pending_' . $todo['id'], 'text' => 'Pending']
                    ]
                ]
            ])
        ]);
    }
    if(mb_stripos($callbackData, 'completed_') !== false) {
        $textId = explode('completed_', $callbackData)[1];
        new Todo()->updateStatus((int)$textId, 'completed');
    }
    if(mb_stripos($callbackData, 'in_progress_') !== false) {
        $textId = explode('in_progress_', $callbackData)[1];
        new Todo()->updateStatus((int)$textId, 'in_progress');
    }
    if(mb_stripos($callbackData, 'pending_') !== false) {
        $textId = explode('pending_', $callbackData)[1];
        new Todo()->updateStatus((int)$textId, 'pending_');
    }
}


if ($message){
    if ($text == '/start') {
        $bot->makeRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => 'Welcome to the Todo App'
        ]);
        exit();
    }

    if (mb_stripos($text, '/start') !== false) {
        $userId = explode('/start', $text)[1];
        $user = new \App\User();
        $user->setTelegramId($userId, $chatId);
        $bot->makeRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => "MB STRIPOS dan keldi"
        ]);
    }

    if ($text == '/tasks') {
        $bot->sendUserTask($chatId);
        exit();
    }

}