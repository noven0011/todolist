<?php
// LANGKAH 1: Mulai session di paling atas
session_start();

// LANGKAH 2: Inisialisasi tugas dari session atau buat baru jika belum ada
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        ["id" => 1, "title" => "Belajar PHP", "status" => "belum"],
        ["id" => 2, "title" => "Kerjakan tugas UX", "status" => "selesai"],
    ];
}

// Gunakan variabel lokal untuk mempermudah pengelolaan
$tasks = $_SESSION['tasks'];

// Tangani penambahan tugas
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    // Membuat ID unik berdasarkan timestamp agar tidak duplikat
    $newId = time(); 
    $tasks[] = ["id" => $newId, "title" => $title, "status" => "belum"];
    $_SESSION['tasks'] = $tasks; // Simpan perubahan ke session
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman untuk mencegah resubmit
    exit();
}

// Tangani perubahan status tugas (toggle)
if (isset($_POST['toggle'])) {
    $toggleId = $_POST['toggle'];
    foreach ($tasks as &$task) {
        if ($task['id'] == $toggleId) {
            $task['status'] = $task['status'] === 'belum' ? 'selesai' : 'belum';
            break; // Hentikan loop setelah ditemukan
        }
    }
    unset($task);
    $_SESSION['tasks'] = $tasks; // Simpan perubahan ke session
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Tangani penghapusan tugas
if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];
    $tasks = array_filter($tasks, function ($task) use ($deleteId) {
        return $task['id'] != $deleteId;
    });
    $_SESSION['tasks'] = $tasks; // Simpan perubahan ke session
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Tangani pengeditan tugas
$editId = null;
if (isset($_POST['edit'])) {
    $editId = $_POST['edit'];
}

// Tangani penyimpanan update edit
if (isset($_POST['update'])) {
    $updateId = $_POST['update_id'];
    $newTitle = $_POST['new_title'];
    foreach ($tasks as &$task) {
        if ($task['id'] == $updateId) {
            $task['title'] = $newTitle;
            break; // Hentikan loop setelah ditemukan
        }
    }
    unset($task);
    $_SESSION['tasks'] = $tasks; // Simpan perubahan ke session
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
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
        .todo-container { width: 100%; max-width: 600px; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
        .card-title { font-weight: 600; color: #2d3436; }
        input[type="text"] { border-radius: 0.5rem; }
        .list-group-item {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .form-inline { display: flex; gap: 10px; }
        .btn-group { display: flex; gap: 5px; }
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
            <?php if (empty($tasks)): ?>
                <li class="list-group-item text-center text-muted">Tidak ada tugas.</li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="list-group-item">
                        <?php if ($editId == $task['id']): ?>
                            <!-- Form Edit -->
                            <form method="POST" class="form-inline w-100">
                                <input type="hidden" name="update_id" value="<?= $task['id']; ?>">
                                <input type="text" name="new_title" class="form-control flex-grow-1" value="<?= htmlspecialchars($task['title']); ?>" required autofocus>
                                <button type="submit" name="update" class="btn btn-warning">Simpan</button>
                            </form>
                        <?php else: ?>
                            <!-- Tampilan Normal -->
                            <form method="POST" class="d-flex align-items-center flex-grow-1">
                                <input type="hidden" name="toggle" value="<?= $task['id']; ?>">
                                <input class="form-check-input me-3" type="checkbox" onchange="this.form.submit()" <?= $task['status'] === 'selesai' ? 'checked' : '' ?>>
                                <span class="<?= $task['status'] === 'selesai' ? 'text-decoration-line-through text-muted' : '' ?>">
                                    <?= htmlspecialchars($task['title']); ?>
                                </span>
                            </form>

                            <!-- Tombol Aksi -->
                            <div class="btn-group">
                                <form method="POST">
                                    <input type="hidden" name="edit" value="<?= $task['id']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Edit</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="delete" value="<?= $task['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

</body>
</html>
