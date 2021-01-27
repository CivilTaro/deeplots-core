<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\Persistence;

use DeepLots\Common\Util\UUID;
use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\ItemId;
use DeepLots\ItemMap\DomainModel\Item\ItemRepository;
use DeepLots\ItemMap\DomainModel\Item\ItemFactory;
use App\Item as ItemModel;
use App\ItemTitle as ItemTitleModel;
use App\ItemKeyword as ItemKeywordModel;
use App\ItemContent as ItemContentModel;
use \DateTimeImmutable;
use \DateTimeZone;
use \UnexpectedValueException;

class EloquentItemRepository implements ItemRepository
{
    /**
     * アイテム集約のファクトリ
     *
     * @access private
     * @var ItemFactory
     */
    private $itemFactory;

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
     * @param ItemService $itemService
     * @param ItemFactory $itemFactory
     * @return void
     */
    public function __construct(ItemFactory $itemFactory)
    {
        $this->setItemFactory($itemFactory);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemFactory
     */
    public function itemFactory(): ItemFactory
    {
        return $this->itemFactory;
    }
    
    /**
     * セッター
     *
     * @access private
     * @param ItemFactory $itemFactory
     * @return void
     */
    private function setItemFactory(ItemFactory $itemFactory): void
    {
        $this->itemFactory = $itemFactory;
    }

    /**
     * アイテムの挿入
     *
     * @access private
     * @param Item $item
     * @return void
     */
    private function insert(Item $item): void
    {
        $id = $item->id()->id();
        $title = $item->title()->title();
        $content = $item->content()->content();
        $authorId = $item->author()->id()->id();
        $keywords = $item->keywords();
        $createdAt = $item->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $item->updatedAt()->format(self::DATE_FORMAT);

        $itemModel = new ItemModel();
        $itemModel->id = $id;
        $itemModel->author_id = $authorId;
        $itemModel->created_at = $createdAt;
        $itemModel->updated_at = $updatedAt;
        // 削除された日時のデフォルト値は作成した日時
        $itemModel->deleted_at = $createdAt;
        $itemModel->is_deleted = 0;
        $itemModel->save();

        $itemTitleModel = new ItemTitleModel();
        $itemTitleModel->id = preg_replace('/-/u', '', UUID::v4());
        $itemTitleModel->title = $title;
        $itemTitleModel->item_id = $id;
        $itemTitleModel->save();

        $itemContentModel = new ItemContentModel();
        $itemContentModel->id = preg_replace('/-/u', '', UUID::v4());
        $itemContentModel->content = $content;
        $itemContentModel->item_id = $id;
        $itemContentModel->save();

        foreach ($keywords->keywords() as $keyword) {
            $itemKeywordModel = new ItemKeywordModel();
            $itemKeywordModel->id = preg_replace('/-/u', '', UUID::v4());
            $itemKeywordModel->keyword = $keyword->keyword();
            $itemKeywordModel->item_id = $id;
            $itemKeywordModel->save();
        }
    }

    /**
     * アイテムの論理削除
     *
     * @access private
     * @param Item $item
     * @return void
     */
    private function logicalDelete(Item $item): void
    {
        $id = $item->id()->id();
        $authorId = $item->author()->id()->id();
        $createdAt = $item->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $item->updatedAt()->format(self::DATE_FORMAT);
        $deletedAt = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                                        ->format(self::DATE_FORMAT);


        $itemModel = new ItemModel();
        $itemModel->id = $id;
        $itemModel->author_id = $authorId;
        $itemModel->created_at = $createdAt;
        $itemModel->updated_at = $updatedAt;
        $itemModel->deleted_at = $deletedAt;
        $itemModel->is_deleted = 1;
        $itemModel->save();
    }

    /**
     * アイテムの永続化
     *
     * @access public
     * @param Item $item
     * @return void
     */
    public function save(Item $item): void
    {
        $existingItemModel = ItemModel::find($item->id()->id());
        if ($existingItemModel !== null) {
            $existingItemModel->delete();
        }

        $this->insert($item);
    }

    /**
     * アイテムの削除
     *
     * @access public
     * @param Item $item
     * @return void
     * @throws UnexpectedValueException
     */
    public function remove(Item $item): void
    {
        $itemModel = ItemModel::find($item->id()->id());
        if ($itemModel === null) {
            throw new UnexpectedValueException('アイテムが存在しません.');
        }

        $itemModel->delete();
        $this->logicalDelete($item);
    }

    /**
     * アイテムの取得
     * 
     * アイテムのIDにより検索
     *
     * @access public
     * @param ItemId $id
     * @return Item
     * @throws UnexpectedValueException
     */ 
    public function itemOfId(ItemId $itemId): Item
    {
        $id = $itemId->id();

        $itemModel = ItemModel::find($id);
        if ($itemModel === null) {
            throw new UnexpectedValueException('アイテムが存在しません.');
        }
        
        $authorId = $itemModel->author_id;
        $createdAt = $itemModel->created_at;
        $updatedAt = $itemModel->updated_at;
        $deletedAt = $itemModel->deleted_at;

        if ((int)$itemModel->is_deleted === 0) {
            $title = $itemModel->title->title;
            $content = $itemModel->content->content;
            
            $keywords = [];
            foreach ($itemModel->keywords as $keywordModel) {
                $keywords[] = $keywordModel->keyword;
            }

            return $this->itemFactory()
                        ->restoreItem(
                            $id,
                            $title,
                            $keywords,
                            $content,
                            $authorId,
                            $createdAt,
                            $updatedAt,
                            $deletedAt
                        );
        } elseif ((int)$itemModel->is_deleted === 1) {
            return $this->itemFactory()
                        ->restoreDeletedItem(
                            $id,
                            $authorId,
                            $createdAt,
                            $updatedAt,
                            $deletedAt,
                        );
        }

        throw new UnexpectedValueException('削除判定できません.');
    }
}
