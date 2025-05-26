<?php
session_start();

// Adatok beolvasása txt-ből
$data = file('data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$models = [];

foreach ($data as $line) {
    list($model, $engine, $year) = explode(';', $line);
    $models[] = ['model' => $model, 'engine' => $engine, 'year' => $year];
}

// Szűrés
$search_model = $_GET['model'] ?? '';
$search_engine = $_GET['engine'] ?? '';
$search_year = $_GET['year'] ?? '';

$filtered = array_filter($models, function($item) use ($search_model, $search_engine, $search_year) {
    return (stripos($item['model'], $search_model) !== false) &&
           (stripos($item['engine'], $search_engine) !== false) &&
           (stripos($item['year'], $search_year) !== false);
});

// Hozzáadás
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $_SESSION['my_list'][] = [
            'model' => $_POST['model'],
            'engine' => $_POST['engine'],
            'year' => $_POST['year']
        ];
    } elseif (isset($_POST['delete'])) {
        $index = $_POST['index'];
        unset($_SESSION['my_list'][$index]);
        $_SESSION['my_list'] = array_values($_SESSION['my_list']);
    }
}

$user_list = $_SESSION['my_list'] ?? [];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Volkswagen Modellek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="text-center mb-4 text-primary">Volkswagen Modell Kereső</h1>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="model" class="form-control" placeholder="Modell" value="<?= htmlspecialchars($search_model) ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="engine" class="form-control" placeholder="Motortípus" value="<?= htmlspecialchars($search_engine) ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="year" class="form-control" placeholder="Évjárat" value="<?= htmlspecialchars($search_year) ?>">
        </div>
        <div class="col-12 text-center">
            <button class="btn btn-primary" type="submit">🔍 Keresés</button>
        </div>
    </form>

    <h3 class="text-secondary mb-3">Találatok</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-light">
                <tr>
                    <th>Modell</th>
                    <th>Motor</th>
                    <th>Évjárat</th>
                    <th>Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filtered as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['model']) ?></td>
                        <td><?= htmlspecialchars($item['engine']) ?></td>
                        <td><?= htmlspecialchars($item['year']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="model" value="<?= $item['model'] ?>">
                                <input type="hidden" name="engine" value="<?= $item['engine'] ?>">
                                <input type="hidden" name="year" value="<?= $item['year'] ?>">
                                <button type="submit" name="add" class="btn btn-success btn-sm">Mentés</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($filtered)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Nincs találat</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3 class="text-secondary mt-5 mb-3">📋 Saját Lista</h3>
    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead class="table-light">
                <tr>
                    <th>Modell</th>
                    <th>Motor</th>
                    <th>Évjárat</th>
                    <th>Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_list as $index => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['model']) ?></td>
                        <td><?= htmlspecialchars($item['engine']) ?></td>
                        <td><?= htmlspecialchars($item['year']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="index" value="<?= $index ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">❌ Törlés</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($user_list)): ?>
                    <tr><td colspan="4" class="text-center text-muted">A lista üres</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
