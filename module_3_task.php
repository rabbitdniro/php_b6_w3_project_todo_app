<?php

// Defines json file name as a constant variable
define("TASK_FILE", "tasks.json");

// Function to read/load tasks array from json file
function load_tasks() : array {
    // Returns empty array if file doesn't exist
    if (!file_exists(TASK_FILE)) {
        return [];
    };
    // Reads data from json file and save contents to data variable
    $data = file_get_contents(TASK_FILE);
    // Returns data as an array with json_decode
    return $data ? json_decode($data, true) : [];
};

// Array of tasks loaded from json file
$file_tasks_list = load_tasks();
// print_r($file_tasks_list);

// Function to save tasks to file as json
function save_tasks(array $tasks_list) : void {
    file_put_contents(TASK_FILE, json_encode($tasks_list), JSON_PRETTY_PRINT);
};

// print_r($_SERVER['REQUEST_METHOD']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Checks if user sends add_task request
    if (isset($_POST["add_task"]) && !empty(trim($_POST["add_task"]))) {
        // Creating a task and adding it to the tasks array
        $file_tasks_list[] = [
            "task_name" => htmlspecialchars(trim($_POST["add_task"])),
            "done" => false
        ];
        // Saving tasks to the tasks array
        save_tasks($file_tasks_list);
        // Reloading the page
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;

    // Checks if user sends a delete request
    } elseif (isset($_POST["delete"])) {
        // Delete a task from the task array
        unset($file_tasks_list[$_POST["delete"]]);
        // Re-Indexing the task array
        $file_tasks_list = array_values($file_tasks_list);
        // Saving tasks to the array
        save_tasks($file_tasks_list);
        // Reloading the page
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;

    // Checks if user sends a done/undone request
    } elseif (isset($_POST["toggle"])) {
        print_r($_POST["toggle"]);
        // Toggling value of done for a task
        $file_tasks_list[$_POST["toggle"]]["done"] = !$file_tasks_list[$_POST["toggle"]]["done"];
        // Saving array of tasks with updated value of done
        save_tasks($file_tasks_list);
        // Reloading the page
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}
?>



<!-- HTML for User Interface using bootstrap -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>To-Do App</title>
</head>
<body>
    <div class="container">
        <div class="card text-center mt-5 mx-auto" style="width: 40rem">
            <div class="card-header">
                <h2 class="card-title">To-Do App</h2>
            </div>
            <div class="card-body">
                <!-- Main form to add task by user -->
                <form method="POST" class="row gx-3 justify-content-center">
                    <div class="col">
                        <input type="text" name="add_task" class="form-control" placeholder="add a task" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>

                <hr>
                    <h5 class="text-start">Task List</h5>
                <hr>
                
                <ul style="list-style: none; padding: 0;">

                    <!-- Checks if array of tasks is empty -->
                    <?php if (empty($file_tasks_list)): ?>
                            <li>No task to show!</li>
                        <?php else: ?>

                        <!-- Starts loop to display tasks as list item -->
                            <?php foreach($file_tasks_list as $index => $task): ?>
                                <li>
                                    <div class="row gx-3 ">

                                        <!-- Second form to toggle task done or not -->
                                        <form method="POST" class="col">
                                            <input type="hidden" name="toggle" value="<?= $index ?>">
                                            <button type="submit" style="border: none; text-align: left; background: none;">

                                                <!-- Dynamically adding class to make text line through and smaller -->
                                                <span class="text-start <?= $task['done'] ? 'text-decoration-line-through small' : '' ?>">
                                                    <?= htmlspecialchars($task["task_name"]) ?>
                                                </span>
                                            </button>
                                        </form>

                                        <!-- Third form to delete a task -->
                                        <form method="POST" class="col-auto my-1">
                                            <input type="hidden" name="delete" value="<?= $index ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Delete</button>
                                        </form>
                                    </div>
                                    
                                </li>
                            <!-- End of foreach loop -->
                            <?php endforeach; ?>
                            <!-- End of if statement -->
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
</body>
</html>