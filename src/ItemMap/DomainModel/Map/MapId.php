<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\Util\UUID;
use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\Common\DomainModel\UniqueId;
use \InvalidArgumentException;

class MapId extends UniqueId
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
     * @param ValueObject $mapId
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $mapId): bool
    {
        if (!$mapId instanceof MapId) {
            throw new InvalidArgumentException(MapId::class.'ではない値オブジェクトです.');
        }

        return $this->id() === $mapId->id();
    }
}
