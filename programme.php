<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$programme = null;
$modules = [];

$success = '';
$error = '';

$name = '';
$email = '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $programme) {
    $name = trim($_POST['studentName'] ?? '');
    $email = trim($_POST['studentEmail'] ?? '');

    if ($name === '' || $email === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM InterestedStudents WHERE studentEmail = ? AND programmeID = ?");
            $checkStmt->execute([$email, $id]);
            $alreadyExists = (int) $checkStmt->fetchColumn();

            if ($alreadyExists > 0) {
                $error = 'You have already registered interest for this programme.';
            } else {
                $insertStmt = $pdo->prepare("INSERT INTO InterestedStudents (studentName, studentEmail, programmeID) VALUES (?, ?, ?)");
                $insertStmt->execute([$name, $email, $id]);

                $success = 'Your interest has been registered successfully.';
                $name = '';
                $email = '';
            }
        } catch (Throwable $e) {
            $error = 'Unable to register interest at the moment.';
        }
    }
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

                <?php if ($success !== ''): ?>
                    <p><?= e($success) ?></p>
                <?php endif; ?>

                <?php if ($error !== ''): ?>
                    <p><?= e($error) ?></p>
                <?php endif; ?>

                <form method="POST" action="">
                    <label for="studentName"><strong>Name</strong></label><br><br>
                    <input
                        type="text"
                        name="studentName"
                        id="studentName"
                        placeholder="Enter your full name"
                        value="<?= e($name) ?>"
                        required
                    ><br><br>

                    <label for="studentEmail"><strong>Email</strong></label><br><br>
                    <input
                        type="email"
                        name="studentEmail"
                        id="studentEmail"
                        placeholder="Enter your email address"
                        value="<?= e($email) ?>"
                        required
                    ><br><br>

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