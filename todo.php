<?php
// Load tasks
$tasks = file_exists('tasks.json') ? json_decode(file_get_contents('tasks.json'), true) : [];

// Handle Add Task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_task'])) {
    $newTask = trim($_POST['new_task']);
    if ($newTask !== '') {
        $tasks[] = ['id' => time(), 'task' => $newTask, 'done' => false];
        file_put_contents('tasks.json', json_encode($tasks));
    }
    header("Location: todo.php");
    exit;
}

// Handle Mark Done/Undone
if (isset($_GET['toggle'])) {
    foreach ($tasks as &$task) {
        if ($task['id'] == $_GET['toggle']) {
            $task['done'] = !$task['done'];
            break;
        }
    }
    file_put_contents('tasks.json', json_encode($tasks));
    header("Location: todo.php");
    exit;
}