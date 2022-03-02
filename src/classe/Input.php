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
    private $contrainte;

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

    /**
     * @return mixed
     */
    public function getContrainte()
    {
        return $this->contrainte;
    }

    /**
     * @return mixed
     */
    public function getContrainteForJS()
    {
        /**
         * ne pas retourner les callable sous forme de callable mais plutot sour forme de string
         * Ces string seront utiliser pour des appel ajax associé
         */
        $contraintes = [];
        foreach ($this->contrainte as $contrainte){

            if(is_callable($contrainte)){

                /**
                 * ne pas ajouter la contrainte si fonction anonyme car pas possible de faire fonctionner l'ajax
                 * idem si le callable n'est pas un array
                 */
                if ($contrainte instanceof \Closure || !is_array($contrainte)) {
                    continue;
                }

                $contrainte = 'AJAX_CHECK::'.base64_encode($contrainte[1]);
            }
            $contraintes[] = $contrainte;
        }

        return $contraintes;
    }

    /**
     * @param mixed $contrainte
     */
    public function setContrainte($contrainte): Input
    {
        $this->contrainte = $contrainte;
        return $this;
    }
}