<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\DomainModel\ValidationNotificationHandler;
use DeepLots\ItemMap\DomainModel\Item\ItemId;
use DeepLots\ItemMap\DomainModel\Map\MapId;
use DeepLots\ItemMap\DomainModel\Map\MapTitle;
use DeepLots\ItemMap\DomainModel\Map\Relation;
use DeepLots\ItemMap\DomainModel\Map\Connection;
use DeepLots\ItemMap\DomainModel\Map\MapValidator;
use DeepLots\ItemMap\DomainModel\User\Author;
use DeepLots\ItemMap\DomainModel\User\Operator;
use \DateTimeImmutable;
use \RangeException;

class Map extends Entity
{
    /**
     * マップのID
     *
     * @access private
     * @var MapId
     */
    private $id;

    /**
     * マップのタイトル
     *
     * @access private
     * @var MapTitle
     */
    private $title;

    /**
     * マップを作成したユーザー
     *
     * @access private
     * @var Author
     */
    private $author;

    /**
     * アイテムの関係
     *
     * @access private
     * @var Relation
     */
    private $relation;

    /**
     * コンストラクタ
     *
     * @access public
     * @param MapId $id
     * @param MapTitle $title
     * @param Relation $relation
     * @param Author $author
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        MapId $id,
        MapTitle $title,
        Relation $relation,
        Author $author,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $deletedAt,
        bool $isDeleted
    )
    {
        parent::__construct(
            $createdAt,
            $updatedAt,
            $deletedAt,
            $isDeleted
        );

        $this->setId($id);
        $this->setTitle($title);
        $this->setRelation($relation);
        $this->setAuthor($author);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return MapId
     */
    public function id(): MapId
    {
        return $this->id;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return MapTitle
     */
    public function title(): MapTitle
    {
        return $this->title;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return Author
     */
    public function author(): Author
    {
        return $this->author;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return Relation
     */
    public function relation(): Relation
    {
        return $this->relation;
    }

    /**
     * セッター
     *
     * @access private
     * @param MapId $id
     * @return void
     */
    private function setId(MapId $id): void
    {
        $this->id = $id;
    }

    /**
     * セッター
     *
     * @access private
     * @param MapTitle $title
     * @return void
     */
    private function setTitle(MapTitle $title): void
    {
        $this->title = $title;
    }

    /**
     * セッター
     *
     * @access private
     * @param Author $author
     * @return void
     * @throws RangeException
     */
    private function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    /**
     * セッター
     *
     * @access private
     * @param Relation $relation
     * @return void
     */
    private function setRelation(Relation $relation): void
    {
        $this->relation = $relation;
    }

    /**
     * マップのタイトルを更新する.
     *
     * @access public
     * @param MapTitle $title
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editTitle(MapTitle $title, Operator $operator): void
    {
        if (!$this->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');      
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているマップです.');
        }

        $this->setTitle($title);
        $this->updateDate();
    }

    /**
     * アイテムの関係を更新する.
     *
     * @access public
     * @param Relation $relation
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editRelation(Relation $relation, Operator $operator): void
    {
        if (!$this->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');      
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているマップです.');
        }

        $this->setRelation($relation);
        $this->updateDate();
    }

    /**
     * ファクトリ
     * 
     * 引数を与えない場合は,自動でIDがセットされる.
     *
     * @access public
     * @param string $id
     * @return MapId
     */
    public static function createId(string $id = null): MapId
    {
        if ($id === null) {
            return new MapId();
        }
        
        return new MapId($id);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $title
     * @return MapTitle
     */
    public static function createTitle(string $title): MapTitle
    {
        return new MapTitle($title);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param array $relation
     * @return Relation
     */
    public static function createRelation(array $relation): Relation
    {
        return new Relation($relation);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param ItemId $parent
     * @param ItemId $child
     * @return Connection
     */
    public static function createConnection(
        ItemId $parent,
        ItemId $child
    ): Connection
    {
        return new Connection($parent, $child);
    }

    /**
     * バリデーション
     *
     * @access public
     * @param ValidationNotificationHandler $handler
     * @return void
     */
    public function validate(ValidationNotificationHandler $handler): void
    {
        $validator = new MapValidator($handler);
        $validator->validate();
    }

    /**
     * 与えられたエンティティがこのエンティティと等しいか判定する.
     *
     * @access public
     * @param Entity $item
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(Entity $map): bool
    {
        if (!$map instanceof Map) {
            throw new InvalidArgumentException(Map::class.'ではないエンティティです.');
        }

        return $this->id()->equals($map->id());
    }
}
