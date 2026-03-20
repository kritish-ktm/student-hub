<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

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
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>