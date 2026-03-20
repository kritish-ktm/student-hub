<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

// Fetch statistics
$stats = [
    'programmes' => 0,
    'modules' => 0,
    'students' => 0,
];

try {
    $stats['programmes'] = (int)$pdo->query("SELECT COUNT(*) FROM Programmes WHERE isPublished = 1")->fetchColumn();
    $stats['modules'] = (int)$pdo->query("SELECT COUNT(*) FROM Modules")->fetchColumn();
    $stats['students'] = (int)$pdo->query("SELECT COUNT(*) FROM InterestedStudents")->fetchColumn();
} catch (Throwable $e) {
    // fail silently (for demo)
}
$pageTitle = 'Student Hub | Home';
require __DIR__ . '/includes/header.php';
?>

<main class="page-shell">
    <section class="hero">
        <div class="hero__content">
            <span class="eyebrow">Explore your future</span>
            <h1>Find the right university programme for you</h1>
            <p>
                Browse undergraduate and postgraduate programmes, compare options,
                and view full course details in one place.
            </p>

            <div class="hero__actions">
                <a class="btn btn--primary" href="/student_course_hub/programmes.php">Browse Programmes</a>
                <a class="btn btn--ghost" href="/student_course_hub/programmes.php?level=Undergraduate">Undergraduate</a>
            </div>
        </div>
    </section>
    <section class="stats">
    <div class="stats__grid">
        <div class="stat">
            <div class="stat__value"><?= e($stats['programmes']) ?></div>
            <div class="stat__label">Programmes</div>
        </div>

        <div class="stat">
            <div class="stat__value"><?= e($stats['modules']) ?></div>
            <div class="stat__label">Modules</div>
        </div>

        <div class="stat">
            <div class="stat__value"><?= e($stats['students']) ?></div>
            <div class="stat__label">Interested Students</div>
        </div>
    </div>
</section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>