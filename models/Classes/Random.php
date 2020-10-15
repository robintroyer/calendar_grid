<?php
class Random {
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    public function generateRandomEvent()
    {
        $locations = ['Zell am See', 'Saalfelden', 'Mittersill', 'Neukirchen', 'Salzburg', 'Graz', 'Wien'];
        $rand_location = mt_rand(0, count($locations) - 1);
        $start_date = mt_rand(1, 31) . '-' . mt_rand(1, 12) . '-2020';
        $start_date = date('Y-m-d', strtotime($start_date));
        $is_valid = DateTime::createFromFormat('Y-m-d', $start_date) !== false;
        if (!$is_valid) {
            $this->generateRandomEvent();
        }
        $duration = mt_rand(1, 6);
        $end_date = date('Y-m-d', strtotime('+' . $duration . ' day', strtotime($start_date)));
        $values = [0, 1];
        $weights = [5, 1];
        $count = count($values);
        $i = 0;
        $n = 0;
        $num = mt_rand(0, array_sum($weights));
        while ($i < $count) {
            $n += $weights[$i];
            if ($n >= $num) {
                break;
            }
            $i++;
        }
        $fullday = $values[$i];
        if (!$fullday) {
            $start_time = mt_rand(6, 20) . ':' . (mt_rand(0, 3) * 15) . ':00';
            $end_time = mt_rand(6, 20) . ':' . (mt_rand(0, 3) * 15) . ':00';
        } else {
            $start_time = '00:00:00';
            $end_time = '00:00:00';
        }
        $color = '#' . str_pad(dechex(mt_rand(150, 255)), 2, '0', STR_PAD_LEFT)
        . str_pad(dechex(mt_rand(150, 255)), 2, '0', STR_PAD_LEFT)
        . str_pad(dechex(mt_rand(150, 255)), 2, '0', STR_PAD_LEFT);
        $entry = new Entry();
        $entry->setTitle('generierter Eintrag');
        $entry->setDesc('Das ist die Beschreibung für ' . $entry->getTitle());
        $entry->setLocation($locations[$rand_location]);
        $entry->setStart($start_date);
        $entry->setEnd($end_date);
        $entry->setTime($start_time);
        $entry->setEndTime($end_time);
        $entry->setColor($color);
        $entry->setFullDay($fullday);
        $this->storage->saveEntry($entry);
    }
}