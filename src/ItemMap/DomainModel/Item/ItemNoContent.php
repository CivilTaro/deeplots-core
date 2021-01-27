<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\ItemContent;
use \InvalidArgumentException;

class ItemNoContent extends ItemContent
{
    /**
     * コンテンツ
     *
     * @access private
     * @var string
     */
    private $content;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->content = '';
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $itemContent
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $itemContent): bool
    {
        if (!$itemContent instanceof ItemContent) {
            throw new InvalidArgumentException(ItemContent::class.'ではない値オブジェクトです.');
        }

        if ($itemContent instanceof ItemNoContent) {
            return true;
        }

        return false;
    }
}
