<?php
namespace ComBank\Bank;
use ComBank\Support\Traits\ApiTrait;
class Person{
    use ApiTrait;
    private $name;

    private $idCard;

    private $email;

    function __construct($name, $idCard, $email) {
        $this->name = $name;
        $this->idCard = $idCard;
        $this->email = $email;

        if ($this->validateEmail($this->email)) {
            pl("Valid Email");
        } else {
            pl("Invalid Email");
        }
    }
}