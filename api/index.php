<?php

// VERCEL CONFIGURATION : Setup /tmp environment

// 1. Setup SQLite Database in /tmp (Read-Only fix)
$dbSource = __DIR__ . '/../database/database.sqlite';
$dbDest = '/tmp/database.sqlite';

if (!file_exists($dbDest)) {
    // Jika ada file database di source, copy dulu
    if (file_exists($dbSource)) {
        copy($dbSource, $dbDest);
    } else {
        // Jika tidak ada, buat file kosong
        touch($dbDest);
    }
}

// 2. Delegate to standard Laravel public/index.php
require __DIR__ . '/../public/index.php';
