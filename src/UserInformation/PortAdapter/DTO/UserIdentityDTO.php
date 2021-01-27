<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\PortAdapter\DTO;

use DeepLots\Common\PortAdapter\DTO;
use \DateTimeImmutable;
use \DateTimeZone;

class UserIdentityDTO implements DTO
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
     * ユーザーのシステムID
     *
     * @access private
     * @var string
     */
    private $id;

    /**
     * ログイン、公開用のID
     *
     * @access private
     * @var string
     */
    private $publicId;

    /**
     * ユーザネーム
     *
     * @access private
     * @var string
     */
    private $name;

    /**
     * メールアドレス
     *
     * @access private
     * @var string
     */
    private $email;

    /**
     * ユーザーを作成した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * ユーザーアカウント情報を更新した日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * ユーザーアカウントが削除された日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $deletedAt;

    /**
     * 削除されているかのフラグ
     *
     * @access public
     * @var bool
     */
    private $isDeleted;

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $id
     * @param string $publicId
     * @param string $name
     * @param string $email
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        string $id,
        string $publicId,
        string $name,
        string $email,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $deletedAt,
        bool $isDeleted
    )
    {
        $this->id = $id;
        $this->publicId = $publicId;
        $this->name = $name;
        $this->email = $email;
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
    public function publicId(): string
    {
        return $this->publicId;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function email(): string
    {
        return $this->email;
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
        $userIdentity = [];
        $userIdentity['id'] = $this->id();
        $userIdentity['publicId'] = $this->publicId();
        $userIdentity['name'] = $this->name();
        $userIdentity['email'] = $this->email();
        $userIdentity['createdAt'] = $this->createdAt()->format(self::DATE_FORMAT);
        $userIdentity['updatedAt'] = $this->updatedAt()->format(self::DATE_FORMAT);
        $userIdentity['deletedAt'] = $this->deletedAt()->format(self::DATE_FORMAT);
        $userIdentity['isDeleted'] = $this->isDeleted();

        return $userIdentity;
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
