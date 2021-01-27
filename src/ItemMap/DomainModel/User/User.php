<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\User;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\User\UserId;

abstract class User extends ValueObject
{
    /**
     * ユーザーID
     *
     * @access private
     * @var UserId
     */
    private $id;

    /**
     * コンストラクタ
     *
     * @access public
     * @param UserId $id
     * @return void
     */
    public function __construct(UserId $id)
    {
        $this->setId($id);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * セッター
     *
     * @access private
     * @param UserId $id
     * @return void
     */
    private function setId(UserId $id): void
    {
        $this->id = $id;
    }
}
