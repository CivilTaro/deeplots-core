<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\DomainModel\Map\MapNoTitle;
use DeepLots\ItemMap\DomainModel\Map\NoRelation;
use DeepLots\ItemMap\DomainModel\User\UserService;
use \RangeException;
use \DateTimeImmutable;
use \DateTimeZone;

class MapFactory
{
    /**
     * ドメインサービス
     *
     * @access private
     * @var UserService
     */
    private $userService;

    /**
     * コンストラクタ
     *
     * @access public
     * @param UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->setUserService($userService);
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
        return $this->userService;
    }

    /**
     * セッター
     *
     * @access private
     * @param UserService $userService
     * @return void
     */
    private function setUserService(UserService $userService): void
    {
        $this->userService = $userService;
    }

    /**
     * マップ集約の新規作成
     * 
     * @access public
     * @param string $title
     * @param array $relation
     * @param string $authorId
     * @return Map
     */
    public function newMap(
        string $title,
        array $relation,
        string $authorId
    ): Map
    {
        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        return $map = new Map(
            Map::createId(),
            Map::createTitle($title),
            Map::createRelation($relation),
            $this->userService()->authorFrom($authorId),
            $createdAt,
            $createdAt,
            $createdAt,
            false
        );
    }

    /**
     * マップ集約の生成
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param array $relation
     * @param string $authorId
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $deletedAt
     * @return Map
     */
    public function restoreMap(
        string $id,
        string $title,
        array $relation,
        string $authorId,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): Map
    {
        return new Map(
            Map::createId($id),
            Map::createTitle($title),
            Map::createRelation($relation),
            $this->userService()->authorFrom($authorId),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            false
        );
    }

    /**
     * 削除されたマップ集約の生成
     *
     * @access public
     * @param string $id
     * @param string $authorId
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $deletedAt
     * @return Map
     */
    public function restoreDeletedMap(
        string $id,
        string $authorId,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): Map
    {
        return new Map(
            Map::createId($id),
            new MapNoTitle(),
            new NoRelation(),
            $this->userService()->authorFrom($authorId),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            true
        );
    }
}
