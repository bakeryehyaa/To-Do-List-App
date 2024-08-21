<?php 
include '../config/config.php';  
include '../src/user-functions.php'; 
include '../src/task-functions.php';  

// Add Or Edit Task
if (isset($_POST["description"])) {
    $task_desc = $_POST["description"];
    $task_id = $_POST["task_id"] ?? null; 
    
    if (isset($_SESSION['id'])) {
        if ($task_id) {
            
            editTask($task_id, $task_desc, $_SESSION['id']);
        } else {
            
            addTask($task_desc, $_SESSION['id']);
        }
        
        header("Location: tasks.php");
        exit;
    }
}

// Mark Task As Completed and vice versa
if (isset($_GET['toggle'])) {
    $task_id = $_GET['toggle'];
    if (isset($_SESSION['id'])) {
        toggleTaskCompletion($task_id, $_SESSION['id']);
        
        header("Location: tasks.php");
        exit;
    }
}

// Delete A Task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    if (isset($_SESSION['id'])) {
        deleteTask($task_id, $_SESSION['id']);
        
        header("Location: tasks.php");
        exit;
    }
}
// Check User Login Status And Retrieve Session Id
if (isset($_SESSION['id'])) {
    $tasks = getTasks($_SESSION['id']);
} else {
    echo "User is not logged in.";
    $tasks = [];
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>TODO</title>
    <link rel="stylesheet" type="text/css" href="../task.css"> 
</head>
<body>
    <div class="container">
        <form method="POST" action="tasks.php">
            <h1><?php echo isset($_GET['edit']) ? 'Edit Task' : 'Add Task'; ?></h1>
            <input type="hidden" name="task_id" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>" />
            <input type="text" size="50" placeholder="Task Description" name="description" autocomplete="off" required value="<?php echo isset($_GET['edit']) ? htmlspecialchars($tasks[array_search($_GET['edit'], array_column($tasks, 'id'))]['task_description']) : ''; ?>" />    
            <input type="submit" name="submit_task" value="<?php echo isset($_GET['edit']) ? 'Update Task' : 'Add Task'; ?>" />
        </form>

        <div class="header">
            <h1>Current Active Tasks</h1>

            <p>
            <?php
            if (isset($_SESSION['id'])) {
                $completedCount = array_sum(array_column($tasks, 'completed'));
                echo "Completed Tasks: " . $completedCount;
            }
            ?>
        </p>


            <a href="../public/logout.php" class="btn logout">Logout</a>
            
        
        </div>


        <?php if (!empty($tasks)): ?>
            <ul>
                <?php foreach ($tasks as $task): ?>
                    <li>
                        <?php echo htmlspecialchars($task['task_description']); ?>
                        <div class="task-actions">
                            <?php if (!$task['completed']): ?>
                                <a href="tasks.php?toggle=<?php echo $task['id']; ?>" class="btn toggle">Mark as Completed</a>
                            <?php else: ?>
                                <a href="tasks.php?toggle=<?php echo $task['id']; ?>" class="btn toggle">Mark as Not Completed</a>
                            <?php endif; ?>
                            <a href="tasks.php?edit=<?php echo $task['id']; ?>" class="btn edit">Edit</a>
                            <a href="tasks.php?delete=<?php echo $task['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>

        
    </div>
</body>
</html>
