<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\PortAdapter\Persistence;

use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentity;
use DeepLots\UserInformation\DomainModel\UserIdentity\SystemUserId;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentityRepository;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentityFactory;
use App\User;
use \RangeException;

class EloquentUserIdentityRepository implements UserIdentityRepository
{
    /**
     * ユーザーアカウント情報の集約のファクトリ
     *
     * @access private
     * @var UserIdentityFactory
     */
    private $userIdentityFactory;

    /**
     * データベースのDATETIME型のフォーマット
     * 
     * @access private
     */
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * コンストラクタ
     *
     * @access public
     * @param UserIdentityFactory $userIdentityFactory
     * @return void
     */
    public function __construct(UserIdentityFactory $userIdentityFactory)
    {
        $this->setUserIdentityFactory($userIdentityFactory);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserIdentityFactory
     */
    public function userIdentityFactory(): UserIdentityFactory
    {
        return $this->userIdentityFactory;
    }

    /**
     * セッター
     *
     * @access private
     * @param UserIdentityFactory $userIdentityFactory
     * @return void
     */
    private function setUserIdentityFactory(UserIdentityFactory $userIdentityFactory): void
    {
        $this->userIdentityFactory = $userIdentityFactory;
    }

    /**
     * ユーザーアカウント情報の永続化
     *
     * @access public
     * @param UserIdentity $userIdentity
     * @return void
     */
    public function save(UserIdentity $userIdentity): void
    {
        $id = $userIdentity->id()->id();

        $existingUser = User::find($id);
        if ($existingUser === null) {
            throw new RangeException('ユーザーが存在しません.');
        }

        $publicId = $userIdentity->publicId()->id();
        $name = $userIdentity->name()->name();
        $email = $userIdentity->email()->email();
        $updatedAt = $userIdentity->updatedAt()->format(self::DATE_FORMAT);

        $existingUser->publicId = $publicId;
        $existingUser->name = $name;
        $existingUser->email = $email;
        $existingUser->updated_at = $updatedAt;
        $existingUser->save();
    }

    /**
     * ユーザーアカウント情報の取得
     *
     * @access public
     * @param SystemUserId $systemUserId
     * @return UserIdentity
     * @throws RangeException
     */
    public function userIdentityOfId(SystemUserId $systemUserId): UserIdentity
    {
        $id = $systemUserId->id();

        $user = User::find($id);
        if ($user === null) {
            throw new RangeException('ユーザーアカウント情報が存在しません.');
        }

        $createdAt = $user->created_at;
        $updatedAt = $user->updated_at;
        $deletedAt = $user->deleted_at;

        if ((int)$user->is_deleted === 0) {
            $publicId = $user->public_id;
            $name = $user->name;
            $email = $user->email;

            return $this->userIdentityFactory()
                        ->restoreUserIdentity(
                            $id,
                            $publicId,
                            $name,
                            $email,
                            $createdAt,
                            $updatedAt,
                            $deletedAt
                        );
        } elseif ((int)$user->is_deleted === 1) {
            return $this->userIdentityFactory()
                        ->restoreDeletedUserIdentity(
                            $id,
                            $createdAt,
                            $updatedAt,
                            $deletedAt
                        );
        }

        throw new UnexpectedValueException('削除判定できません.');
    }
}
