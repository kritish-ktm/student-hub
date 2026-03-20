<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$level = $_GET['level'] ?? '';
$q = trim($_GET['q'] ?? '');
$programmes = [];

try {
    $sql = "SELECT * FROM Programmes WHERE isPublished = 1";
    $params = [];

    if ($level !== '') {
        $sql .= " AND level = ?";
        $params[] = $level;
    }

    if ($q !== '') {
        $sql .= " AND programmeName LIKE ?";
        $params[] = "%$q%";
    }

    $sql .= " ORDER BY programmeName ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // fail silently for demo
}

$pageTitle = 'Student Hub | Programmes';
require __DIR__ . '/includes/header.php';
?>

<main class="page-shell">
    <section class="section">
        <div class="section__header">
            <h1>All Programmes</h1>
            <p>Browse all published programmes available in the student hub.</p>
        </div>

        <div class="card">
            <form method="GET" action="/student_course_hub/programmes.php">
                <label for="q"><strong>Search programmes</strong></label><br><br>
                <input
                    type="text"
                    name="q"
                    id="q"
                    value="<?= e($q) ?>"
                    placeholder="Enter programme name"
                ><br><br>

                <label for="level"><strong>Filter by level</strong></label><br><br>
                <select name="level" id="level">
                    <option value="">All Levels</option>
                    <option value="Undergraduate" <?= $level === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
                    <option value="Postgraduate" <?= $level === 'Postgraduate' ? 'selected' : '' ?>>Postgraduate</option>
                </select>
                <br><br>

                <button type="submit" class="btn">Search</button>
            </form>
        </div>

        <div class="section__header">
    <h2>Available Results</h2>
    <p>
        <?= count($programmes) ?> programme(s) found
        <?php if ($q !== ''): ?>
            for “<?= e($q) ?>”
        <?php endif; ?>
        <?php if ($level !== ''): ?>
            in <?= e($level) ?>
        <?php endif; ?>.
    </p>
</div>

        <div class="grid">
            <?php if (!empty($programmes)): ?>
                <?php foreach ($programmes as $programme): ?>
                    <div class="card">
                        <h3><?= e($programme['programmeName']) ?></h3>
                        <p><?= e($programme['description']) ?></p>
                        <p><strong>Level:</strong> <?= e($programme['level']) ?></p>
                        <a class="btn" href="/student_course_hub/programme.php?id=<?= $programme['programmeID'] ?>">
                            View Details
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <p>No published programmes found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>