<?php
$errors = [];


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} elseif ($exception) {
    $message = [
        'type' => 'error',
        'message' => $exception->getMessage()
    ];

    if (get_class($exception) === 'ValidationException') {
        $errors = $exception->getErrors();
    }
} else{
    $message = [
        'type' => '',
        'message' =>''
    ];
}

$alertType = '';

if ($message['type'] === 'error') {
    $alertType = 'danger';
} elseif($message['type'] <> '' && $message['type'] <> 'error') {
    $alertType = 'success';
}
?>

<?php if ($message['type'] <> '') : ?>
    <div role="alert" 
    class="my-3 alert alert-<?= $alertType ?>">
        <?= $message['message'] ?>
    </div>
<?php endif ?>