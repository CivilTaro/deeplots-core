<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\DTO;

use DeepLots\Common\PortAdapter\DTO;
use \DateTimeImmutable;
use \DateTimeZone;

class ItemDTO implements DTO
{
    /**
     * DateTimeのレンダリング用フォーマット
     * 
     * @access private
     */
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * レンダリング用のタイムゾーン
     * 
     * @access private
     */
    private const TIMEZONE = 'Asia/Tokyo';

    /**
     * アイテムのID
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * アイテムのタイトル
     *
     * @access private
     * @var string
     */
    private $title;

    /**
     * アイテムのキーワード
     *
     * @access private
     * @var array
     */
    private $keywords;

    /**
     * アイテムのコンテンツ
     *
     * @access private
     * @var string
     */
    private $content;

    /**
     * アイテムを作成したユーザーのID
     *
     * @access private
     * @var string
     */
    private $authorId;

    /**
     * アイテムを作成した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * アイテムを更新した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * アイテムを削除した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $deletedAt;

    /**
     * 削除されているかのフラグ
     *
     * @access private
     * @var bool
     */
    private $isDeleted;

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param array $keywords
     * @param string $content
     * @param string $authorId
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        string $id,
        string $title,
        array $keywords,
        string $content,
        string $authorId,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $deletedAt,
        bool $isDeleted
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->keywords = $keywords;
        $this->content = preg_replace('/\n/u', PHP_EOL, $content);
        $this->authorId = $authorId;
        $this->createdAt = $createdAt->setTimezone(new DateTimeZone(self::TIMEZONE));
        $this->updatedAt = $updatedAt->setTimezone(new DateTimeZone(self::TIMEZONE));
        $this->deletedAt = $deletedAt->setTimezone(new DateTimeZone(self::TIMEZONE));
        $this->isDeleted = $isDeleted;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return array
     */
    public function keywords(): array
    {
        return $this->keywords;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function authorId(): string
    {
        return $this->authorId;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return DateTimeImmutable
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return DateTimeImmutable
     */
    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * ゲッター
     * 
     * @access public
     * @param void
     * @return DateTimeImmutable
     */
    public function deletedAt(): DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * 連想配列に変換する.
     *
     * @access public
     * @param void
     * @return array
     */
    public function toArray(): array
    {
        $item = [];
        $item['id'] = $this->id();
        $item['title'] = $this->title();
        $item['keywords'] = $this->keywords();
        $item['content'] = $this->content();
        $item['authorId'] = $this->authorId();
        $item['createdAt'] = $this->createdAt()->format(self::DATE_FORMAT);
        $item['updatedAt'] = $this->updatedAt()->format(self::DATE_FORMAT);
        $item['deletedAt'] = $this->deletedAt()->format(self::DATE_FORMAT);
        $item['isDeleted'] = $this->isDeleted();
        
        return $item;
    }

    /**
     * JSONフォーマットに変換する.
     *
     * @access public
     * @param void
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
