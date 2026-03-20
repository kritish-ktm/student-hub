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
    // fail silently
}

// Fetch featured programmes
$featured = [];

try {
    $stmt = $pdo->query("SELECT * FROM Programmes WHERE isPublished = 1 LIMIT 6");
    $featured = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // fail silently
}

$pageTitle = 'Student Hub | Home';
require __DIR__ . '/includes/header.php';
?>

<main class="page-shell">
    <section class="section">
    <div class="section__header">
        <h2>Browse by Study Level</h2>
        <p>Choose the type of programme you want to explore.</p>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Undergraduate Programmes</h3>
            <p>Explore foundation and bachelor-level courses designed for new university students.</p>
            <a class="btn" href="/student_course_hub/programmes.php?level=Undergraduate">View Undergraduate</a>
        </div>

        <div class="card">
            <h3>Postgraduate Programmes</h3>
            <p>Discover advanced master’s and specialised programmes for further academic study.</p>
            <a class="btn" href="/student_course_hub/programmes.php?level=Postgraduate">View Postgraduate</a>
        </div>
    </div>
</section>
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

    <section class="section">
    <div class="section__header">
        <h2>Featured Programmes</h2>
        <p>Explore some of our most popular undergraduate and postgraduate courses.</p>
    </div>

    <?php if (!empty($featured)): ?>
        <div class="grid">
            <?php foreach ($featured as $programme): ?>
                <div class="card">
                    <h3><?= e($programme['programmeName']) ?></h3>
                    <p><?= e($programme['description']) ?></p>
                    <p><strong>Level:</strong> <?= e($programme['level']) ?></p>

                    <a class="btn" href="/student_course_hub/programme.php?id=<?= $programme['programmeID'] ?>">
                        View Details
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card">
            <p>No featured programmes are available right now.</p>
        </div>
    <?php endif; ?>
</section>

    
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>