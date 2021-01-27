<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\Common\DomainModel\UniqueId;
use \InvalidArgumentException;

class SystemUserId extends UniqueId
{
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $systemUserId
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $systemUserId): bool
    {
        if (!$systemUserId instanceof SystemUserId) {
            throw new InvalidArgumentException(ItemId::class.'ではない値オブジェクトです.');
        }

        return $this->id() === $systemUserId->id();
    }
}
