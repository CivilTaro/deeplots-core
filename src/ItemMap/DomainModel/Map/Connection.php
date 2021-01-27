<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\ItemId;
use \InvalidArgumentException;
use \RangeException;


class Connection extends ValueObject
{
    /**
     * 親のアイテムID
     *
     * @access private
     * @var ItemId
     */
    private $parent;

    /**
     * 子のアイテムID
     *
     * @access private
     * @var ItemId
     */
    private $child;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ItemId $parent
     * @param ItemId $child
     * @return void
     */
    public function __construct(
        ItemId $parent,
        ItemId $child
    )
    {
        $this->setParent($parent);
        $this->setChild($child);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemId
     */
    public function parent(): ItemId
    {
        return $this->parent;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemId
     */
    public function child(): ItemId
    {
        return $this->child;
    }

    /**
     * セッター
     *
     * @access private
     * @param ItemId $parent
     * @return void
     */
    private function setParent(ItemId $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * セッター
     *
     * @access private
     * @param ItemId $chid
     * @return void
     */
    private function setChild(ItemId $child): void
    {
        $this->child = $child;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $connection
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $connection): bool
    {
        if (!$connection instanceof Connection) {
            throw new InvalidArgumentException(Connection::class.'ではない値オブジェクトです.');
        }

        return $this->parent()->equals($connection->parent())
                && $this->child()->equals($connection->child());
    }
}
