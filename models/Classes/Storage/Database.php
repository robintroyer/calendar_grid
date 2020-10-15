<?php
    class Database implements StorageInterface
    {
        protected $conn;
        private $appointments;
        public function initialize($config)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->conn = new mysqli($config->server, $config->username,
            $config->password, $config->database);
            if($this->conn->connect_error){
                die('Connection failed: ' . $this->conn->connect_error);
            }
            // print_r($_GET);
        }
        public function saveEntry($data)
        {
            $sql = "INSERT INTO termine (title, `description`, `location`,
            startDate, endDate, fullday, `time`, endTime, color)
            VALUES ('".$data->getTitle()."', '".$data->getDesc()."', '".$data->getLocation()."',
            '".$data->getStart()."', '".$data->getEnd()."', '".$data->getFullDay()."',
            '".$data->getTime()."', '".$data->getEndTime()."', '".$data->getColor()."')";
            if ($this->conn->query($sql) === true) {
                session_start();
                $_SESSION['new_record'] = 'New record added successfully';
                $ical = new ICalendar();
                $ical->createFile($data, $this->conn->insert_id);
                switch ($_GET['viewingmethod']) {
                    case 'Jahr':
                        header('location:index.php?viewingmethod=' . $_GET['viewingmethod'] . '&year=' . $_GET['year']);
                        break;
                    case 'Monat':
                        header('location:index.php?viewingmethod=' . $_GET['viewingmethod'] . '&month=' . $_GET['month'] . '&year=' . $_GET['year']);
                        break;
                    case 'Woche':
                        header('location:index.php?viewingmethod=' . $_GET['viewingmethod'] . '&kw=' . $_GET['kw'] . '&year=' . $_GET['year']);
                        break;
                    case 'Liste':
                        header('location:index.php?viewingmethod=' . $_GET['viewingmethod']);
                        break;
                    default:
                    break;
                }
                ob_end_flush();
                exit();
            } else {
                echo 'Error'  . $sql . '<br>' . $this->conn->error;
            }
        }
        public function getEntries($sorting_method)
        {
            $this->appointments = [];
            if ($sorting_method == 0) {
                $sql = "SELECT id, title, `description`, `location`, startDate,
                endDate, fullday, `time`, endTime, color
                FROM termine";
            } elseif ($sorting_method == 2) {
                $sql = "SELECT id, title, `description`, `location`, startDate,
                endDate, fullday, `time`, endTime, color
                FROM termine
                ORDER BY title ASC";
            } elseif($sorting_method == 3) {
                $sql = "SELECT id, title, `description`, `location`, startDate,
                endDate, fullday, `time`, endTime, color
                FROM termine
                ORDER BY title DESC";
            }
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $entry = new Entry();
                    $entry->setID($row['id']);
                    $entry->setTitle($row['title']);
                    $entry->setDesc($row['description']);
                    $entry->setLocation($row['location']);
                    $entry->setStart($row['startDate']);
                    $entry->setEnd($row['endDate']);
                    $entry->setFullDay($row['fullday']);
                    $entry->setTime($row['time']);
                    $entry->setEndTime($row['endTime']);
                    $entry->setColor($row['color']);
                    $this->appointments[] = $entry;
                }
            }
            return $this->appointments;
        }
        public function getLastID()
        {
            return $this->conn->insert_id;
        }
        public function deleteEntry($id)
        {
            $sql = "DELETE
            FROM termine
            WHERE id = $id";
            if ($this->conn->query($sql)) {
                echo 'Record deleted successfully';
                
            } else {
                'Error deleting record: ' . $this->conn->error;
            }
        }
        public function editEntry($new_entry)
        {
            $sql = "UPDATE termine
            SET title = '".$new_entry->getTitle()."', `description` = '".$new_entry->getDesc()."',
            `location` = '".$new_entry->getLocation()."', startDate = '".$new_entry->getStart()."', 
            endDate = '".$new_entry->getEnd()."', fullday = '".$new_entry->getFullDay()."', 
            `time` = '".$new_entry->getTime()."', endTime = '".$new_entry->getEndTime()."', 
            color = '".$new_entry->getColor()."'
            WHERE id = '".$new_entry->getID()."'";
            if ($this->conn->query($sql)) {
                echo 'Record updated successfully';
            } else {
                echo 'Error updating record: ' . $this->conn->error;
            }
        }
        public function getSingleEntry($id)
        {
            $sql = "SELECT id, title, `description`, `location`, startDate,
            endDate, fullday, `time`, endTime, color
            FROM termine
            WHERE id = $id";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $entry = new Entry();
                    $entry->setID($row['id']);
                    $entry->setTitle($row['title']);
                    $entry->setDesc($row['description']);
                    $entry->setLocation($row['location']);
                    $entry->setStart($row['startDate']);
                    $entry->setEnd($row['endDate']);
                    $entry->setFullDay($row['fullday']);
                    $entry->setTime($row['time']);
                    $entry->setEndTime($row['endTime']);
                    $entry->setColor($row['color']);
                }
            }
            return $entry;
        }
        public function getEntriesOfDay($date)
        {
            $entries = [];
            $sql = "SELECT id, title, `description`, `location`, startDate,
            endDate, fullday, `time`, endTime, color
            FROM termine
            WHERE endDate = 0 AND '$date' = startDate";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $entry = new Entry;
                    $entry->setID($row['id']);
                    $entry->setTitle($row['title']);
                    $entry->setDesc($row['description']);
                    $entry->setLocation($row['location']);
                    $entry->setStart($row['startDate']);
                    $entry->setEnd($row['endDate']);
                    $entry->setFullDay($row['fullday']);
                    $entry->setTime($row['time']);
                    $entry->setEndTime($row['endTime']);
                    $entry->setColor($row['color']);
                    $entries[] = $entry;
                }
            }
            $sql = "SELECT id, title, `description`, `location`, startDate,
            endDate, fullday, `time`, endTime, color
            FROM termine
            WHERE '$date' BETWEEN startDate AND endDate";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $entry = new Entry;
                    $entry->setID($row['id']);
                    $entry->setTitle($row['title']);
                    $entry->setDesc($row['description']);
                    $entry->setLocation($row['location']);
                    $entry->setStart($row['startDate']);
                    $entry->setEnd($row['endDate']);
                    $entry->setFullDay($row['fullday']);
                    $entry->setTime($row['time']);
                    $entry->setEndTime($row['endTime']);
                    $entry->setColor($row['color']);
                    $entries[] = $entry;
                }
            }
            return $entries;
        }
        public function getMaxID()
        {
            $sql = 'SELECT max(id)
            FROM termine';
            $result = $this->conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    return $row['max(id)'];
                }
            }
        }
    }