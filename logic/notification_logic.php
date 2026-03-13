<?php

require_once __DIR__ . '/../config/db.php';

//    ADD NOTIFICATION

function addNotification($user_id, $message)
{
    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO notifications (user_id, message)
        VALUES (?, ?)
    ");

    $stmt->execute([
        $user_id,
        $message
    ]);
}




// NOTIFICATIONS


function getNotifications($user_id)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM notifications
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");

    $stmt->execute([$user_id]);

    return $stmt->fetchAll();
}



//    COUNT UNREAD

function countUnread($user_id)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM notifications
        WHERE user_id = ?
        AND is_read = 0
    ");

    $stmt->execute([$user_id]);

    return $stmt->fetchColumn();
}



function markAsRead($user_id)
{
    global $pdo;

    $stmt = $pdo->prepare("
        UPDATE notifications
        SET is_read = 1
        WHERE user_id = ?
    ");

    $stmt->execute([$user_id]);
}