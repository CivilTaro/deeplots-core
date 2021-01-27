<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\DomainModel\ValidationNotificationHandler;
use DeepLots\UserInformation\DomainModel\UserIdentity\SystemUserId;
use DeepLots\UserInformation\DomainModel\UserIdentity\PublicUserId;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserName;
use DeepLots\UserInformation\DomainModel\UserIdentity\Email;
use DeepLots\UserInformation\DomainModel\UserIdentity\Operator;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentityValidator;
use \DateTimeImmutable;
use \RangeException;

class UserIdentity extends Entity
{
    /**
     * ユーザーのシステムID
     *
     * @access private
     * @var SystemUserId
     */
    private $id;

    /**
     * ログイン、公開用のID
     *
     * @access private
     * @var PublicUserId
     */
    private $publicId;

    /**
     * ユーザーネーム
     *
     * @access private
     * @var UserName
     */
    private $name;

    /**
     * メールアドレス
     *
     * @access private
     * @var Email
     */
    private $email;

    /**
     * コンストラクタ
     *
     * @access public
     * @param SystemUserId $id
     * @param PublicUserId $publicId
     * @param UserName $name
     * @param Email $email
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        SystemUserId $id,
        PublicUserId $publicId,
        UserName $name,
        Email $email,
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
        $this->setPublicId($publicId);
        $this->setName($name);
        $this->setEmail($email);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return SystemUserId
     */
    public function id(): SystemUserId
    {
        return $this->id;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return PublicUserId
     */
    public function publicId(): PublicUserId
    {
        return $this->publicId;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserName
     */
    public function name(): UserName
    {
        return $this->name;
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * セッター
     *
     * @access private
     * @param SystemUserId $id
     * @return void
     */
    private function setId(SystemUserId $id): void
    {
        $this->id = $id;
    }

    /**
     * セッター
     *
     * @access private
     * @param PublicUserId $publicId
     * @return void
     */
    private function setPublicId(PublicUserId $publicId): void
    {
        $this->publicId = $publicId;
    }

    /**
     * セッター
     *
     * @access private
     * @param UserName $name
     * @return void
     */
    private function setName(UserName $name): void
    {
        $this->name = $name;
    }

    /**
     * セッター
     *
     * @access private
     * @param Email $email
     * @return void
     */
    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * ログイン、公開用のIDを更新する.
     *
     * @access public
     * @param PublicUserId $publicId
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editPublicId(
        PublicUserId $publicId,
        Operator $operator
    ): void
    {
        if (!$this->id()->equals($operator->id())) {
            throw new RangeException('このユーザーと異なるユーザーによる操作です.');
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているユーザーです.');
        }

        $this->setPublicId($publicId);
        $this->updateDate();
    }

    /**
     * ユーザーネームを更新する.
     *
     * @access public
     * @param UserName $name
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editName(
        UserName $name,
        Operator $operator
    ): void
    {
        if (!$this->id()->equals($operator->id())) {
            throw new RangeException('このユーザーと異なるユーザーによる操作です.');
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているユーザーです.');
        }

        $this->setName($name);
        $this->updateDate();
    }

    /**
     * メールアドレスを更新する.
     *
     * @access public
     * @param Email $email
     * @param Operator $operator
     * @return void
     * @throws RangeException
     */
    public function editEmail(
        Email $email,
        Operator $operator
    ): void
    {
        if (!$this->id()->equals($operator->id())) {
            throw new RangeException('この作成したユーザーと異なるユーザーによる操作です.');
        }

        if ($this->isDeleted()) {
            throw new RangeException('削除されているユーザーです.');
        }

        $this->setEmail($email);
        $this->updateDate();
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $id
     * @return SystemUserId
     */
    public static function createId(string $id): SystemUserId
    {
        return new SystemUserId($id);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $publicId
     * @return PublicUserId
     */
    public static function createPublicId(string $publicId): PublicUserId
    {
        return new PublicUserId($publicId);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $name
     * @return UserName
     */
    public static function createName(string $name): UserName
    {
        return new UserName($name);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $email
     * @return Email
     */
    public static function createEmail(string $email): Email
    {
        return new Email($email);
    }

    /**
     * ファクトリ
     *
     * @access public
     * @param string $operatorId
     * @return Operator
     */
    public static function createOperator(string $operatorId): Operator
    {
        return new Operator(self::createId($operatorId));
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
        $validator = new UserIdentityValidator($handler);
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
    public function equals(Entity $userIdentity): bool
    {
        if (!$userIdentity instanceof UserIdentity) {
            throw new InvalidArgumentException(UserIdentity::class.'ではないエンティティです.');
        }

        return $this->id()->equals($userIdentity->id());
    }
}
