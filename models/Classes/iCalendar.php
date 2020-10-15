<?php
class ICalendar
{
    private $filename;
    private $data;
    public function createFile($entry, $last_id)
    {
        $this->generateFileName($last_id);
        $id = $entry->getID();
        $title = $entry->getTitle();
        $desc = $entry->getDesc();
        $location = $entry->getLocation();
        $start = date('Y', strtotime($entry->getStart())) . date('m', strtotime($entry->getStart()))
        . date('d', strtotime($entry->getStart())) . 'T'
        . date('H', strtotime($entry->getTime())) . date('i', strtotime($entry->getTime()))
        . date('s', strtotime($entry->getTime())) . 'Z';
        $end = date('Y', strtotime($entry->getEnd())) . date('m', strtotime($entry->getEnd()))
        . date('d', strtotime($entry->getEnd())) . 'T'
        . date('H', strtotime($entry->getEndTime())) . date('i', strtotime($entry->getEndTime()))
        . date('s', strtotime($entry->getEndTime())) . 'Z';
        $stamp = date('Y') . date('m') . date('d') . 'T' . date('H') . date('i') . date('s') . 'Z';
        $this->data .= "BEGIN:VCALENDAR\n";
        $this->data .= "VERSION:2.0\n";
        $this->data .= "PRODID:Robin\n";
        $this->data .= "METHOD:PUBLISH\n";
        $this->data .= "BEGIN:VEVENT\n";
        $this->data .= "UID:$id\n";
        $this->data .= "LOCATION:$location\n";
        $this->data .= "SUMMARY:$title\n";
        $this->data .= "DESCRIPTION:$desc\n";
        $this->data .= "CLASS:PUBLIC\n";
        $this->data .= "DTSTART:$start\n";
        $this->data .= "DTEND:$end\n";
        $this->data .= "DTSTAMP:$stamp\n";
        $this->data .= "END:VEVENT\n";
        $this->data .= "END:VCALENDAR";
        file_put_contents(__DIR__ . '/../../ical/' . $this->filename, $this->data);
    }
    public function editFile($entry)
    {
        $files = scandir(__DIR__ . '/../../ical');
        foreach ($files as $file) {
            if ($file == 'entry' . $entry->getID() . '.ics') {
                $id = $entry->getID();
                $title = $entry->getTitle();
                $desc = $entry->getDesc();
                $location = $entry->getLocation();
                $start = date('Y', strtotime($entry->getStart())) . date('m', strtotime($entry->getStart()))
                . date('d', strtotime($entry->getStart())) . 'T'
                . date('H', strtotime($entry->getTime())) . date('i', strtotime($entry->getTime()))
                . date('s', strtotime($entry->getTime())) . 'Z';
                $end = date('Y', strtotime($entry->getEnd())) . date('m', strtotime($entry->getEnd()))
                . date('d', strtotime($entry->getEnd())) . 'T'
                . date('H', strtotime($entry->getEndTime())) . date('i', strtotime($entry->getEndTime()))
                . date('s', strtotime($entry->getEndTime())) . 'Z';
                $stamp = date('Y') . date('m') . date('d') . 'T' . date('H') . date('i') . date('s') . 'Z';
                $this->data .= "BEGIN:VCALENDAR\n";
                $this->data .= "VERSION:2.0\n";
                $this->data .= "PRODID:Robin\n";
                $this->data .= "METHOD:PUBLISH\n";
                $this->data .= "BEGIN:VEVENT\n";
                $this->data .= "UID:$id\n";
                $this->data .= "LOCATION:$location\n";
                $this->data .= "SUMMARY:$title\n";
                $this->data .= "DESCRIPTION:$desc\n";
                $this->data .= "CLASS:PUBLIC\n";
                $this->data .= "DTSTART:$start\n";
                $this->data .= "DTEND:$end\n";
                $this->data .= "DTSTAMP:$stamp\n";
                $this->data .= "END:VEVENT\n";
                $this->data .= "END:VCALENDAR";
                file_put_contents(__DIR__ . '/../../ical/' . $file, $this->data);
            }
        }
    }
    private function generateFileName($last_id)
    {
        $this->filename = 'entry' . $last_id . '.ics';
    }
}