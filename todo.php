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

// Handle Delete Task
if (isset($_GET['delete'])) {
    $tasks = array_filter($tasks, fn($t) => $t['id'] != $_GET['delete']);
    file_put_contents('tasks.json', json_encode(array_values($tasks)));
    header("Location: todo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My To-Do List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            font-family: 'Inter', sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
        }
        form input[type="text"] {
            width: 75%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        form button {
            width: 20%;
            margin-left: 5%;
           
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s ease;
        }
        form button:hover {
            background: #2980b9;
        }
        ul {
            list-style: none;
            padding-left: 0;
            margin-top: 30px;
        }
        li {
            background: #f0f2f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        li:hover {
            background-color: #e1e7ec;
        }
        .task-link {
            text-decoration: none;
            color: #2c3e50;
            font-size: 16px;
            transition: color 0.2s ease;
        }
        .task-link:hover {
            color: #1abc9c;
        }
        .done {
            text-decoration: line-through;
            color: #999;
        }
        .delete-btn {
            color: #e74c3c;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .delete-btn:hover {
            background: #ffe5e5;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>My To-Do List</h2>
    <form method="POST" autocomplete="off">
        <input type="text" name="new_task" placeholder="Enter a new task" required>
        <button type="submit">Add</button>
    </form>

    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <a href="?toggle=<?= $task['id'] ?>" class="task-link <?= $task['done'] ? 'done' : '' ?>">
                    <?= htmlspecialchars($task['task']) ?>
                </a>
                <a href="?delete=<?= $task['id'] ?>" class="delete-btn">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>