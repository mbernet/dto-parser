<?php

namespace DTOParser;

class BaseDTO {

    /**
     * @param $data
     *
     * @return static
     */
    static function toDTO($data) : self {
        return DTOParser::toDTO($data, get_called_class());
    }

    /**
     * @param $data
     *
     * @return static[]
     */
    static function toDTOArray($data) : array {
        return DTOParser::toDTOArray($data, get_called_class());
    }

    /**
     * @return array
     */
    public function toArray() {
        return get_object_vars($this);
    }
}
