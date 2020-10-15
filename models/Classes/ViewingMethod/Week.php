<?php
class Week implements ViewingMethodInterface
{
    private $table;
    private $weekdays;
    private $weeknumber;
    private $current_year;
    private $current_week;
    private $methods;
    public function inizialize($config)
    {
        $this->weekdays = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag',
        'Freitag', 'Samstag', 'Sonntag'];
        $this->current_year = date('Y');
        $this->current_week = date('W');
        if (isset($_GET['kw'])) {
            $this->weeknumber = $_GET['kw'];
        } else {
            $this->weeknumber = $this->current_week;
        }
        $this->methods = new Methods();
    }
    public function printData($entries)
    {   
        $this->showButtons();
        // echo '<span class="title">KW ' . $this->weeknumber . '</span>';
        echo 
        '<style type="text/css">
            .title:after
            {
                content: "KW ' . $this->weeknumber . '";
            }
        </style>';
        $this->table = '<table class="table table-bordered"><thead class="thead-dark"><tr>';
        $this->table .= '<th style="text-align:center;">#</th>';
        $dates = $this->generateDates();
        for ($i = 1; $i <= 7; $i++){
            $day_number = date('d', strtotime($dates[$i - 1]));
            $month_number = date('m', strtotime($dates[$i - 1]));
            $year = date('Y', strtotime($dates[$i - 1]));
            $this->table .= '<th style="text-align:center;">' . $this->weekdays[$i - 1]
            . ', ' . $day_number . '.' . $month_number . '.' . $year . '</th>';
        }
        $this->table .= '</tr>';
        $this->table .= '</thead>';
        for ($i = 0; $i <= 23; $i++) {
            $this->table .= '<tr><th>' . $i . ':00</th>';
            foreach ($this->weekdays as $weekday) {
                $this->table .= '<td></td>';
            }
            $this->table .= '</tr>';
        }
        $this->table .= '</table>';
        $dom = new DOMDocument();
        $dom->loadHTML($this->table);
        $tds = $dom->getElementsByTagName('td');
        $cells = [];
        $counter = 0;
        $arraycounter = 0;
        foreach ($tds as $td) {
            $cells[$arraycounter][] = $td;
            $counter++;
            if ($counter > 6) {
                $counter = 0;
                $arraycounter++;
            }
        }
        foreach ($entries as $entry) {
            if ($entry->getEnd() != 0) {
                $dates = $this->methods->getAllDates($entry->getStart(), $entry->getEnd());
            } else {
                $start = $entry->getStart();
            }
            $start_time_hour = date('G', strtotime($entry->getTime()));
            $end_time_hour = date('G', strtotime($entry->getEndTime()));
            $color = $entry->getColor();
            list($r, $g, $b) = sscanf($color, '#%02x%02x%02x');
            $div = '<div class="week-entry border rounded"
            style="background-color:rgb(' . $r . ',' . $g . ',' . $b . ');"><form method="post"><input type="submit" class="year_details_button"
            name="expand" value="Details" /><input type="hidden" name="id" value="' . $entry->getID() . '" /></form></div>';
            if ($entry->getFullday()) {
                if (isset($start)) {
                    $calendar_week = date('W', strtotime($start));
                    if ($calendar_week == $_GET['kw']) {
                        $day_of_week = date('N', strtotime($start));
                        for ($i = 0; $i <= 23; $i++) {
                            $this->generateEntry($cells[$i][$day_of_week - 1], $div, $dom);
                        }
                    }
                } elseif(isset($dates)) {
                    foreach ($dates as $date) {
                        $calendar_week = date('W', strtotime($date));
                        if ($calendar_week == $_GET['kw']) {
                            $day_of_week = date('N', strtotime($date));
                            for ($i = 0; $i <= 23; $i++) {
                                $this->generateEntry($cells[$i][$day_of_week - 1], $div, $dom);
                            }
                        }
                    }
                }
            } else {
                if (isset($dates)) {
                    foreach ($dates as $key => $date) {
                        $calendar_week = date('W', strtotime($date));
                        if ($calendar_week == $_GET['kw']) {
                            $day_of_week = date('N', strtotime($date));
                            if ($key == array_key_first($dates)) {
                                for ($i = $start_time_hour; $i <= 23; $i++) {
                                    $this->generateEntry($cells[$i][$day_of_week - 1], $div, $dom);
                                }
                            } elseif ($key == array_key_last($dates)) {
                                for ($i = 0; $i <= $end_time_hour; $i++) {
                                    $this->generateEntry($cells[$i][$day_of_week - 1], $div, $dom);
                                }
                            } else {
                                for ($i = 0; $i <= 23; $i++) {
                                    $this->generateEntry($cells[$i][$day_of_week - 1], $div, $dom);
                                }
                            }
                        }
                    }
                }
            }
            unset($dates);
            unset($start);
        }
        $this->table = $dom->saveHTML();
        return $this->table;
    }
    private function generateEntry($cell, $xml, $dom)
    {
        $child = $dom->createDocumentFragment();
        $child->appendXML($xml);
        $cell->appendChild($child);
    }
    private function generateDates()
    {
        $dto = new DateTime();
        $dto->setISODate($_GET['year'], $_GET['kw']);
        $result['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $result['week_end'] = $dto->format('Y-m-d');
        return $this->methods->getAllDates($result['week_start'], $result['week_end']);
    }
    private function showButtons()
    {
        clearstatcache();
        $previous_week = '<input type="submit" name="week" value="Zurueck">';
        $next_week = '<input type="submit" name="week" value="Vor">';
        if (
            $_GET['kw'] == date('W')
            && $_GET['year'] == date('Y')
        ) {
            $current_week = '<input type="submit" name="week" value="aktuelle Woche" disabled>';
        } else {
            $current_week = '<input type="submit" name="week" value="aktuelle Woche">';
        }
        echo '<form method="post">' . $previous_week . $next_week . $current_week . '</form>';
        if (!isset($_GET['kw'])) {
            $_GET['kw'] = $this->current_week;
        }
        if (!isset($_GET['year'])) {
            $_GET['year'] = $this->current_year;
            header('location:?viewingmethod=Woche&kw=' . $_GET['kw'] . '&year=' . $_GET['year']);
        }
        if (isset($_POST['week'])) {
            if ($_POST['week'] == 'Zurueck') {
                if ($_GET['kw'] == 1) {
                    if (date('L', strtotime($this->current_year - 1 . '-01-01'))){
                        if (date('N', strtotime($this->current_year - 1 . '-01-01')) >= 3) {
                            $_GET['kw'] = 53;
                        }
                        $_GET['kw'] = 52;
                    } else {
                        if (date('N', strtotime($this->current_year - 1 . '-01-01')) >= 4) {
                            $_GET['kw'] = 53;
                        }
                        $_GET['kw'] = 52;
                    }
                    $_GET['year']--;
                } else {
                    $_GET['kw']--;
                }
            } elseif ($_POST['week'] == 'Vor') {
                if ($_GET['kw'] == date('W', strtotime($this->current_year . '-12-28'))) {
                    $_GET['kw'] = 1;
                    $_GET['year']++;
                } else {
                    $_GET['kw']++;
                }
            } elseif ($_POST['week'] == 'aktuelle Woche') {
                $_GET['kw'] = $this->current_week;
                $_GET['year'] = $this->current_year;
            }
            header('location:?viewingmethod=Woche&kw=' . $_GET['kw'] . '&year=' . $_GET['year']);
        }
    }
}