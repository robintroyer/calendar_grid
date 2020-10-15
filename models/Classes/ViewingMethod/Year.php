<?php
class Year implements ViewingMethodInterface
{
    private $table;
    private $methods;
    public function inizialize($config)
    {
        $this->month_names = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 
        'September', 'Oktober', 'November', 'Dezember'];
        $this->methods = new Methods();
    }
    public function printData($entries)
    {
        $leap_year = date('L', strtotime($_GET['year'] . '-01-01'));
        $this->showButtons();
        // echo '<span class="title">' . $_GET['year'] . '</span>';
        echo 
        '<style type="text/css">
            .title:after
            {
                content: "' . $_GET['year'] . '";
            }
        </style>';
        $this->table = '<table class="table table-bordered"><thead class="thead-dark"><tr>';
        $this->table .= '<th>#</th>';
        foreach ($this->month_names as $month_name) {
            $this->table .= '<th>' . $month_name . '</th>';
        }
        $this->table .= '</tr></thead>';
        for ($i = 1; $i <= 31; $i++) {
            $this->table .= '<tr>';
            $this->table .= '<th>' . $i . '</th>';
            foreach ($this->month_names as $month_name) {
                $this->table .= '<td';
                if (
                    (
                        $month_name == 'April'
                        || $month_name == 'Juni'
                        || $month_name == 'September'
                        || $month_name == 'November'
                    )
                    && $i == 31
                    || (
                        $month_name == 'Februar'
                        && $leap_year
                        && (
                            $i == 30
                            || $i == 31
                        )
                    )
                    || (
                        $month_name == 'Februar'
                        && !$leap_year
                        && (
                            $i == 29
                            || $i == 30
                            || $i == 31
                        )
                    )
                ) {
                    $this->table .= ' class="no-day"';
                }

                if (
                    $i == date('j')
                    && array_keys($this->month_names, $month_name)[0] + 1 == date('n')
                    && $_GET['year'] == date('Y')
                ) {
                    $this->table .= ' class="today"';
                }
                $this->table .= '></td>';
            }
            $this->table .= '</tr>';
        }
        $this->table .= '</table>';
        if ($entries) {
            $dom = new DOMDocument();
            $dom->loadHTML($this->table);
            $tds = $dom->getElementsByTagName('td');
            $cells = [];
            $counter = 0;
            $arraycounter = 0;
            foreach ($tds as $td) {
                $cells[$arraycounter][] = $td;
                $counter++;
                if ($counter > 11) {
                    $counter = 0;
                    $arraycounter++;
                }
            }
            foreach ($entries as $entry) {
                if ($entry->getEnd() != 0) {
                    $dates = $this->methods->getAllDates($entry->getStart(), $entry->getEnd());
                } else {
                    $dates = [$entry->getStart()];
                }
                $color = $entry->getColor();
                list($r, $g, $b) = sscanf($color, '#%02x%02x%02x');
                $div = '<div class="week-entry border rounded"
                style="background-color:rgb(' . $r . ',' . $g . ',' . $b . ');">
                <form method="post"><input type="submit" class="year_details_button" name="expand" value="Details" /><input type="hidden"
                name="id" value="' . $entry->getID() . '" /></form></div>';
                foreach ($dates as $date) {
                    if (date('Y', strtotime($date)) == $_GET['year']) {
                        $month = date('n', strtotime($date));
                        $day = date('j', strtotime($date));
                        $this->generateEntry($cells[$day - 1][$month - 1], $div, $dom);
                    }
                }
            }
            $this->table = $dom->saveHTML();
        }
        return $this->table;
    }
    private function generateEntry($cell, $xml, $dom)
    {
        $child = $dom->createDocumentFragment();
        $child->appendXML($xml);
        $cell->appendChild($child);
    }
    private function showButtons()
    {
        $prev_year = '<input type="submit" name="year" value="Zurueck">';
        $next_year = '<input type="submit" name="year" value="Vor">';
        if ($_GET['year'] == date('Y')) {
            $curr_year = '<input type="submit" name="year" value="aktuelles Jahr" disabled>';
        } else {
            $curr_year = '<input type="submit" name="year" value="aktuelles Jahr">';
        }
        echo '<form method="post">' . $prev_year . $next_year . $curr_year . '</form>';
        if (!isset($_GET['year'])) {
            header('location:?viewingmethod=Jahr&year=' . date('Y'));
        }
        if (isset($_POST['year'])) {
            if ($_POST['year'] == 'Zurueck') {
                $_GET['year']--;
                header('location:?viewingmethod=Jahr&year=' . $_GET['year']);

            } elseif ($_POST['year'] == 'Vor') {
                $_GET['year']++;
                header('location:?viewingmethod=Jahr&year=' . $_GET['year']);

            } elseif ($_POST['year'] == 'aktuelles Jahr') {
                $_GET['year'] = date('Y');
                header('location:?viewingmethod=Jahr&year=' . $_GET['year']);
            }
        }
    }
}