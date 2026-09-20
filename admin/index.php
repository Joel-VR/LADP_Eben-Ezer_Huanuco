<?php
// Route controller: Admin dashboard. View: views/admin/dashboard.php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

$events = db_all('events');
$messages = db_all('contact_messages');
$ministries = db_all('ministry_content');
$decisions = db_all('decisiones');

$eventCount = count($events);
$messageCount = count(array_filter($messages, function($m) { return !isset($m['read']) || $m['read'] == 0; }));
$ministryCount = count($ministries);
$decisionCount = count($decisions);

$unreadMessages = array_filter($messages, function($m) { return !isset($m['read']) || $m['read'] == 0; });
usort($unreadMessages, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
$unreadMessages = array_slice($unreadMessages, 0, 5);

$recentEvents = $events;
usort($recentEvents, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
$recentEvents = array_slice($recentEvents, 0, 5);

require __DIR__ . '/../views/admin/dashboard.php';
