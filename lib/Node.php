<?php

class Node
{
    private $id;
    private $text;
    private $parentid;
    private $order;
    private $depth = null;
    private $children = null;

    public function __construct($id, $text, $parentid, $order)
    {
        $this->id = $id;
        $this->text = $text;
        $this->parentid = $parentid;
        $this->order = $order;
    }

    public function __destruct()
    {

    }

    public function setNodeID($value)
    {

    }

    public function getNodeID()
    {
        return $this->id;
    }

    public function setNodeName($value)
    {

    }

    public function getNodeName()
    {
        return $this->text;
    }

    public function setParentID($value)
    {

    }

    public function getParentID()
    {

    }
}