<?php
class Methods
{
    public function getAllDates($start, $end)
    {
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );
        $days = [];
        foreach ($period as $date) {
            $days[] = $date->format('Y-m-d');
        }
        $days[] = $end;
        return $days;
    }
}