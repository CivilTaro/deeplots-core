<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\Keyword;
use \InvalidArgumentException;
use \RangeException;

class ItemKeywords extends ValueObject
{
    /**
     * キーワードのコレクション
     *
     * @access private
     * @var array
     */
    private $keywords;

    /**
     * コンストラクタ
     *
     * @access public
     * @param array $keywords
     * @return void
     */
    public function __construct(array $keywords)
    {
        $this->setKeywords($keywords);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return array
     */
    public function keywords(): array
    {
        return $this->keywords;
    }

    /**
     * セッター
     *
     * @access private
     * @param array $keywords
     * @return void
     * @throws RangeException
     */
    private function setKeywords(array $keywords): void
    {
        if ($keywords === []) {
            throw new RangeException('キーワードがありません.');
        }

        if (count($keywords) > 5) {
            throw new RangeException('キーワードが5個を超えています.');
        }

        $keywordObjects = [];

        foreach ($keywords as $keyword) {
            if (!is_string($keyword)) {
                throw new RangeException('不適切な型'. gettype($keyword). 'が含まれてます.');
            }

            $keywordObjects[] = Item::createKeyword($keyword);
        }

        $this->keywords = $keywordObjects;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $keywords
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $keywords): bool
    {
        if (!$keywords instanceof ItemKeywords) {
            throw new InvalidArgumentException(ItemKeywords::class.'ではない値オブジェクトです.');
        }

        if (count($this->keywords()) !== count($keywords->keywords())) {
            return false;
        }

        foreach ($this->keywords() as $index => $keyword) {
            if (!$keyword->equals($keywords[$index])) {
                return false;
            }
        }

        return true;
    }
}
