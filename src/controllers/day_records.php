<?php
session_start();
requireValidSession();

loadModel('WorkingHours');

// Get the current timestamp
$timestamp = time();

// Format the current date
$today = date('d/m/Y', $timestamp);

// Get the logged-in user
$user = $_SESSION['user'];

// Load working hours records for the logged-in user and today's date
$records = WorkingHours::loadFromUserAndDate($user->id, date('Y-m-d'));

// Load the view template with the required variables
loadTemplateView('day_records', [
    'today' => $today,
    'records' => $records // Make sure $records is correctly loaded
]);
