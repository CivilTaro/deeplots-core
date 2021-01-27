<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\SeparatedDomainObject;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\Common\DomainModel\UniqueId;
use DeepLots\ItemMap\DomainModel\User\UserId;
use \RangeException;
use \InvalidArgumentException;

class TranslatedUserId extends UniqueId implements UserId
{
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $userId
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $userId): bool
    {
        if (!$userId instanceof TranslatedUserId) {
            throw new InvalidArgumentException(TranslatedUserId::class.'ではない値オブジェクトです.');
        }

        return $this->id() === $userId->id();
    }
}
