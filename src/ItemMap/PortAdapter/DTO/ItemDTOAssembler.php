<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\DTO;

use DeepLots\Common\DomainModel\Entity;
use DeepLots\Common\PortAdapter\DTO;
use DeepLots\Common\PortAdapter\DTOAssembler;
use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\PortAdapter\DTO\ItemDTO;
use \InvalidArgumentException;

class ItemDTOAssembler implements DTOAssembler
{
    /**
     * アイテムのDTOの組み立て
     *
     * @access public
     * @param Entity $item
     * @return DTO
     * @throws InvalidArgumentException
     */
    public function assemble(Entity $item): DTO
    {
        if (!$item instanceof Item) {
            throw new InvalidArgumentException(Item::class.'ではないエンティティです.');
        }

        if (!$item->isDeleted()) {
            $keywords = [];
            foreach ($item->keywords()->keywords() as $keyword) {
                $keywords[] = $keyword->keyword();
            }
    
            return new ItemDTO(
                $item->id()->id(),
                $item->title()->title(),
                $keywords,
                $item->content()->content(),
                $item->author()->id()->id(),
                $item->createdAt(),
                $item->updatedAt(),
                $item->deletedAt(),
                $item->isDeleted()
            );
        } else {
            return new ItemDTO(
                $item->id()->id(),
                $item->title()->title(),
                $item->keywords()->keywords(),
                $item->content()->content(),
                $item->author()->id()->id(),
                $item->createdAt(),
                $item->updatedAt(),
                $item->deletedAt(),
                $item->isDeleted()
            );
        }
    }
}
