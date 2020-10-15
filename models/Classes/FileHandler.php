<?php
class FileHandler
{
    private $dir;
    private $extension;
    private $file;
    private $uploadOK;
    private $content;
    private $storage;

    public function __construct($storage)
    {
        $this->dir = __DIR__ . '/../../ical/temp/';
        $this->file = $this->dir . basename($_FILES['fileToUpload']['name']);
        $this->extension = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
        if ($this->extension != 'ics') {
            $this->uploadOK = 0;
        } else {
            $this->uploadOK = 1;
        }
        $this->storage = $storage;
    }

    public function uploadFile()
    {
        if ($this->uploadOK) {
            if (copy($_FILES['fileToUpload']['tmp_name'], $this->file)) {
                echo 'Die Datei ' . basename($_FILES['fileToUpload']['name']) . ' wurde hochgeladen';
                $this->readContent();
            } else {
                echo 'Fehler beim Hochladen der Datei';
            }
        } else {
            echo 'Bitte laden Sie nur .ics Datein hoch.';
        }
    }

    private function readContent()
    {
        $this->content = file_get_contents($this->file);
        $this->deleteFile();
        $this->getValues();
    }

    private function getParts($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    private function getValues()
    {
        $title = $this->getParts($this->content, 'SUMMARY:', "\n");
        $desc = $this->getParts($this->content, 'DESCRIPTION:', "\n");
        $location = $this->getParts($this->content, 'LOCATION:', "\n");
        $dtstart = $this->getParts($this->content, 'DTSTART:', "\n");
        $dtstart_year = substr($dtstart, 0, 4);
        $dtstart_month = substr($dtstart, 4, 2);
        $dtstart_day = substr($dtstart, 6, 2);
        $dtstart_hour = substr($dtstart, 9, 2);
        $dtstart_minute = substr($dtstart, 11, 2);
        $dtstart_date = $dtstart_year . '-' . $dtstart_month . '-' . $dtstart_day;
        $dtstart_time = $dtstart_hour . ':' . $dtstart_minute;
        $dtend = $this->getParts($this->content, 'DTEND:', "\n");
        $dtend_year = substr($dtend, 0, 4);
        $dtend_month = substr($dtend, 4, 2);
        $dtend_day = substr($dtend, 6, 2);
        $dtend_hour = substr($dtend, 9, 2);
        $dtend_minute = substr($dtend, 11, 2);
        $dtend_date = $dtend_year . '-' . $dtend_month . '-' . $dtend_day;
        $dtend_time = $dtend_hour . ':' . $dtend_minute;
        $entry = new Entry();
        $entry->setID($this->storage->getMaxID() + 1);
        $entry->setTitle($title);
        $entry->setDesc($desc);
        $entry->setLocation($location);
        $entry->setStart($dtstart_date);
        $entry->setEnd($dtend_date);
        $entry->setFullDay(0);
        $entry->setTime($dtstart_time);
        $entry->setEndTime($dtend_time);
        $entry->setColor('#b2b8b6');
        $this->storage->saveEntry($entry);
        $last_id = $this->storage->getLastID();
        $ical = new ICalendar();
        $ical->createFile($entry, $last_id);
    }
    
    private function deleteFile()
    {
        unlink($this->file);
    }
}