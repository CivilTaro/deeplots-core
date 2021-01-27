<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\UserInformation\DomainModel\UserIdentity\PublicUserId;
use \InvalidArgumentException;

class NoPublicUserId extends PublicUserId
{
    /**
     * ログイン、公開用のID
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->id = '';
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $publicUserId
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $publicUserId): bool
    {
        if (!$publicUserId instanceof PublicUseId) {
            throw new InvalidArgumentException(PublicUserId::class.'ではない値オブジェクトです.');
        }

        if ($publicUserId instanceof NoPublicUserId) {
            return true;
        }

        return false;
    }
}
