<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use \InvalidArgumentException;
use \RangeException;

class Email extends ValueObject
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
     * @param string $email
     * @return void
     */
    public function __construct(string $email)
    {
        $this->setEmail($email);
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
     * セッター
     *
     * @access private
     * @param string $email
     * @return string
     * @throws RangeException
     */
    private function setEmail(string $email): void
    {
        switch (true) {
            // RFCに準拠しているかどうか(xxxxxx@[xxx.xxx.xxx]の形式は弾く)
            case false === filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE):
            case !preg_match('/@([^@\[]++)\z/', $email, $m):
                throw new RangeException('不適切な形式のメールアドレスです.');
    
            // ホストが存在するか
            case checkdnsrr($m[1], 'MX'):
            case checkdnsrr($m[1], 'A'):
            case checkdnsrr($m[1], 'AAAA'):
                break;
    
            default:
                throw new RangeException('不適切な形式のメールアドレスです.');
        }

        $this->email = $email;
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

        return $this->email() === $email->email();
    }
}
