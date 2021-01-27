<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\DomainModel\Map\MapId;

interface MapRepository
{
    /**
     * マップの永続化
     *
     * @access public
     * @param Map $map
     * @return void
     */
    public function save(Map $map): void;

    /**
     * マップの削除
     *
     * @access public
     * @param Map $map
     * @return void
     */
    public function remove(Map $map): void;

    /**
     * マップの取得
     * 
     * マップのIDにより検索
     *
     * @access public
     * @param MapId $mapId
     * @return Map
     */
    public function mapOfId(MapId $mapId): Map;
}
