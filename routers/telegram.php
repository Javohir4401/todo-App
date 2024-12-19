<?php

use App\Bot;
use App\Todo;

$bot  = new Bot();

$update = json_decode(file_get_contents('php://input'));

$chatID = $update->message->chat->id;
$message = $update?->message;
$text = $update->message->text;

$callbackQuery = $update->callback_query;
$callbackQueryId = $callbackQuery->id;
$callbackData = $callbackQuery->data;
$callbackUserId = $callbackQuery->from->id;
$callbackChatId = $callbackQuery->message->chat->id;
$callbackMessageId = $callbackQuery->message->message_id;

if ($callbackQuery) {
    if (mb_stripos($callbackData, 'task_') !== false) {
        $taskId = explode('task_', $callbackData)[1];
        $todo = (new Todo())->getTodo($taskId);
        $bot->makeRequest('sendMessage', [
            'chat_id' => $callbackChatId,
            'text' =>"Task: " . json_encode($todo),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['callback_data' => "pending_" . $todo['id'], 'text' => 'Pending'],
                        ['callback_data' => "in_progress_" . $todo['id'], 'text' => 'In progrees'],
                        ['callback_data' => "completed_" . $todo['id'], 'text' => 'Complete'],
                    ]
                ]
            ])
        ]);
    }
    if (mb_stripos($callbackData, 'completed_') !== false) {
        $taskId = explode('completed_', $callbackData)[1];
        (new Todo())->updateStatus($taskId, 'completed');
    }
    if (mb_stripos($callbackData, 'in_progress_') !== false) {
        $taskId = explode('in_progress_', $callbackData)[1];
        (new Todo())->updateStatus($taskId, 'in_progress');
    }
    if (mb_stripos($callbackData, 'pending_') !== false) {
        $taskId = explode('pending_', $callbackData)[1];
        (new Todo())->updateStatus($taskId, 'pending');
    }
}

if ($message) {
    if ($text == '/start') {
        $bot->makeRequest('sendMessage', [
            'chat_id' => $chatID,
            'text' => 'Welcome to the Todo App'
        ]);
        exit();
    }

    if (mb_stripos($text, '/start') !== false) {
        $userId = explode("/start", $text)[1];
        $user = new \App\User();
        $user->setTelegramId($userId, $chatID);
        $bot->makeRequest('sendMessage', [
            'chat_id' => $chatID,
            'text' => "MB STRIPOS dan keldi"
        ]);
    }

    if ($text == '/tasks') {
        $bot->sendUserTasks($chatID);
        exit();
    }
}
