<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\DTO;

use DeepLots\Common\PortAdapter\DTO;
use \DateTimeImmutable;
use \DateTimeZone;

class MapDTO implements DTO
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
     * マップのID
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * マップのタイトル
     *
     * @access private
     * @var string
     */
    private $title;

    /**
     * アイテムの関係
     *
     * @access private
     * @var array
     */
    private $relation;

    /**
     * マップを作成したユーザーのID
     *
     * @access private
     * @var string
     */
    private $authorId;

    /**
     * マップを作成した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * マップを更新した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * マップを削除した日時
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
     * @param array $relation
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
        array $relation,
        string $authorId,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $deletedAt,
        bool $isDeleted
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->relation = $relation;
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

    public function relation(): array
    {
        return $this->relation;
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
        $map = [];
        $map['id'] = $this->id();
        $map['title'] = $this->title();
        $map['relation'] = $this->relation();
        $map['authorId'] = $this->authorId();
        $map['createdAt'] = $this->createdAt()->format(self::DATE_FORMAT);
        $map['updatedAt'] = $this->updatedAt()->format(self::DATE_FORMAT);
        $map['deletedAt'] = $this->deletedAt()->format(self::DATE_FORMAT);
        $map['isDeleted'] = $this->isDeleted();

        return $map;
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
