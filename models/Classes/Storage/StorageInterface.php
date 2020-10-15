<?php

interface StorageInterface
{
    public function initialize($config);
    public function saveEntry($data);
    public function getEntries($sorting_method);
    public function deleteEntry($id);
}