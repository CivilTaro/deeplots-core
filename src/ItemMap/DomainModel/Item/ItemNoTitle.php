<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\ItemTitle;
use \InvalidArgumentException;

class ItemNoTitle extends ItemTitle
{
    /**
     * タイトル
     *
     * @access private
     * @var string
     */
    private $title;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->title = '';
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $itemTitle
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $itemTitle): bool
    {
        if (!$itemTitle instanceof ItemTitle) {
            throw new InvalidArgumentException(ItemTitle::class.'ではない値オブジェクトです.');
        }

        if ($itemTitle instanceof ItemNoTitle) {
            return true;
        }
        
        return false;
    }
}
