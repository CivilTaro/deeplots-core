<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\User;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\User\User;
use DeepLots\ItemMap\DomainModel\User\UserId;
use \InvalidArgumentException;

class Author extends User
{
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $author
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $author): bool
    {
        if (!$author instanceof Author) {
            throw new InvalidArgumentException(Author::class.'ではない値オブジェクトです.');
        }

        return $this->id()->equals($author->id());
    }
}
