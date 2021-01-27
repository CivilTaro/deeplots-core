<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\ItemNoTitle;
use DeepLots\ItemMap\DomainModel\Item\ItemNoKeywords;
use DeepLots\ItemMap\DomainModel\Item\ItemNoContent;
use DeepLots\ItemMap\DomainModel\User\UserService;
use \RangeException;
use \DateTimeImmutable;
use \DateTimeZone;

class ItemFactory
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
     * アイテム集約の新規作成
     *
     * @access public
     * @param string $title
     * @param array $keywords
     * @param string $content
     * @param string $authorId
     * @return Item
     */
    public function newItem(
        string $title,
        array $keywords,
        string $content,
        string $authorId
    ): Item
    {
        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        return new Item(
            Item::createId(),
            Item::createTitle($title),
            Item::createKeywords($keywords),
            Item::createContent($content),
            $this->userService()->authorFrom($authorId),
            $createdAt,
            $createdAt,
            $createdAt,
            false
        );
    }

    /**
     * アイテム集約の生成
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param array $keywords
     * @param string $content
     * @param string $authorId
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $deletedAt
     * @return Item
     */
    public function restoreItem(
        string $id,
        string $title,
        array $keywords,
        string $content,
        string $authorId,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): Item
    {
        return new Item(
            Item::createId($id),
            Item::createTitle($title),
            Item::createKeywords($keywords),
            Item::createContent($content),
            $this->userService()->authorFrom($authorId),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            false
        );
    }

    /**
     * 削除されたアイテム集約の生成
     *
     * @access public
     * @param string $id
     * @param string $authorId
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $deletedAt
     * @return Item
     */
    public function restoreDeletedItem(
        string $id,
        string $authorId,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): Item
    {
        return new Item(
            Item::createId($id),
            new ItemNoTitle(),
            new ItemNoKeywords(),
            new ItemNoContent(),
            $this->userService()->authorFrom($authorId),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            true
        );
    }
}
