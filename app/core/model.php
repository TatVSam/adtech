<?php

class Model
{
    private $entity;

    public function __contruct($entity = null)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}