<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserName;
use \InvalidArgumentException;

class NoUserName extends UserName
{
    /**
     * ユーザーネーム
     *
     * @access private
     * @var string
     */
    private $name;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->name = '';
    }

    /**
     * ゲッター
     * 
     * @access public
     * @param void
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $userName
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $userName): bool
    {
        if (!$userName instanceof UserName) {
            throw new InvalidArgumentException(UserName::class.'ではない値オブジェクトです.');
        }

        if ($userName instanceof NoUserName) {
            return true;
        }

        return false;
    }
}
