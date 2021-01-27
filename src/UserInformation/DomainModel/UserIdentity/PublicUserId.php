<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\ValueObject;
use \InvalidArgumentException;

class PublicUserId extends ValueObject
{
    /**
     * ログイン、公開用のID
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * 正規表現のパターン
     * 
     * @access private
     */
    private const PATTERN = '/\A[a-zA-z0-9_]{3,20}\z/u';

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $id
     * @return void
     */
    public function __construct(string $id)
    {
        $this->setId($id);
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
     * セッター
     *
     * @access private
     * @param string $id
     * @return void
     * @throws RangeException
     */
    private function setId(string $id): void
    {
        if (preg_match(self::PATTERN, $id) === 0) {
            throw new RangeException('不適切な形式のIDです.');
        }

        $this->id = $id;
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

        return $this->id() === $publicUserId->id();
    }
}
