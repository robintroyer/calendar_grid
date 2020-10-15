<?php
class Entry
{
    protected $id;
    protected $title;
    protected $desc;
    protected $location;
    protected $start;
    protected $end;
    protected $fullday;
    protected $time;
    protected $end_time;
    protected $color;
    public function setID($id)
    {
        $this->id = $id;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }
    public function setLocation($location)
    {
        $this->location = $location;
    }
    public function setStart($start)
    {
        $this->start = $start;
    }
    public function setEnd($end)
    {
        $this->end = $end;
    }
    public function setFullDay($fullday)
    {
        $this->fullday = $fullday;
    }
    public function setTime($time)
    {
        $this->time = $time;
    }
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }
    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getID()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDesc()
    {
        return $this->desc;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getStart()
    {
        return $this->start;
    }
    public function getEnd()
    {
        return $this->end;
    }
    public function getFullDay()
    {
        return $this->fullday;
    }
    public function getTime()
    {
        return $this->time;
    }
    public function getEndTime()
    {
        return $this->end_time;
    }
    public function getColor()
    {
        return $this->color;
    }
}