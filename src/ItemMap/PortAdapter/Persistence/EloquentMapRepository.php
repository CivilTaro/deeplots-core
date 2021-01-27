<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\Persistence;

use DeepLots\Common\Util\UUID;
use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\DomainModel\Map\MapId;
use DeepLots\ItemMap\DomainModel\Map\MapRepository;
use DeepLots\ItemMap\DomainModel\Map\MapFactory;
use App\Map as MapModel;
use App\MapTitle as MapTitleModel;
use App\Relation as RelationModel;
use \DateTimeImmutable;
use \DateTimeZone;
use \UnexpectedValueException;

class EloquentMapRepository implements MapRepository
{
    /**
     * マップ集約のファクトリ
     *
     * @access private
     * @var MapFactory
     */
    private $mapFactory;

    /**
     * データベースのDATETIME型のフォーマット
     * 
     * @access private
     */
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(MapFactory $mapFactory)
    {
        $this->setMapFactory($mapFactory);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return MapFactory
     */
    public function mapFactory(): MapFactory
    {
        return $this->mapFactory;
    }

    /**
     * セッター
     *
     * @access private
     * @param MapFactory $mapFactory
     * @return void
     */
    private function setMapFactory(MapFactory $mapFactory): void
    {
        $this->mapFactory = $mapFactory;
    }

    /**
     * マップの挿入
     *
     * @access private
     * @param Map $map
     * @return void
     */
    private function insert(Map $map): void
    {
        $id = $map->id()->id();
        $title = $map->title()->title();
        $authorId = $map->author()->id()->id();
        $relation = $map->relation();
        $createdAt = $map->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $map->updatedAt()->format(self::DATE_FORMAT);

        $mapModel = new MapModel();
        $mapModel->id = $id;
        $mapModel->author_id = $authorId;
        $mapModel->created_at = $createdAt;
        $mapModel->updated_at = $updatedAt;
        // 削除された日時のデフォルト値は作成した日時
        $mapModel->deleted_at = $createdAt;
        $mapModel->is_deleted = 0;
        $mapModel->save();

        $mapTitleModel = new MapTitleModel();
        $mapTitleModel->id = preg_replace('/-/u', '', UUID::v4());
        $mapTitleModel->title = $title;
        $mapTitleModel->map_id = $id;
        $mapTitleModel->save();

        foreach ($relation->relation() as $connection) {
            $relationModel = new RelationModel();
            $relationModel->id = preg_replace('/-/u', '', UUID::v4());
            $relationModel->parent = $connection->parent()->id();
            $relationModel->child = $connection->child()->id();
            $relationModel->map_id = $id;
            $relationModel->save();
        }
    }

    /**
     * マップの論理削除
     *
     * @access private
     * @param Map $map
     * @return void
     */
    private function logicalDelete(Map $map): void
    {
        $id = $map->id()->id();
        $authorId = $map->author()->id()->id();
        $createdAt = $map->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $map->updatedAt()->format(self::DATE_FORMAT);
        $deletedAt = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                                        ->format(self::DATE_FORMAT);

        $mapModel = new MapModel();
        $mapModel->id = $id;
        $mapModel->author_id = $authorId;
        $mapModel->created_at = $createdAt;
        $mapModel->updated_at = $updatedAt;
        // 削除された日時のデフォルト値は作成した日時
        $mapModel->deleted_at = $deletedAt;
        $mapModel->is_deleted = 1;
        $mapModel->save();
    }

    /**
     * マップの永続化
     *
     * @access public
     * @param Map $map
     * @return void
     */
    public function save(Map $map): void
    {
        $existingMapModel = MapModel::find($map->id()->id());
        if ($existingMapModel !== null) {
            $existingMapModel->delete();
        }

        $this->insert($map);
    }

    /**
     * マップの削除
     *
     * @access public
     * @param Map $map
     * @return void
     * @throws UnexpectedValueException
     */
    public function remove(Map $map): void
    {
        $mapModel = MapModel::find($map->id()->id());
        if ($mapModel === null) {
            throw new UnexpectedValueException('マップが存在しません.');
        }

        $mapModel->delete();
        $this->logicalDelete($map);
    }

    /**
     * マップの取得
     *
     * @access public
     * @param MapId $mapId
     * @return Map
     * @throws RangeException
     * @throws UnexpectedValueException
     */
    public function mapOfId(MapId $mapId): Map
    {
        $id = $mapId->id();

        $mapModel = MapModel::find($id);
        if ($mapModel === null) {
            throw new RangeException('マップが存在しません.');
        }

        $authorId = $mapModel->author_id;
        $createdAt = $mapModel->created_at;
        $updatedAt = $mapModel->updated_at;
        $deletedAt = $mapModel->deleted_at;

        if ((int)$mapModel->is_deleted === 0) {
            $title = $mapModel->title->title;
        
            $relation = [];
            foreach ($mapModel->relation as $index => $elmOfRelation) {
                $relation[$index]['parent'] = $elmOfRelation->parent;
                $relation[$index]['child'] = $elmOfRelation->child;
            }

            return $this->mapFactory()
                        ->restoreMap(
                            $id,
                            $title,
                            $relation,
                            $authorId,
                            $createdAt,
                            $updatedAt,
                            $deletedAt
                        );
        } elseif ((int)$mapModel->is_deleted === 1) {
            return $this->mapFactory()
                        ->restoreDeletedMap(
                            $id,
                            $authorId,
                            $createdAt,
                            $updatedAt,
                            $deletedAt
                        );
        }

        throw new UnexpectedValueException('削除判定できません.');
    }
}
