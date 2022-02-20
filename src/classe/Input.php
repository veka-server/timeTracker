<?php

namespace App\classe;

class Input
{

    private $key;
    private $type;
    private $label;
    private $icon;
    private $size;
    private $placeholder;
    private $required;
    private $validation;

    public function __construct()
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): Input
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): Input
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): Input
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): Input
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     */
    public function setRequired($required): Input
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param mixed $placeholder
     */
    public function setPlaceholder($placeholder): Input
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): Input
    {
        $this->size = $size;
        return $this;
    }

    public function setValidation(array $validation)
    {
        $this->validation = $validation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidation()
    {
        return $this->validation;
    }
}