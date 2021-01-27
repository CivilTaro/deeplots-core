<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentity;
use DeepLots\UserInformation\DomainModel\UserIdentity\NoPublicUserId;
use DeepLots\UserInformation\DomainModel\UserIdentity\NoUserName;
use DeepLots\UserInformation\DomainModel\UserIdentity\NoEmail;
use \RangeException;
use \DateTimeImmutable;
use \DateTimeZone;

class UserIdentityFactory
{
    /**
     * ユーザーアカウント情報の集約の生成
     *
     * @access public
     * @param string $id
     * @param string $publicId
     * @param string $name
     * @param string $email
     * @param string $createdAt
     * @param string $updatedAt
     * @return UserIdentity
     */
    public function restoreUserIdentity(
        string $id,
        string $publicId,
        ?string $name,
        string $email,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): UserIdentity
    {
        return new UserIdentity(
            UserIdentity::createId($id),
            UserIdentity::createPublicId($publicId),
            $name === null ? new NoUserName() : UserIdentity::createName($name),
            UserIdentity::createEmail($email),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            false
        );
    }

    /**
     * 削除されたユーザーアカウント情報の集約の生成
     *
     * @access public
     * @param string $id
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $deletedAt
     * @return UserIdentity
     */
    public function restoreDeletedUserIdentity(
        string $id,
        string $createdAt,
        string $updatedAt,
        string $deletedAt
    ): UserIdentity
    {
        return new UserIdentity(
            UserIdentity::createId($id),
            new NoPublicUserId(),
            new NoUserName(),
            new NoEmail(),
            new DateTimeImmutable($createdAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($updatedAt, new DateTimeZone('UTC')),
            new DateTimeImmutable($deletedAt, new DateTimeZone('UTC')),
            true
        );
    }
}
