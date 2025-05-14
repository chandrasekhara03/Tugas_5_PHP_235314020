<?php
session_start();
require 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// --------------------------------------------------------------
// TAMBAH TASK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']); 
    $user_id = $_SESSION['user_id']; 
    mysqli_query($conn, "INSERT INTO todos (user_id, title, status) VALUES ('$user_id', '$task', 'pending')");
}

// --------------------------------------------------------------
// TOGGLE STATUS TASK
if (isset($_GET['toggle_task'])) {
    $task_id = $_GET['toggle_task'];
    $result = mysqli_query($conn, "SELECT status FROM todos WHERE id='$task_id' AND user_id='{$_SESSION['user_id']}'");
    $current_status = mysqli_fetch_assoc($result)['status'];

    $new_status = ($current_status === 'completed') ? 'pending' : 'completed';
    mysqli_query($conn, "UPDATE todos SET status = '$new_status' WHERE id='$task_id' AND user_id='{$_SESSION['user_id']}'");
}

// --------------------------------------------------------------
// HAPUS TASK
if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task'];
    mysqli_query($conn, "DELETE FROM todos WHERE id='$task_id' AND user_id='{$_SESSION['user_id']}'");
}

// --------------------------------------------------------------
// AMBIL SEMUA TASK
$todos = mysqli_query($conn, "SELECT * FROM todos WHERE user_id='{$_SESSION['user_id']}' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <h1>To-Do List [<?php echo $_SESSION['username']; ?>]</h1>
    <a class="logout" href="logout.php">Logout</a>

    <form method="POST">
        <input type="text" name="task" placeholder="Masukkan task..." required>
        <button type="submit" name="add_task">Tambah</button>
    </form>

    <?php while ($todo = mysqli_fetch_assoc($todos)): ?>
        <div class="task">
            <span class="<?php echo $todo['status'] === 'completed' ? 'completed' : ''; ?>">
                <?php echo htmlspecialchars($todo['title']); ?>
            </span>
            <div class="task-actions">
                <a href="todolist.php?toggle_task=<?php echo $todo['id']; ?>">
                    <?php echo $todo['status'] === 'completed' ? 'Batal' : 'Selesai'; ?>
                </a>
                <a href="todolist.php?delete_task=<?php echo $todo['id']; ?>">Hapus</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
