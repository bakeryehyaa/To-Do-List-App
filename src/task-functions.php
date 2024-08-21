<?php 
function addTask($task_desc, $user_id) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (task_description, user_id) VALUES (:task_desc, :user_id)");
        $stmt->bindParam(':task_desc', $task_desc);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo()); 
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function getTasks($user_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $tasks;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return []; 
    }
}

function editTask($task_id, $task_description, $user_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET task_description = :task_description WHERE id = :task_id AND user_id = :user_id");
        $stmt->bindParam(':task_description', $task_description);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0; 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function toggleTaskCompletion($task_id, $user_id) {
    global $pdo;
    try {
        
        $stmt = $pdo->prepare("SELECT completed FROM tasks WHERE id = :task_id AND user_id = :user_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            
            $new_status = $task['completed'] ? 0 : 1;
            $updateStmt = $pdo->prepare("UPDATE tasks SET completed = :completed WHERE id = :task_id AND user_id = :user_id");
            $updateStmt->bindParam(':completed', $new_status, PDO::PARAM_INT);
            $updateStmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $updateStmt->execute();

            return $updateStmt->rowCount() > 0; 
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteTask($task_id, $user_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0; 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


?>