<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\ItemKeywords;
use \InvalidArgumentException;

class ItemNoKeywords extends ItemKeywords
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
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->keywords = [];
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
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $itemKeywords
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $itemKeywords): bool
    {
        if (!$itemKeywords instanceof ItemKeywords) {
            throw new InvalidArgumentException(ItemKeywords::class.'ではない値オブジェクトです.');
        }

        if ($itemKeywords instanceof ItemNoKeywords) {
            return true;
        }
        
        return false;
    }
}
