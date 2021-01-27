<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\User;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\User\User;
use DeepLots\ItemMap\DomainModel\User\UserId;
use \InvalidArgumentException;

class Operator extends User
{
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $operator
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $operator): bool
    {
        if (!$operator instanceof Operator) {
            throw new InvalidArgumentException(Operator::class.'ではない値オブジェクトです.');
        }

        return $this->id()->equals($operator->id());
    }
}
