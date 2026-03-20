<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$programme = null;
$modules = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM Programmes WHERE programmeID = ? AND isPublished = 1");
    $stmt->execute([$id]);
    $programme = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($programme) {
        $modStmt = $pdo->prepare("SELECT * FROM Modules WHERE programmeID = ? ORDER BY yearOfStudy ASC, moduleName ASC");
        $modStmt->execute([$id]);
        $modules = $modStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    // fail silently for demo
}

$pageTitle = $programme ? 'Student Hub | ' . $programme['programmeName'] : 'Student Hub | Programme';
require __DIR__ . '/includes/header.php';
?>

<main class="page-shell">
    <section class="section">
        <?php if ($programme): ?>
            <div class="card">
                <h1><?= e($programme['programmeName']) ?></h1>
                <p><?= e($programme['description']) ?></p>
                <p><strong>Level:</strong> <?= e($programme['level']) ?></p>
                <p><strong>Duration:</strong> <?= e($programme['duration'] ?? 'N/A') ?></p>
            </div>

            <div class="card">
                <h2>Modules</h2>

                <?php if (!empty($modules)): ?>
                    <?php
                    $currentYear = null;
                    foreach ($modules as $module):
                        if ($currentYear !== $module['yearOfStudy']):
                            $currentYear = $module['yearOfStudy'];
                    ?>
                        <h3>Year <?= e($currentYear) ?></h3>
                    <?php endif; ?>

                        <p><?= e($module['moduleName']) ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No modules available for this programme.</p>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2>Register Your Interest</h2>
                <p>Complete the form below to register your interest in this programme.</p>

                <form method="POST" action="">
                    <label for="studentName"><strong>Name</strong></label><br><br>
                    <input type="text" name="studentName" id="studentName" placeholder="Enter your full name" required><br><br>

                    <label for="studentEmail"><strong>Email</strong></label><br><br>
                    <input type="email" name="studentEmail" id="studentEmail" placeholder="Enter your email address" required><br><br>

                    <button type="submit" class="btn">Register Interest</button>
                </form>
            </div>
        <?php else: ?>
            <div class="card">
                <h1>Programme not found</h1>
                <p>The programme you are looking for does not exist or is not published.</p>
                <a class="btn" href="/student_course_hub/programmes.php">Back to Programmes</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>