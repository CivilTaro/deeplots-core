<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\SeparatedDomainObject;

use DeepLots\ItemMap\DomainModel\User\UserService;
use DeepLots\ItemMap\DomainModel\User\Author;
use DeepLots\ItemMap\DomainModel\User\Operator;
use DeepLots\ItemMap\PortAdapter\SeparatedDomainObject\TranslatedUserId;

class TranslatingUserService implements UserService
{
    /**
     * ユーザーIDからローカルの値オブジェクトに変換する.
     *
     * @access public
     * @param string $id
     * @return Author
     */
    public function authorFrom(string $id): Author
    {
        $userId = new TranslatedUserId($id);

        return new Author($userId);
    }

    /**
     * ユーザーIDからローカルの値オブジェクトに変換する.
     *
     * @access public
     * @param string $id
     * @return Operator
     */
    public function operatorFrom(string $id): Operator
    {
        $userId = new TranslatedUserId($id);

        return new Operator($userId);
    }
}
