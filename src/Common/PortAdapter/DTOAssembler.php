<?php
declare(strict_types=1);

namespace DeepLots\Common\PortAdapter;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\PortAdapter\DTO;

interface DTOAssembler
{
    /**
     * DTOの組み立て
     *
     * @access public
     * @param Entity $entity
     * @return DTO
     */
    public function assemble(Entity $entity): DTO;
}
