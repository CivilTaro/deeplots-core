<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\DomainModel\ValidationNotificationHandler;
use DeepLots\ItemMap\DomainModel\Item\ItemId;
use DeepLots\ItemMap\DomainModel\Item\ItemTitle;
use DeepLots\ItemMap\DomainModel\Item\ItemContent;
use DeepLots\ItemMap\DomainModel\Item\ItemKeyword;
use DeepLots\ItemMap\DomainModel\Item\ItemKeywords;
use DeepLots\ItemMap\DomainModel\Item\ItemValidator;
use DeepLots\ItemMap\DomainModel\User\Author;
use DeepLots\ItemMap\DomainModel\User\Operator;
use \DateTimeImmutable;
use \RangeException;

class Item extends Entity
{
    /**
     * アイテムのID
     *
     * @access private
     * @var ItemId
     */
    private $id;

    /**
     * アイテムのタイトル
     *
     * @access private
     * @var ItemTitle
     */
    private $title;
    
    /**
     * アイテムのキーワード
     *
     * @access private
     * @var ItemKeywords
     */
    private $keywords;

    /**
     * アイテムのコンテンツ
     *
     * @access private
     * @var ItemContent
     */
    private $content;

    /**
     * アイテムを作成したユーザー
     *
     * @access private
     * @var Author
     */
    private $author;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ItemId $id
     * @param ItemTitle $title
     * @param ItemKeywords $keywords
     * @param ItemContent $content
     * @param Author $author
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        ItemId $id,
        ItemTitle $title,
        ItemKeywords $keywords,
        ItemContent $content,
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
        $this->setKeywords($keywords);
        $this->setContent($content);
        $this->setAuthor($author);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemId
     */
    public function id(): ItemId
    {
        return $this->id;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemTitle
     */
    public function title(): ItemTitle
    {
        return $this->title;
    }
    
    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemKeywords
     */
    public function keywords(): ItemKeywords
    {
        return $this->keywords;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemContent
     */
    public function content(): ItemContent
    {
        return $this->content;
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
     * セッター
     *
     * @access private
     * @param ItemId $id
     * @return void
     */
    private function setId(ItemId $id): void
    {
        $this->id = $id;
    }

    /**
     * セッター
     *
     * @access private
     * @param ItemTitle $title
     * @return void
     */
    private function setTitle(ItemTitle $title): void
    {
        $this->title = $title;
    }
    
    /**
     * セッター
     *
     * @access private
     * @param ItemKeywords $keyowrds
     * @return void
     */
    private function setKeywords(ItemKeywords $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * セッター
     *
     * @access private
     * @param ItemContent $content
     * @return void
     */
    private function setContent(ItemContent $content): void
    {
        $this->content = $content;
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
     * アイテムのタイトルを更新する.
     *
     * @access public
     * @param ItemTitle $title
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editTitle(ItemTitle $title, Operator $operator): void
    {
        if (!$this->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');      
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているアイテムです.');
        }

        $this->setTitle($title);
        $this->updateDate();
    }

    /**
     * アイテムのコンテンツを更新する.
     *
     * @access public
     * @param ItemContent $content
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editContent(ItemContent $content, Operator $operator): void
    {
        if (!$this->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');      
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているアイテムです.');
        }

        $this->setContent($content);
        $this->updateDate();
    }

    /**
     * アイテムのキーワードを更新する.
     *
     * @access public
     * @param ItemKeywords $keywords
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editKeywords(ItemKeywords $keywords, Operator $operator): void
    {
        if (!$this->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');      
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているアイテムです.');
        }

        $this->setKeywords($keywords);
        $this->updateDate();
    }

    /**
     * ファクトリ
     * 
     * 引数を与えない場合は,自動でIDがセットされる.
     *
     * @access public
     * @param string $id
     * @return ItemId
     */
    public static function createId(string $id = null): ItemId
    {
        if ($id === null) {
            return new ItemId();
        }

        return new ItemId($id);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $title
     * @return ItemTitle
     */
    public static function createTitle(string $title): ItemTitle
    {
        return new ItemTitle($title);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $content
     * @return ItemContent
     */
    public static function createContent(string $content): ItemContent
    {
        return new ItemContent($content);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $keyword
     * @return ItemKeyword
     */
    public static function createKeyword(string $keyword): ItemKeyword
    {
        return new ItemKeyword($keyword);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param array $keywords
     * @return ItemKeywords
     * @throws RangeException
     */
    public static function createKeywords(array $keywords): ItemKeywords
    {
        return new ItemKeywords($keywords);
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
        $validator = new ItemValidator($handler);
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
    public function equals(Entity $item): bool
    {
        if (!$item instanceof Entity) {
            throw new InvalidArgumentException(Item::class.'ではないエンティティです.');
        }

        return $this->id()->equals($item->id());
    }
}
