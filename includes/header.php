<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Student Hub';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="/student_course_hub/assets/css/public.css">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="site-logo" href="/student_course_hub/index.php">Student Hub</a>

            <nav class="site-nav">
                <a href="/student_course_hub/index.php">Home</a>
                <a href="/student_course_hub/programmes.php">Programmes</a>
            </nav>
        </div>
    </header>