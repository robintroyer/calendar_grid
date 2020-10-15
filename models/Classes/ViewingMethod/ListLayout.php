<?php
class ListLayout implements ViewingMethodInterface {
    private $list;
    private $weekdays;
    private $month_names;
    private $storage;
    private $sorting_method;
    private $methods;
    public function inizialize($config)
    {
        $this->weekdays = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
        $this->month_names = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 
        'September', 'Oktober', 'November', 'Dezember'];
        $this->storage = $config->storage;
        $this->showDropdown();
        $this->methods = new Methods();
    }
    public function printData($entries)
    {
        if (isset($_POST['order'])) {
            $this->sorting_method = $_POST['order'];
        } else {
            $this->sorting_method = 0;
        }
        $this->list .= '<table class="table">';
        if (
            $this->sorting_method == 0
            || $this->sorting_method == 1
        ) {
            $dates = [];
            foreach ($entries as $entry) {
                if ($entry->getEnd() > 0) {
                    $days = $this->methods->getAllDates($entry->getStart(), $entry->getEnd());
                    foreach ($days as $day) {
                        $dates[] = $day;
                    }
                } else {
                    $dates[] = $entry->getStart();
                }
            }
            $dates = array_unique($dates);
            if ($this->sorting_method == 0) {
                usort($dates, [$this, 'dateSortAsc']);
            } elseif($this->sorting_method == 1) {
                usort($dates, [$this, 'dateSortDesc']);
            }
            foreach ($dates as $date) {
                $day = date('j', strtotime($date));
                $month = date('n', strtotime($date));
                $year = date('Y', strtotime($date));
                $weekday = date('N', strtotime($date));
                $this->list .= '<thead class="thead-light">';
                $this->list .= '<tr><th>' . $this->weekdays[$weekday - 1] . '</th>';
                $this->list .= '<th class="date">' . $day . '. ' . $this->month_names[$month - 1]
                . ' ' . $year . '</th><th></th></tr></thead>';
                $entries_of_day = $this->storage->getEntriesofDay($date);    
                foreach ($entries_of_day as $day_entry) {
                    $hex = $day_entry->getColor();
                    list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');
                    $this->list .= '<tr>';
                    if ($day_entry->getFullDay()) {
                        $this->list .= '<td>Ganztags</td>';
                    } elseif ($date == $day_entry->getStart()) {
                        $this->list .= '<td>ab ' . date('H:i', strtotime($day_entry->getTime())) . '</td>';
                    } elseif ($date == $day_entry->getEnd()) {
                        $this->list .= '<td>bis ' . date('H:i', strtotime($day_entry->getEndTime())) . '</td>';
                    } else {
                        $this->list .= '<td>Ganztags</td>';
                    }
                    $button = '<form method="post"><input class="details-button" type="submit"
                    name="expand" value="Details">';
                    $hidden_button = '<input type="hidden" name="id" value="' . $day_entry->getID() . '">';
                    $delete_button = '<input class="details-button" type="submit" name="delete" value="Loeschen">';
                    $hidden_delete_button = '<input type="hidden" name="delete_id"
                    value="' . $day_entry->getID() . '"></form>';
                    $this->list .= '<td>' . $day_entry->getTitle()
                    . '<div style="background-color:rgb(' . $r . ',' . $g . ',' . $b . ');"
                    class="list-colorbox rounded-pill"></div></td>';
                    $this->list .= '<td>' . $button . $hidden_button
                    . $delete_button . $hidden_delete_button . '</td></tr>';
                }
            }
        } elseif (
            $this->sorting_method == 2
            || $this->sorting_method == 3
        ) {
            $entries = $this->storage->getEntries($this->sorting_method);
            $used_chars = [];
            foreach ($entries as $entry) {
                if (!in_array(substr($entry->getTitle(), 0, 1), $used_chars)) {
                    $used_chars[] = substr($entry->getTitle(), 0, 1);
                    $this->list .= '<thead class="thead-light"><tr><th>'
                    . substr($entry->getTitle(), 0, 1) . '</th><th></th><th></th><th></th></tr></thead>';
                }
                $start_str = date('j', strtotime($entry->getStart())) . '. '
                . $this->month_names[date('n', strtotime($entry->getStart())) - 1]
                . ' ' . date('Y', strtotime($entry->getStart()));
                if ($entry->getTime()) {
                    $start_str .= ', ' . date('H:i', strtotime($entry->getTime()));
                }
                if ($entry->getEnd() != 0) {
                    $end_str = date('j', strtotime($entry->getEnd())) . '. '
                    . $this->month_names[date('n', strtotime($entry->getEnd())) - 1]
                    . ' ' . date('Y', strtotime($entry->getEnd()));
                    if ($entry->getEndTime()) {
                        $end_str .= ', ' . date('H:i', strtotime($entry->getEndTime()));
                    }
                } else {
                    $end_str = '';
                }
                $hex = $entry->getColor();
                list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');
                $this->list .= '<tr><td>' . $start_str . '</td><td>' . $end_str
                . '</td><td><strong>' . $entry->getTitle()
                . '</strong><div style="background-color:rgb(' . $r . ',' . $g . ',' . $b . ');"
                class="list-colorbox rounded-pill"></div></td>';
                $button = '<form method="post"><input type="submit" name="expand" value="Details">';
                $hidden_button = '<input type="hidden" name="id" value="' . $entry->getID() . '">';
                $delete_button = '<input class="details-button" type="submit" name="delete" value="Loeschen">';
                $hidden_delete_button = '<input type="hidden" name="delete_id"
                value="' . $entry->getID() . '"></form>';
                $this->list .= '<td>' . $button . $hidden_button
                . $delete_button . $hidden_delete_button . '</td></tr>';
            }
        }
        $this->list .= '</table>';
        $dom = new DOMDocument();
        $dom->loadHTML($this->list);
        $dom->saveHTML();
        return $this->list;
    }
    private function showDropdown()
    {
            if (isset($_POST['order'])) {
                $this->sorting_method = $_POST['order'];
            }
            $sorting_methods = ['Datum aufsteigend', 'Datum absteigend', 'Name aufsteigend', 'Name absteigend'];
            echo '<form method="post" name="sort">';
            echo '<label for="order">Sortieren nach&nbsp</label>';
            echo '<select name="order">';
            for ($i = 0; $i < count($sorting_methods); $i++) {
                if ($i == $this->sorting_method) {
                    echo '<option selected="selected" value="' . $i . '">' . $sorting_methods[$i] . '</option>';
                } else {
                    echo '<option value="' . $i . '">' . $sorting_methods[$i] . '</option>';
                }
            }
            echo '</select>&nbsp';
            echo '<input type="submit" value="Sortieren">';
            echo '</form>';
    }
    private function dateSortAsc($a, $b)
    {
        return strtotime($a) - strtotime($b);
    }
    private function dateSortDesc($a, $b)
    {
        return strtotime($b) - strtotime($a);
    }
}