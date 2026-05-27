<?php
session_start();

require __DIR__ . '/core/database.php';
require __DIR__ . '/core/helpers.php';
require __DIR__ . '/views/partials/load_cards.php';

init_db(db());

$page = $_GET['page'] ?? 'home';
$action = $_POST['action'] ?? null;

require __DIR__ . '/actions/handle.php';

$user = current_user();
