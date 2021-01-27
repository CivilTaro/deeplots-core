<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\ItemId;

interface ItemRepository
{
    /**
     * アイテムの永続化
     *
     * @access public
     * @param Item $item
     * @return void
     */
    public function save(Item $item): void;

    /**
     * アイテムの削除
     *
     * @access public
     * @param Item $item
     * @return void
     */
    public function remove(Item $item): void;

    /**
     * アイテムの取得
     * 
     * アイテムのIDにより検索
     *
     * @access public
     * @param ItemId $id
     * @return Item
     */
    public function itemOfId(ItemId $id): Item;
}
