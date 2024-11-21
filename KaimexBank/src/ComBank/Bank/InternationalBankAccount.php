<?php
namespace ComBank\Bank;
use ComBank\Bank\BankAccount;


class InternationalBankAccount extends BankAccount
{
    function getConvertedBalance(): float
    {
        return $this->convertBalance($this->getBalance());
    }

    function getConvertedCurrency(): string
    {
        return "$";
    }
}