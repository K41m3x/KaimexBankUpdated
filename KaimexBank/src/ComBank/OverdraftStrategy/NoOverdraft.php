<?php
namespace ComBank\OverdraftStrategy;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

class NoOverdraft implements OverdraftInterface
{
    public function isGrantOverdraftFunds(float $num): bool{
        return $num >= $this->getOverdraftFundsAmount();
    }

    public function getOverdraftFundsAmount(): float{
        return 0;
    }

}
