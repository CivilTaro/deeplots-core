<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\DTO;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\PortAdapter\DTO;
use DeepLots\Common\PortAdapter\DTOAssembler;
use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\PortAdapter\DTO\MapDTO;
use \InvalidArgumentException;

class MapDTOAssembler implements DTOAssembler
{
    /**
     * マップのDTOの組み立て
     *
     * @access public
     * @param Entity $map
     * @return DTO
     * @throws InvalidArgumentException
     */
    public function assemble(Entity $map): DTO
    {
        if (!$map instanceof Map) {
            throw new InvalidArgumentException(Map::class.'ではないエンティティです.');
        }

        if (!$map->isDeleted()) {
            $relation = [];
            foreach ($map->relation()->relation() as $index => $connection) {
                $relation[$index]['parent'] = $connection->parent()->id();
                $relation[$index]['child'] = $connection->child()->id();
            }
    
            return new MapDTO(
                $map->id()->id(),
                $map->title()->title(),
                $relation,
                $map->author()->id()->id(),
                $map->createdAt(),
                $map->updatedAt(),
                $map->deletedAt(),
                $map->isDeleted()
            );
        } else {
            return new MapDTO(
                $map->id()->id(),
                $map->title()->title(),
                $map->relation()->relation(),
                $map->author()->id()->id(),
                $map->createdAt(),
                $map->updatedAt(),
                $map->deletedAt(),
                $map->isDeleted()
            );
        }
    }
}
