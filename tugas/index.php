<?php
// Inisialisasi array tugas
$tasks = [
    ["id" => 1, "title" => "Belajar PHP", "status" => "belum"],
    ["id" => 2, "title" => "Kerjakan tugas UX", "status" => "selesai"],
];

// Tangani penambahan tugas
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $newId = count($tasks) + 1;
    $tasks[] = ["id" => $newId, "title" => $title, "status" => "belum"];
}

// Tangani perubahan status tugas
if (isset($_POST['toggle'])) {
    $toggleId = $_POST['toggle'];
    foreach ($tasks as &$task) {
        if ($task['id'] == $toggleId) {
            $task['status'] = $task['status'] === 'belum' ? 'selesai' : 'belum';
        }
    }
    unset($task);
}

// Tangani penghapusan tugas
if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];
    $tasks = array_filter($tasks, function ($task) use ($deleteId) {
        return $task['id'] != $deleteId;
    });
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To-Do List - PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #d4fc79, #96e6a1);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .todo-container {
            width: 100%;
            max-width: 600px;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-weight: 600;
            color: #2d3436;
        }

        input[type="text"] {
            border-radius: 0.5rem;
        }

        input[type="text"]:focus {
            border-color: #45ce30;
            box-shadow: 0 0 0 0.2rem rgba(72, 239, 151, 0.25);
        }

        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #219150;
        }

        .list-group-item {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .list-group-item:hover {
            background: #f1f2f6;
        }

        .list-group-item form {
            margin: 0;
            display: inline;
        }

        .form-check-input {
            transform: scale(1.3);
            margin-right: 1rem;
            accent-color: #2ecc71;
        }

        .text-decoration-line-through {
            color: #7f8c8d !important;
        }

        .btn-outline-danger {
            border-radius: 1rem;
        }

        .btn-outline-danger:hover {
            background-color: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        .form-inline {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="container todo-container">
    <div class="card p-4">
        <h3 class="card-title mb-3 text-center">📝 To-Do List</h3>

        <!-- Form Tambah Tugas -->
        <form method="POST" class="form-inline mb-4">
            <input type="text" name="title" class="form-control flex-grow-1" placeholder="Tambahkan tugas..." required>
            <button type="submit" name="add" class="btn btn-success">Tambah</button>
        </form>

        <!-- Daftar Tugas -->
        <ul class="list-group">
            <?php foreach ($tasks as $task): ?>
                <li class="list-group-item">
                    <form method="POST" class="d-flex align-items-center flex-grow-1">
                        <input class="form-check-input me-2" type="checkbox" name="toggle" value="<?= $task['id']; ?>"
                               onchange="this.form.submit()" <?= $task['status'] === 'selesai' ? 'checked' : '' ?>>
                        <span class="<?= $task['status'] === 'selesai' ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($task['title']); ?>
                        </span>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="delete" value="<?= $task['id']; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

</body>
</html>
