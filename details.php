<?php
    // Load configuration file with DB credentials etc.
    if (!is_readable(__DIR__ . '/config.php')) {
        die('Konfigurationdatei nicht vorhanden!');
    }
    require_once __DIR__ . '/config.php';
    header('Cache-Control: no cache');
    session_cache_limiter('private_no_expire');
    session_start();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <title>Details</title>
        <?php
            require __DIR__ . '/vendor/autoload.php';
            /**
             * @var StorageInterface
             */
            $configDB = new stdClass();
            $configDB->server = $DB_HOST;
            $configDB->username = $DB_USER;
            $configDB->password = $DB_PASS;
            $configDB->database = $DB_NAME;
            $storage = new Database;
            $form = new Form($storage);
            $values = $storage->initialize($configDB);
            $entry = $storage->getSingleEntry($_GET['id']);
            $view = new View($storage);
            echo '<h1>' . $entry->getTitle() . '</h1>';
            echo '<p>Beschreibung: ' . $entry->getDesc() . '</p>';
            echo '<p>Ort: ' . $entry->getLocation() . '</p>';
            if ($entry->getFullDay()) {
                echo '<p>am: ' . $entry->getStart() . '</p>';
                echo 'ganztägiger Termin';
            } else {
                echo '<p>von: ' . $entry->getStart() . ', ' . date('H:i', strtotime($entry->getTime())) . '</p>';
                echo '<p>bis: ' . $entry->getEnd() . ', ' . date('H:i', strtotime($entry->getEndTime())) . '</p>';
            }
            $form->editForm();
            $view->showDownload();
            echo '<button onclick="history.go(-1);">Back</button>';
        ?>
    </head>
    <body>
    
    </body>
</html>