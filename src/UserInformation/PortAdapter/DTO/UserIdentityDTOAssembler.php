<?php
declare(strict_types=1);

namespace DeepLots\UserInformation\PortAdapter\DTO;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\PortAdapter\DTO;
use DeepLots\Common\PortAdapter\DTOAssembler;
use DeepLots\UserInformation\DomainModel\UserIdentity\UserIdentity;
use DeepLots\UserInformation\PortAdapter\DTO\UserIdentityDTO;
use \InvalidArgumentException;

class UserIdentityDTOAssembler implements DTOAssembler
{
    /**
     * ユーザーアカウント情報のDTOの組み立て
     *
     * @access public
     * @param Entity $userIdentity
     * @return DTO
     * @throws InvalidArgumentException
     */
    public function assemble(Entity $userIdentity): DTO
    {
        if (!$userIdentity instanceof UserIdentity) {
            throw new InvalidArgumentException(UserIdentity::class.'ではないエンティティです.');
        }

        return new UserIdentityDTO(
            $userIdentity->id()->id(),
            $userIdentity->publicId()->id(),
            $userIdentity->name()->name(),
            $userIdentity->email()->email(),
            $userIdentity->createdAt(),
            $userIdentity->updatedAt(),
            $userIdentity->deletedAt(),
            $userIdentity->isDeleted()
        );
    }
}
