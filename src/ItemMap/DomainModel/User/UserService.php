<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\User;

use DeepLots\ItemMap\DomainModel\User\Author;
use DeepLots\ItemMap\DomainModel\User\Operator;

interface UserService
{
    /**
     * サービスのユーザーデータをこのコンテキスト用に変換する.
     *
     * @access public
     * @param string $id
     * @return Author
     */
    public function authorFrom(string $id): Author;

    /**
     * サービスのユーザーデータをこのコンテキスト用に変換する.
     *
     * @access public
     * @param string $id
     * @return Operator
     */
    public function operatorFrom(string $id): Operator;
}
