<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\UserInformation\DomainModel\UserIdentity\Email;
use \InvalidArgumentException;

class NoEmail extends Email
{
    /**
     * メールアドレス
     *
     * @access private
     * @var string
     */
    private $email;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->email = '';
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $email
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $email): bool
    {
        if (!$email instanceof Email) {
            throw new InvalidArgumentException(Email::class.'ではない値オブジェクトです.');
        }

        if ($email instanceof NoEmail) {
            return true;
        }

        return false;
    }
}
