<?php
    // Load configuration file with DB credentials etc.
    if (!is_readable(__DIR__ . '/config.php')) {
        die('Konfigurationdatei nicht vorhanden!');
    }
    require_once __DIR__ . '/config.php';
    header('Cache-Control: no cache');
    session_cache_limiter('private_no_expire');
    session_start();
    ob_start();
?>
<!doctype html>
<html lang="de">
    <head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Calendar</title>
    <link rel="stylesheet" href="./style.css">
    <script src="./jquery-3.5.1.slim.min.js"></script>
    <script src="./script.js"></script>
    <?php
        require __DIR__ . '/vendor/autoload.php';
        /**
         * @var StorageInterface 
         * @var ViewingMethodInterface $method
         */
        $configDB = new stdClass();
        $configDB->server = $DB_HOST;
        $configDB->username = $DB_USER;
        $configDB->password = $DB_PASS;
        $configDB->database = $DB_NAME;
        $storage = new Database();
        $form = new Form($storage);
        $view = new View($storage);
        if (isset($_GET['viewingmethod'])) {
            if ($_GET['viewingmethod'] == 'Monat') {
                $config = new stdClass();
                $method = new Calendar();
                $config->storage = $storage;
                $method->inizialize($config);
            } elseif ($_GET['viewingmethod'] == 'Liste') {
                $config = new stdClass();
                $config->storage = $storage;
                $method = new ListLayout($storage);
                $method->inizialize($config);
            } elseif ($_GET['viewingmethod'] == 'Jahr') {
                $config = new stdClass();
                $method = new Year();
                $method->inizialize($config);
            } elseif ($_GET['viewingmethod'] == 'Woche') {
                $config = new stdClass();
                $method = new Week();
                $method->inizialize($config);
            }
        }
        if (!isset($method)) {
            header('location:?viewingmethod=Monat');
        }
        $values = $storage->initialize($configDB);
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $form->onSubmit();
        }
        if (isset($_FILES['fileToUpload'])) {
            $filehandler = new FileHandler($storage);
            $filehandler->uploadFile();
        }
    ?>
    </head>
    <body>
        <?php
            $entries = $storage->getEntries(0);
            echo '<div id="nav" class="navbar col-12 col-s-12">';
            echo '<form method="get">';
            if ($_GET['viewingmethod'] == 'Jahr') {
                $show_year = '<input class="active buttons_disabled nav_box" type="submit" name="viewingmethod" value="Jahr" disabled>';
            } else {
                $show_year = '<input class="buttons nav_box" type="submit" name="viewingmethod" value="Jahr">';
            }
            if ($_GET['viewingmethod'] == 'Monat') {
                $show_month = '<input class="active buttons_disabled nav_box" type="submit" name="viewingmethod" value="Monat" disabled>';
            } else {
                $show_month = '<input class="buttons nav_box" type="submit" name="viewingmethod" value="Monat">';
            }
            if ($_GET['viewingmethod'] == 'Woche') {
                $show_week = '<input class="active buttons_disabled nav_box" type="submit" name="viewingmethod" value="Woche" disabled>';
            } else {
                $show_week = '<input class="buttons nav_box" type="submit" name="viewingmethod" value="Woche">';
            }
            if ($_GET['viewingmethod'] == 'Liste') {
                $show_list = '<input class="active buttons_disabled nav_box" type="submit" name="viewingmethod" value="Liste" disabled>';
            } else {
                $show_list = '<input class="buttons nav_box" type="submit" name="viewingmethod" value="Liste">';
            }
            echo '<div class="wrapper">';
            echo $show_year;
            echo $show_month;
            echo $show_week;
            echo $show_list;
            echo '</div>';
            echo '</form></div>';
            echo '<button onclick="showNav()" id="nav_button"><img src="./src/images/2x/baseline_view_headline_black_18dp.png" alt="menu"></button>';

            echo '<form method="post"><div class="wrapper_random"><input id="random_button" type="submit"
            name="random" value="zufälliger Eintrag"></div></form>';
            echo '<br />';
            if (isset($_SESSION['new_record'])) {
                echo $_SESSION['new_record'];
                ob_end_flush();
                unset($_SESSION['new_record']);
            }
            $random = new Random($storage);
            if (isset($_POST['random'])) {
                $random->generateRandomEvent();
            }
            echo '<span class="title"></span>';
            echo $method->printData($entries);
            if (isset($_POST['expand'])) {
                $view->showDesc();
            }
            if (isset($_POST['delete'])) {
                $storage->deleteEntry($_POST['delete_id']);
                unlink(__DIR__ . '/ical/entry' . $_POST['delete_id'] . '.ics');
            }
            echo '<div id="form_dropdown_div" class="col-6 col-s-6 form_dropdown" onclick="showForm()">Neuer Termin
            <img src="./src/images/2x/baseline_arrow_drop_down_black_18dp.png" alt="dropdown"></div>';
            $form->printForm();
            echo '<div style="height:100px;display:block;"></div>';
        ?>
    </body>
</html>