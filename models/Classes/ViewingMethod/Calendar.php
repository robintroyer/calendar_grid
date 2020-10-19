<?php
class Calendar implements ViewingMethodInterface {
    private $calendar;
    private $month;
    private $year;
    private $weekdays;
    private $first_day;
    private $day_counter;
    private $max_days;
    private $max_days_counter;
    private $month_names;
    private $event_counter;
    private $methods;
    public function inizialize($config)
    {
        $this->month_names = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 
        'September', 'Oktober', 'November', 'Dezember'];
        $this->weekdays = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag',
        'Samstag', 'Sonntag'];
        $this->modifyMonth();
        if (!isset($this->event_counter)) {
            $this->event_counter = 0;
        }
        $this->arrowButtons();
        $this->showCurrentMonth();
        $this->methods = new Methods();
    }
    public function printData($entries)
    {
        $today = date('Y-m-d');
        $current_day = date('j', strtotime($today));
        $current_month = date('n', strtotime($today));
        $current_year = date('Y', strtotime($today));
        echo 
        '<style type="text/css">
            .title:after
            {
                content: "' . $this->month_names[$this->month - 1] . ' ' . $this->year . '";
            }
        </style>';
        $this->calendar = '<table><thead><tr class="wrapper_table">';
        $this->first_day = date('w',mktime(0, 0, 0, $this->month, 1, $this->year));
        $this->max_days = date('t',mktime(0, 0, 0, $this->month, 1, $this->year));
        $this->day_counter = 1;
        $this->max_days_counter = 1;
        foreach ($this->weekdays as $weekday) {
            $this->calendar .= '<th id="' . substr(strtolower($weekday), 0, 2) . '"><span>' . $weekday . '</span></th>';
        }
        $this->calendar .= '</tr></thead><tbody><tr>';
        // if ($this->first_day == 0) {
        //     $this->first_day = 7;
        // }
        // for ($i = 1; $i < $this->first_day; $i++) {
        //     $this->calendar .= '<td></td>';
        //     $this->day_counter++;
        // }
        // while ($this->max_days_counter <= $this->max_days) {
        //     if (
        //         $this->max_days_counter == $current_day
        //         && $this->month == $current_month
        //         && $this->year == $current_year
        //     ) {
        //         $this->calendar .= '<td><div class="cellheadercontainer">
        //         <h6 id="current_day" class="cellheader">' . $this->max_days_counter . '</h6></div></td>';
        //     } else {
        //         $this->calendar .= '<td><div class="cellheadercontainer"><h6 class="cellheader">'
        //         . $this->max_days_counter . '</h6></div></td>';
        //     }
        //     if ($this->day_counter == 7) {
        //         $this->calendar .= '</tr><tr>';
        //         $this->day_counter = 0;
        //     }
        //     $this->max_days_counter++;
        //     $this->day_counter++;
        // }
        // $this->calendar .= '</tbody></table>';
        // $table = $this->calendar;
        // $dom = new DOMDocument();
        // $dom->loadHTML($table);
        // if ($entries) {
        //     foreach ($entries as $entry) {
        //         if ($entry->getEnd() > 0) {
        //             $days = $this->methods->getAllDates($entry->getStart(), $entry->getEnd());
        //             foreach ($dom->getElementsByTagName('h6') as $key => $num) {
        //                 foreach ($days as $day) {
        //                     $day_number = date('j', strtotime($day));
        //                     $month = date('n', strtotime($day));
        //                     $year = date('Y', strtotime($day));
        //                     if (
        //                         $day_number == $num->nodeValue
        //                         && $this->month_names[$month - 1] == $this->month_names[$this->month - 1]
        //                         && $year == $this->year
        //                     ) {
        //                         if ($day == $days[0]) {
        //                             $this->generateEntry($dom, $entry, $num, 'first');
        //                         } elseif ($day == $days[count($days) - 1]) {
        //                             $this->generateEntry($dom, $entry, $num, 'last');
        //                         } else {
        //                             $this->generateEntry($dom, $entry, $num, '');
        //                         }
        //                     }
        //                 }
        //             }
        //         } else {
        //             $day = date('j', strtotime($entry->getStart()));
        //             $month = date('n', strtotime($entry->getStart()));
        //             $year = date('Y', strtotime($entry->getStart()));
        //             foreach ($dom->getElementsByTagName('h6') as $num) {
        //                 if (
        //                     $day == $num->nodeValue
        //                     && $this->month_names[$month - 1] == $this->month_names[$this->month - 1]
        //                     && $year == $this->year
        //                 ) {
        //                     $this->generateEntry($dom, $entry, $num, '');
        //                 }
        //             }
        //         }                
        //     }
        // }        
        // $this->table = $dom->saveHTML();
        // return $this->table;


        echo '<br /><br /><br />';
        $table = '';

        $table .= '<div class="wrapper_table">';
        foreach ($this->weekdays as $weekday) {
                $table .= '<th id="' . substr(strtolower($weekday), 0, 2) . '"><span>' . substr($weekday, 0, 2) . '</span></th>';
        }
        


        if ($this->first_day == 0) {
            $this->first_day = 7;
        }
        for ($i = 1; $i < $this->first_day; $i++) {
            $table .= '<h6></h6>';
            $this->day_counter++;
        }
        while ($this->max_days_counter <= $this->max_days) {
            if (
                $this->max_days_counter == $current_day
                && $this->month == $current_month
                && $this->year == $current_year
            ) {
                // $this->calendar .= '<td><div class="cellheadercontainer">
                // <h6 id="current_day" class="cellheader">' . $this->max_days_counter . '</h6></div></td>';
                $table .= '<div class="cell_wrapper"><h6 id="current_day">' . $this->max_days_counter . '</h6></div>';
            } else {
                // $this->calendar .= '<td><div class="cellheadercontainer"><h6 class="cellheader">'
                // . $this->max_days_counter . '</h6></div></td>';
                $table .= '<div class="cell_wrapper"><h6>' . $this->max_days_counter . '</h6></div>';
            }
            if ($this->day_counter == 7) {
                // $this->calendar .= '</tr><tr>';
                $this->day_counter = 0;
            }
            $this->max_days_counter++;
            $this->day_counter++;
        }



        $dom = new DOMDocument();
        $dom->loadHTML($table);
        if ($entries) {
            foreach ($entries as $entry) {
                if ($entry->getEnd() > 0) {
                    $days = $this->methods->getAllDates($entry->getStart(), $entry->getEnd());
                    foreach ($dom->getElementsByTagName('h6') as $key => $num) {
                        foreach ($days as $day) {
                            $day_number = date('j', strtotime($day));
                            $month = date('n', strtotime($day));
                            $year = date('Y', strtotime($day));
                            if (
                                $day_number == $num->nodeValue
                                && $this->month_names[$month - 1] == $this->month_names[$this->month - 1]
                                && $year == $this->year
                            ) {
                                // if ($day == $days[0]) {
                                //     $this->generateEntry($dom, $entry, $num, 'first');
                                // } elseif ($day == $days[count($days) - 1]) {
                                //     $this->generateEntry($dom, $entry, $num, 'last');
                                // } else {
                                //     $this->generateEntry($dom, $entry, $num, '');
                                // }
                                $this->colorizeDay($dom, $entry, $num);
                            }
                        }
                    }
                } else {
                    $day = date('j', strtotime($entry->getStart()));
                    $month = date('n', strtotime($entry->getStart()));
                    $year = date('Y', strtotime($entry->getStart()));
                    foreach ($dom->getElementsByTagName('h6') as $num) {
                        if (
                            $day == $num->nodeValue
                            && $this->month_names[$month - 1] == $this->month_names[$this->month - 1]
                            && $year == $this->year
                        ) {
                            // $this->generateEntry($dom, $entry, $num, '');
                            $this->colorizeDay($dom, $entry, $num);
                        }
                    }
                }                
            }
        }       
        $table = $dom->saveHTML();
        return $table;




        $table .= '</div>';

        echo $table;

        // print_r($entries);
        echo '<script type="text/javascript">
        function addClass()
        {
            var cells = document.getElementsByTagName("h6");
            console.log(cells);
        }
        
        addClass();
        </script>';

        echo '<br /><br /><br />';


    }
    private function colorizeDay($dom, $entry, $num)
    {
        $child = $dom->createDocumentFragment();
        $hex = $entry->getColor();
        list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');
        $xml = '<div style="background-color:rgb(' . $r . ', ' . $g . ', ' . $b . ');height:10px;width:10px;border-radius:50%;"></div>';
        $child->appendXML($xml);
        $div = $num->parentNode;
        $div->appendChild($child);
    }

    private function generateEntry($dom, $entry, $num, $thday)
    {
        $title = $entry->getTitle();
        $child = $dom->createDocumentFragment();
        $hex = $entry->getColor();
        list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');
        $xml = '<div class="entry border rounded"
        style="background-color:rgb(' . $r . ',' . $g . ',' . $b . ');"><span>';
        $xml .= $title . '<br />'; 
        $xml .= $entry->getLocation() . '<br />';
        if ($thday == 'first') {
            if (
                !$entry->getFullDay()
                && $entry->getTime() != 0
            ) {
                $time = $entry->getTime();
                $time = date('H:i', strtotime($time));
                $xml .= 'Start: ' . $time . '<br />';
            }
        } elseif ($thday == 'last') {
            if (
                !$entry->getFullDay()
                && $entry->getEndTime() != 0
            ) {
                $time = $entry->getEndTime();
                $time = date('H:i', strtotime($time));
                $xml .= 'Ende:' . $time . '<br />';
            }
        } else {
            if (!$entry->getFullDay()) {
                $xml .= 'Ganztags<br />';
            }
        }
        if ($entry->getFullDay()) {
            $xml .= 'Ganztagstermin<br />'; 
        }
        $button = '<form method="post"><input id="details_button" type="submit" name="expand" value="Details"></input>';
        $hidden_button = '<input type="hidden" name="id" value="' . $entry->getID() . '"></input>';
        $delete_button = '<input id="delete_button" type="submit" name="delete" value="Loeschen"></input>';
        $hidden_delete_button = '<input type="hidden" name="delete_id" value="' . $entry->getID() . '"></input></form>';
        $xml .= $button . $hidden_button . $delete_button . $hidden_delete_button . '</span></div>';
        $child->appendXML($xml);
        $div = $num->parentNode;
        $div->appendChild($child);
    }
    private function modifyMonth()
    {
        $current_month = date('n');
        $current_year = date('Y');
        if (!isset($_GET['month'])) {
            $_GET['month'] = $current_month;
        }
        if (!isset($_GET['year'])) {
            $_GET['year'] = $current_year;
        }
        if (isset($_POST['prevMonth'])) {
            if ($_GET['month'] > 1) {
                $_GET['month']--;
            } else {
                $_GET['month'] = 12;
                $_GET['year']--;
            }
            header('location:?viewingmethod=Monat&month=' . $_GET['month'] . '&year=' . $_GET['year']);
        }
        if (isset($_POST['nextMonth'])) {
            if ($_GET['month'] < 12) {
                $_GET['month']++;
            } else {
                $_GET['month'] = 1;
                $_GET['year']++;
            }
            header('location:?viewingmethod=Monat&month=' . $_GET['month'] . '&year=' . $_GET['year']);
        }
        if (isset($_POST['currentMonth'])) {
            $_GET['month'] = $current_month;
            $_GET['year'] = $current_year;
            header('location:?viewingmethod=Monat&month=' . $_GET['month'] . '&year=' . $_GET['year']);
        }
        $this->month = $_GET['month'];
        $this->year = $_GET['year'];
    }
    private function arrowButtons()
    {
        $previous_month = '<input id="arrow_left" type="submit" name="prevMonth" value="">';
        $next_month = '<input id="arrow_right" type="submit" name="nextMonth" value="">';
        if (
            $this->month == date('n')
            && $this->year == date('Y')
        ) {
            $current_month = '<input id="today" class="buttons_disabled" type="submit" name="currentMonth" value="heute" disabled>';
        } else {
            $current_month = '<input id="today" class="buttons" type="submit" name="currentMonth" value="heute">';
        }
        // echo '<form method="post">' . $previous_month . $next_month . $current_month . '</form>';
        // echo '<div id="top_wrapper">';
        echo '<form id="arrows_form" method="post"><div id="wrapper_arrows">' . $previous_month . $current_month . $next_month . '</div></form>';
    }
    private function showCurrentMonth()
    {
        // echo '<span class="title">' . $this->month_names[$this->month - 1] . ' ' . $this->year . '</span>';
    }
}