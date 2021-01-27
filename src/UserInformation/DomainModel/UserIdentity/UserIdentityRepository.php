<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\DomainModel\UserIdentity;

use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentity;
use DeepLots\UserInformation\DomainModel\UserIdentity\SystemUserId;

interface UserIdentityRepository
{
    /**
     * ユーザーアカウント情報の永続化
     *
     * @access public
     * @param UserIdentity $userIdentity
     * @return void
     */
    public function save(UserIdentity $userIdentity): void;

    /**
     * ユーザーアカウント情報の取得
     *
     * ユーザーのシステムIDにより検索
     * 
     * @access public
     * @param SystemUserId $id
     * @return UserIdentity
     */
    public function userIdentityOfId(SystemUserId $id): UserIdentity;
}
