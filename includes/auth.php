<?php
// Laad de centrale Auth klasse en controleer direct of de gebruiker is ingelogd.
// Vervangt de oude breekbare relatieve paden (zoals ../../index.php).
require_once __DIR__ . '/../models/Auth.php';

Auth::requireLogin();