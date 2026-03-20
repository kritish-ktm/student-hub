<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    header('Location: ' . $path);
    exit;
}

function getInt($value, $default = 0) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false ? (int)$value : $default;
}