<?php

session_start();

require_once "logic/notification_logic.php";

$list = getNotifications($_SESSION['user_id']);

markAsRead($_SESSION['user_id']);

foreach ($list as $n) {

    echo "<p>";
    echo $n['message'];
    echo " - ";
    echo $n['created_at'];
    echo "</p>";
}