<?php
    class View
    {
        private $storage;
        
        public function __construct($storage)
        {
            $this->storage = $storage;
        }
        public function showDesc()
        {
            $_SESSION['storage'] = $this->storage;
            header('location:details.php?id=' . $_POST['id']);
            ob_end_flush();
        }
        public function showDownload()
        {
            $download_button = '<br /><a href="/calendar/ical/entry' . $_GET['id'] . '.ics" download>Herunterladen</a><br />';
            echo $download_button;
        }
    }