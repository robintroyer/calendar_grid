<?php
    class Form
    {
        private $storage;
        public function __construct($storage)
        {
            $this->storage = $storage;
        }
        public function editForm()
        {
            $entry = $this->storage->getSingleEntry($_GET['id']);
            $input_title = '<input type="text" name="title" value="' . $entry->getTitle() . '">';
            $input_desc = '<textarea type="text" name="desc">' . $entry->getDesc() . '</textarea>';
            $input_location = '<input type="text" name="location" value="' . $entry->getLocation() . '">';
            $input_start = '<input type="date" name="start" value="' . $entry->getStart() . '">';
            $input_end = '<input type="date" name="end" value="' . $entry->getEnd() . '">';
            $input_fullday = '<input type="checkbox" name="fullday" id="fullday" value="' . $entry->getFullDay() . '">';
            $input_time = '<input type="time" name="time" value="' . $entry->getTime() . '">';
            $input_endtime = '<input type="time" name="endTime" value="' . $entry->getEndTime() . '">';
            $input_color = '<input type="color" name="color" value="' . $entry->getColor() . '">';
            $input_submit = '<input type="submit" name="submit_edit" id="formsubmit">';
            echo '<h1>Eintrag bearbeiten:</h1>';
            echo "<form method='post'>";
            echo "Bezeichnung:<br/>$input_title<br/>";
            echo "Beschreibung:<br/>$input_desc<br/>";
            echo "Ort:<br/>$input_location<br/>";
            echo "Start:<br/>$input_start<br/>";
            echo "Ende:<br/>$input_end<br/>";
            echo "Ganztagstermin:<br/>$input_fullday<br/>";
            echo "Zeit:<br/>$input_time<br/>";
            echo "Endzeit:<br/>$input_endtime<br/>";
            echo "Farbe:<br/>$input_color<br/>";
            echo $input_submit;
            echo "</form>";
            if (isset($_POST['submit_edit'])) {
                $this->onEditSubmit();
            }
        }
        public function printForm()
        {
            $input_title = '<input type="text" name="title">';
            $input_desc = '<textarea type="text" name="desc"></textarea>';
            $input_location = '<input type="text" name="location">';
            $input_start = '<input type="date" name="start">';
            $input_end = '<input type="date" name="end">';
            $input_fullday = '<input type="checkbox" name="fullday" id="fullday">';
            $input_time = '<input type="time" name="time">';
            $input_endtime = '<input type="time" name="endTime">';
            $input_color = '<input type="color" name="color">';
            $input_submit = '<input type="submit" name="submit" id="formsubmit">';
            echo '<div class="wrapper_new_entry">';
            echo '<div id="new_entry_form" class="disabled col-5 col-s-5">';
            echo "<form method='post'>";
            echo "Bezeichnung:<br/>$input_title<br/>";
            echo "Beschreibung:<br/>$input_desc<br/>";
            echo "Ort:<br/>$input_location<br/>";
            echo "Start:<br/>$input_start<br/>";
            echo "Ende:<br/>$input_end<br/>";
            echo "Ganztagstermin:<br/>$input_fullday<br/>";
            echo "Zeit:<br/>$input_time<br/>";
            echo "Endzeit:<br/>$input_endtime<br/>";
            echo "Farbe:<br/>$input_color<br/>";
            echo $input_submit;
            echo "</form>";
            echo '</div>';
            $file_upload = '<div id="ics_form" class="disabled">
            <div class="border"></div>
            <form method="post" enctype="multipart/form-data">
            Hier kann eine .ics Datei hochgeladen werden.
            <input type="hidden" name="max_file_size" value="30000">
            <input type="file" name="fileToUpload">
            <input type="submit" name="submitUpload">
            </form></div></div>';
            echo $file_upload;
        }
        private function onEditSubmit()
        {
            if (
                !empty($_POST['title'])
                && !empty($_POST['location'])
                && !empty($_POST['start'])
            ) {
                $entry = new Entry();
                $entry->setID($_GET['id']);
                $entry->setTitle($_POST['title']);
                if (isset($_POST['location'])) {
                    $entry->setLocation($_POST['location']);
                }
                $entry->setLocation($_POST['location']);
                $entry->setStart($_POST['start']);
                $entry->setColor($_POST['color']);
                if (isset($_POST['desc'])) {
                    $entry->setDesc($_POST['desc']);
                }
                if (isset($_POST['end'])) {
                    $entry->setEnd($_POST['end']);
                } else {
                    $entry->setEnd(null);
                }
                if (isset($_POST['fullday']) == true) {
                    $entry->setFullDay(1);
                } else {
                    $entry->setTime($_POST['time']);
                    $entry->setEndTime($_POST['endTime']);
                }
            }
            $this->storage->editEntry($entry);
            $ical = new ICalendar();
            $ical->editFile($entry);
        }
        public function onSubmit(){
            echo 'a';
            if (
                !empty($_POST['title'])
                && !empty($_POST['location'])
                && !empty($_POST['start'])
            ) {
                $entry = new Entry();
                $entry->setID($this->storage->getMaxID() + 1);
                $entry->setTitle($_POST['title']);
                if (isset($_POST['location'])) {
                    $entry->setLocation($_POST['location']);
                }
                $entry->setLocation($_POST['location']);
                $entry->setStart($_POST['start']);
                $entry->setColor($_POST['color']);
                if (isset($_POST['desc'])) {
                    $entry->setDesc($_POST['desc']);
                }
                if (isset($_POST['end'])) {
                    $entry->setEnd($_POST['end']);
                } else {
                    $entry->setEnd(null);
                }
                if (isset($_POST['fullday']) == true) {
                    $entry->setFullDay(1);
                } else {
                    $entry->setTime($_POST['time']);
                    $entry->setEndTime($_POST['endTime']);
                }
                $this->entryToDB($entry);
            }
        }
        private function entryToDB($entry)
        {
            $this->storage->saveEntry($entry);
        }
    }