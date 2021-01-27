<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\Application;

use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentity;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentityRepository;
use DeepLots\UserInformation\PortAdapter\DTO\UserIdentityDTO;
use DeepLots\UserInformation\PortAdapter\DTO\UserIdentityDTOAssembler;
use Illuminate\Support\Facades\DB;

class UserIdentityApplicationService
{
    /**
     * ユーザーアカウント情報のリポジトリ
     *
     * @access private
     * @var UserIdentityRepository
     */
    private $userIdentityRepository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param UserIdentityRepository $userIdentityRepository
     * @return void
     */
    public function __construct(
        UserIdentityRepository $userIdentityRepository
    )
    {
        $this->setUserIdentityRepository($userIdentityRepository);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserIdentityRepository
     */
    public function userIdentityRepository(): UserIdentityRepository
    {
        return $this->userIdentityRepository;
    }

    /**
     * セッター
     *
     * @access private
     * @param UserIdentityRepository $userIdentityRepository
     * @return void
     */
    private function setUserIdentityRepository(
        UserIdentityRepository $userIdentityRepository
    ): void
    {
        $this->userIdentityRepository = $userIdentityRepository;
    }

    /**
     * ユーザーアカウント情報の取得
     *
     * @access private
     * @param string $id
     * @return UersIdentity
     */
    private function userIdentityOfId(string $id): UserIdentity
    {
        $idObject = UserIdentity::createId($id);

        return $this->userIdentityRepository()
                    ->userIdentityOfId($idObject);
    }

    /**
     * ユーザーアカウント情報の一括保存
     *
     * @access private
     * @param UserIdentity $userIdentity
     * @return void
     */
    private function saveUserIdentity(UserIdentity $userIdentity): void
    {
        DB::transaction(function() use($userIdentity) {
            $this->userIdentityRepository()
                ->save($userIdentity);
        });
    }

    /**
     * ユーザーアカウント情報の一括更新
     *
     * @access public
     * @param string $id
     * @param string $publicId
     * @param string $name
     * @param string $email
     * @param string $operatorId
     * @return void
     */
    public function editAll(
        string $id,
        string $publicId,
        string $name,
        string $email,
        string $operatorId
    ): void
    {
        $userIdentity = $this->userIdentityOfId($id);

        $operator = UserIdentity::createOperator($operatorId);

        // 公開用IDの変更
        $publicIdObject = UserIdentity::createPublicId($publicId);
        $userIdentity->editPublicId($publicIdObject, $operator);

        // ユーザーネームを更新
        $nameObject = UserIdentity::createName($name);
        $userIdentity->editName($nameObject, $operator);

        // メールアドレスを更新
        $emailObject = UserIdentity::createEmail($email);
        $userIdentity->editEmail($emailObject, $operator);

        $this->saveUserIdentity($userIdentity);
    }

    /**
     * ユーザーアカウント情報のDTOを生成
     *
     * @access public
     * @param string $id
     * @return UserIdentityDTO
     */
    public function userIdentityDTO(string $id): UserIdentityDTO
    {
        $userIdentity = $this->userIdentityOfId($id);

        return $userIdentity->toDTO(new UserIdentityDTOAssembler());
    }
}
