<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\Application;

use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\DomainModel\Map\MapRepository;
use DeepLots\ItemMap\DomainModel\Map\MapFactory;
use DeepLots\ItemMap\PortAdapter\DTO\MapDTO;
use DeepLots\ItemMap\PortAdapter\DTO\MapDTOAssembler;
use DeepLots\ItemMap\DomainModel\User\UserService;
use Illuminate\Support\Facades\DB;
use \RangeException;

class MapApplicationService
{
    /**
     * マップのリポジトリ
     *
     * @access private
     * @var MapRepository
     */
    private $mapRepository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MapRepository $mapRepository
     * @return void
     */
    public function __construct(MapRepository $mapRepository)
    {
        $this->setMapRepository($mapRepository);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return MapRepository
     */
    public function mapRepository(): MapRepository
    {
        return $this->mapRepository;
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
        return $this->mapRepository
                    ->mapFactory();
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserService
     */
    public function userService(): UserService
    {
        return $this->mapRepository
                    ->mapFactory()
                    ->userService();
    }

    /**
     * セッター
     *
     * @access private
     * @param MapRepository $mapRepository
     * @return void
     */
    private function setMapRepository(MapRepository $mapRepository): void
    {
        $this->mapRepository = $mapRepository;
    }

    /**
     * マップの新規作成
     *
     * @access public
     * @param string $title
     * @param array $relation
     * @param string $authorId
     * @return void
     */
    public function createNewMap(
        string $title,
        array $relation,
        string $authorId
    ): void
    {
        $map = $this->mapFactory()
                    ->newMap(
                        $title,
                        $relation,
                        $authorId
                    );

        $this->saveMap($map);
    }

    /**
     * マップの削除
     *
     * @access public
     * @param string $id
     * @param string $operatorId
     * @return void
     * @throws RangeException
     */
    public function removeMap(string $id, string $operatorId): void
    {
        $map = $this->mapOfId($id);

        $operator = $this->userService()->operatorFrom($operatorId);

        if (!$map->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');
        }

        DB::transaction(function() use($map) {
            $this->mapRepository()
                ->remove($map);
        });
    }

    /**
     * マップの取得
     * 
     * マップをIDにより検索
     *
     * @access private
     * @param string $id
     * @return Map
     */
    private function mapOfId(string $id): Map
    {
        $idObject = Map::createId($id);

        return $this->mapRepository()
                    ->mapOfId($idObject);
    }

    /**
     * マップの一括保存
     *
     * @access private
     * @param Map $map
     * @return void
     */
    private function saveMap(Map $map): void
    {
        DB::transaction(function() use($map) {
            $this->mapRepository()
                ->save($map);
        });
    }

    /**
     * マップの一括更新
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param array $relation
     * @param string $operatorId
     * @return void
     */
    public function editAll(
        string $id,
        string $title,
        array $relation,
        string $operatorId
    ): void
    {
        $map = $this->mapOfId($id);

        $operator = $this->userService()->operatorFrom($operatorId);

        // タイトルの更新
        $titleObject = Map::createTitle($title);
        $map->editTitle($titleObject, $operator);

        // アイテムの関係の更新
        $relationObject = Map::createRelation($relation);
        $map->editRelation($relationObject, $operator);

        $this->saveMap($map);
    }

    /**
     * マップのDTOを生成
     *
     * @access public
     * @param string $id
     * @return MapDTO
     */
    public function mapDTO(string $id): MapDTO
    {
        $map = $this->mapOfId($id);

        return $map->toDTO(new MapDTOAssembler());
    }
}
