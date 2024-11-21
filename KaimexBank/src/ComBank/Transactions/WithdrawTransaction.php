<?php
namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Transactions\Contracts\BankTransactionInterface;
class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $newBalance = $bankAccount->getBalance() - $this->amount;

        if ($bankAccount->getOverdraft()->isGrantOverdraftFunds($newBalance)) {
            return $newBalance;
        }
        if ($bankAccount->getOverdraft() == new NoOverdraft) {
            throw new InvalidOverdraftFundsException('a balance below 0 is not allowed');
        } else {
            throw new FailedTransactionException("a balance below -100 is not allowed");
        }
    }

    public function getTransactionInfo(): string
    {
        return "WITHDRAW_TRANSACTION";
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
