<?php
namespace App;

use GuzzleHttp\Client;
class Bot{
    private Client $client;
    public function __construct(){
        $this->client = new Client([
            'base_uri' => "https://api.telegram.org/bot" . $_ENV['TELEGRAM_TOKEN'] . "/"
        ]);
    }
    public function makeRequest(string $method, array $params): void{
        $this->client->post($method, ['json' => $params]);
    }
    public function getUserTasks(int $chatId): array{
        $todo = new Todo();
        return $todo->getTodoByTelegramId($chatId);
    }
    public function prepareTaskList(int $chatId): string{
        $i = 0;
        $tasks = $this->getUserTasks($chatId);
        $taskList = "Your tasks:\n\n";
        foreach($tasks as $task){
            $i++;
            $taskList .= "Task #" .$i. "\n";
            $taskList .= $task['title'] . "\n";
            $taskList .= $task['dueDate'] . "\n";
            $taskList .= $task['status'] . "\n\n";
            $taskList .= "==================\n\n";

        }
        return $taskList;
    }
    public function prepareButtons(int $chatId): array{
        $i = 0;
        $tasks = $this->getUserTasks($chatId);
        $buttons = [];
        foreach($tasks as $task){
            $i++;
            $buttons[$i] = [
                'text' => "Task #" .$i,
                'callback_data' => "task_:" . $task['task_id']
            ];
        }
        return array_chunk($buttons, 2);
    }
    public function sendUserTask(int $chatId): void{
        $taskList = $this->prepareTaskList($chatId);
        $buttons = $this->prepareButtons($chatId);
        $this->makeRequest("sendMessage", [
            'chat_id' => $chatId,
            'text' => $taskList,
            'reply_markup' => json_encode([
                'inline_keyboard' => $buttons
            ])
        ]);
    }

}
