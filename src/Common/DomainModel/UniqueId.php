<?php
declare(strict_types=1);

namespace DeepLots\Common\DomainModel;

use DeepLots\Common\DomainModel\ValueObject;
use \RangeException;

abstract class UniqueId extends ValueObject
{
    /**
     * ユニークなID
     * 
     * UUID(v4)からハイフンを除いた文字列
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * 正規表現で用いられるパターンを表す定数
     *
     * @access private
     */
    private const PATTERN = '/\A[a-z0-9]{32}\z/u';

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
}
