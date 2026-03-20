<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$level = $_GET['level'] ?? '';
$programmes = [];

try {
    if ($level !== '') {
        $stmt = $pdo->prepare("SELECT * FROM Programmes WHERE isPublished = 1 AND level = ? ORDER BY programmeName ASC");
        $stmt->execute([$level]);
    } else {
        $stmt = $pdo->query("SELECT * FROM Programmes WHERE isPublished = 1 ORDER BY programmeName ASC");
    }

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
                <label for="level"><strong>Filter by level</strong></label><br><br>
                <select name="level" id="level">
                    <option value="">All Levels</option>
                    <option value="Undergraduate" <?= $level === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
                    <option value="Postgraduate" <?= $level === 'Postgraduate' ? 'selected' : '' ?>>Postgraduate</option>
                </select>
                <button type="submit" class="btn">Apply Filter</button>
            </form>
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