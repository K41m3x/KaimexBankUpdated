<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\Person;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Support\Traits\ApiTrait;

use ComBank\Exceptions\ZeroAmountException;


require_once 'bootstrap.php';

//---[Bank account 1]---/
// create a new account1 with balance 400
$person = new Person("Alex", 1928375, "kaimexalabau@gmail.com");
$account1 = new BankAccount(400);
$account1->setPerson(new Person("Alex",3736478342,"kaimexalabau@gmail.com"));
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    $USaccount = new InternationalBankAccount(100);
    $USaccount->setPerson(new Person("Alex",3736478342,"kaimexalabau@gmail.com"));
    echo "Account 1 Balance:" . $account1->getBalance();
    // crear una cuenta con balance 400 y mostrar su balance
    $bankAccount1 = new BankAccount(400);
    $bankAccount1->setPerson(new Person("Alex",3736478342,"kaimexalabau@gmail.com"));
    $bankAccount1->applyOverdraft(new NoOverdraft());
    // close account
    $bankAccount1->closeAccount();
    echo "<br><br>BankAccount1 Closed";
    // reopen account
    $bankAccount1->reopenAccount();
    echo "<br><br>BankAccount1 Opened<br>";
    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new DepositTransaction(150));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    //aqui es donde hay que hacerlo
    $bankAccount1->transaction(new WithdrawTransaction(25));

    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(600));
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());



$bankAccount2 = new BankAccount(100);
$bankAccount2->setPerson(new Person("Alex",3736478342,"kaimexalabau@gmail.com"));
$bankAccount2->applyOverdraft(new SilverOverdraft());
//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {

    // show balance account
    pl("Current BankAccount2 balance: " . $bankAccount2->getBalance());
    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300));

    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50));

    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120));

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

try {

} catch (BankAccountException $e) {
    pl($e->getMessage());
}
//---[Testing conversion to dolars]---/

pl("My actual balance on euros with BankAccount1 is " . $USaccount->getBalance());

pl("My balance on dolars are " . $USaccount->getConvertedBalance() . $USaccount->getConvertedCurrency());

//---[Testing Email validation]---/
pl("Email: kaimexalabau@gmail.com");
$person = new Person("Alex", 1928375, "kaimexalabau@gmail.com");
pl("Email: example");
$person = new Person("Alex", 1928375, "example");

//---[Testing Fraud Control]---/

try {
    pl("My actual balance with BankAccount1 is " . $USaccount->getBalance());
    $USaccount->transaction(new DepositTransaction(21000));
    pl("My balance after the transaction: " . $USaccount->getBalance());

} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}

//---[Testing Free API]---/
pl($USaccount->passGeneration());