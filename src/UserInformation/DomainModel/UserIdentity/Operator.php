<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\UserInformation\DomainModel\UserIdentity\SystemUserId;
use \InvalidArgumentException;

class Operator extends ValueObject
{
    /**
     * オペレーターのシステムID
     *
     * @access private
     * @var SystemUserId
     */
    private $id;

    /**
     * コンストラクタ
     *
     * @access public
     * @param SystemUserId $id
     * @return void
     */
    public function __construct(SystemUserId $id)
    {
        $this->setId($id);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return SystemUserId
     */
    public function id(): SystemUserId
    {
        return $this->id;
    }

    /**
     * セッター
     *
     * @access private
     * @param SystemUserId $id
     * @return void
     */
    private function setId(SystemUserId $id): void
    {
        $this->id = $id;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $id
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $id): bool
    {
        if (!$id instanceof SystemUserId) {
            throw new InvalidArgumentException(SystemUserId::class.'ではない値オブジェクトです.');
        }

        return $this->id() === $id->id();
    }
}
