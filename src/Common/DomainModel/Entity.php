<?php
declare(strict_types=1);

namespace DeepLots\Common\DomainModel;

use DeepLots\Common\DomainModel\ValidationNotificationHandler;
use DeepLots\Common\PortAdapter\DTO;
use DeepLots\Common\PortAdapter\DTOAssembler;
use \DateTimeImmutable;
use \DateTimeZone;
use \RangeException;

abstract class Entity
{
    /**
     * エンティティが生成された日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * エンティティが更新された日時
     *
     * @access private
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * エンティティが削除された日時
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
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param DateTimeImmutable $deletedAt
     * @param bool $isDeleted
     * @return void
     */
    public function __construct(
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $deletedAt,
        bool $isDeleted
    )
    {
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setDeletedAt($deletedAt);
        $this->setIsDeleted($isDeleted);
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
     * セッター
     *
     * @access private
     * @param DateTimeImmutable $createdAt
     * @return void
     */
    private function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * セッター
     *
     * @access private
     * @param DateTimeImmutable $updatedAt
     * @return void
     */
    private function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * セッター
     * 
     * @access private
     * @param DateTimeImmutable $deletedAt
     * @return void
     */
    private function setDeletedAt(DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * セッター
     *
     * @access private
     * @param boolean $isDeleted
     * @return void
     */
    private function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * 日時を更新する.
     *
     * @access protected
     * @param void
     * @return void
     */
    protected function updateDate(): void
    {
        $updatedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        $this->setUpdatedAt($updatedAt);
    }

    /**
     * DTOアセンブラ
     *
     * @access public
     * @param DTOAssembler $assembler
     * @return DTO
     */
    public function toDTO(DTOAssembler $assembler): DTO
    {
        return $assembler->assemble($this);
    }

    /**
     * バリデーション
     *
     * @access public
     * @param ValidationNotificationHandler $handler
     * @return void
     */
    abstract public function validate(ValidationNotificationHandler $handler): void;

    /**
     * 与えられたエンティティがこのエンティティと等しいか判定する.
     *
     * @access public
     * @param Entity $entity
     * @return boolean
     */
    abstract public function equals(Entity $entity): bool;
}
