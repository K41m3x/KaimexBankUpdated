<?php
namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */


use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\ApiTrait;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Bank\Person;

class BankAccount implements BankAccountInterface

{
    use ApiTrait;
    protected $person_holder;
    protected $balance;
    protected $status;
    protected $overdraft;
    protected $currency;

    function __construct($balance)
    {
        $this->balance = $balance;
        $this->status = true;
        $this->overdraft = new NoOverdraft();
        $this->currency = "€";
    }

    public function transaction(BankTransactionInterface $bankTransaction): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException('Bank account should be opened.');
        }
        try {
            $newBalance = $bankTransaction->applyTransaction($this);
            $this->setBalance($newBalance);
        } catch (InvalidOverdraftFundsException $x) {
            throw new FailedTransactionException($x->getMessage());
        }
    }

    public function isOpen(): bool
    {
        return $this->status;
    }

    public function reopenAccount(): void
    {
        if (!$this->status) {
            $this->status = true;
        } else{
            throw new BankAccountException("The account is already opened");
        }
    }

    public function closeAccount(): void
    {
        $this->status = false;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }

    /**
     * Set the value of person_holder
     *
     * @return  self
     */ 
    public function setPerson($person_holder)
    {
        $this->person_holder = $person_holder;

        return $this;
    }
}
