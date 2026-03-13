<?php
require_once __DIR__ . '/../logic/notification_logic.php';

$count = countUnread($_SESSION['user_id']);

