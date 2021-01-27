<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\Util\UUID;
use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\Common\DomainModel\UniqueId;
use \InvalidArgumentException;

class ItemId extends UniqueId
{
    /**
     * コンストラクタ
     * 
     * @access public
     * @param string $id
     * @return void
     */
    public function __construct(string $id = null)
    {
        if ($id === null) {
            parent::__construct(preg_replace('/-/u', '', UUID::v4()));
        } else {
            parent::__construct($id);
        }
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $itemId
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $itemId): bool
    {
        if (!$itemId instanceof ItemId) {
            throw new InvalidArgumentException(ItemId::class.'ではない値オブジェクトです.');
        }

        return $this->id() === $itemId->id();
    }
}
