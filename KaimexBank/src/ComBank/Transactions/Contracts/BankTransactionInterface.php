<?php namespace ComBank\Transactions\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:29 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;

interface BankTransactionInterface
{
    /**
     * @return float new balance after apply thr movrnt
     */
    public function applyTransaction(BankAccountInterface $bankAccountInterface):float;

    public function getTransactionInfo():string;

    public function getAmount():float;
}
