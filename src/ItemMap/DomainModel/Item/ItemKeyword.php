<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use \InvalidArgumentException;
use \RangeException;

class ItemKeyword extends ValueObject
{
    /**
     * キーワード
     *
     * @access private
     * @var string
     */
    private $keyword;

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $keyword
     * @return void
     */
    public function __construct(string $keyword)
    {
        $this->setKeyword($keyword);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function keyword(): string
    {
        return $this->keyword;
    }

    /**
     * セッター
     *
     * @access private
     * @param string $keyword
     * @return void
     * @throws RangeException
     */
    private function setKeyword(string $keyword): void
    {
        if ($keyword === '') {
            throw new RangeException('キーワードが空です.');
        }

        if (mb_strlen($keyword) > 30) {
            throw new RangeException('キーワードが30文字を超えています.');
        }

        //\\x0-\x1F\x7F:ASCIIコードで制御文字
        if (preg_match('/[\\x0-\x1F\x7F]/u', $keyword) === 1) {
            throw new RangeException('不適切な文字が含まれています.');
        }

        //\x20:ASCIIコードで半角スペース
        //\xC2\xA0:NO-BREAK SPACE(U+00A0)
        //\xE3\x80\x80:全角スペース(U+3000)
        if (preg_match('/[^\x20\xC2\xA0\xE3\x80\x80]/u', $keyword) === 0) {
            throw new RangeException('文字が含まれていません.');
        }

        $this->keyword = $keyword;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $keyword
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $keyword): bool
    {
        if (!$keyword instanceof ItemKeyword) {
            throw new InvalidArgumentException(ItemKeyword::class.'ではない値オブジェクトです.');
        }

        return $this->keyword() === $keyword->keyword();
    }
}
