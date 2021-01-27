<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\Application;

use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Item\ItemRepository;
use DeepLots\ItemMap\DomainModel\Item\ItemFactory;
use DeepLots\ItemMap\PortAdapter\DTO\ItemDTO;
use DeepLots\ItemMap\PortAdapter\DTO\ItemDTOAssembler;
use DeepLots\ItemMap\DomainModel\User\UserService;
use Illuminate\Support\Facades\DB;
use \RangeException;

class ItemApplicationService
{
    /**
     * アイテムのリポジトリ
     *
     * @access private
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ItemRepository $itemRepository
     * @return void
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->setItemRepository($itemRepository);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ItemRepository
     */
    public function itemRepository(): ItemRepository
    {
        return $this->itemRepository;
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
        return $this->itemRepository
                    ->itemFactory();
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return UserService
     */
    public function userService(): UserService
    {
        return $this->itemRepository
                    ->itemFactory()
                    ->userService();
    }

    /**
     * セッター
     *
     * @access private
     * @param ItemRepository $itemRepository
     * @return void
     */
    private function setItemRepository(ItemRepository $itemRepository): void
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * アイテムの新規作成
     *
     * @access public
     * @param string $title
     * @param array $keywords
     * @param string $content
     * @param string $authorId
     * @return void
     */
    public function createNewItem(
        string $title,
        array $keywords,
        string $content,
        string $authorId
    ): void
    {
        $item = $this->itemFactory()
                    ->newItem(
                        $title,
                        $keywords,
                        $content,
                        $authorId
                    );

        $this->saveItem($item);
    }

    /**
     * アイテムの削除
     *
     * @access public
     * @param string $id
     * @param string $operatorId
     * @return void
     * @throws RangeException
     */
    public function removeItem(string $id, string $operatorId): void
    {
        $item = $this->itemOfId($id);

        $operator = $this->userService()->operatorFrom($operatorId);

        if (!$item->author()->id()->equals($operator->id())) {
            throw new RangeException('アイテムを作成したユーザーと異なるユーザーによる操作です.');
        }

        DB::transaction(function() use($item) {
            $this->itemRepository()
                ->remove($item);
        });
    }

    /**
     * アイテムの取得
     *
     * アイテムをIDにより検索
     * 
     * @access private
     * @param string $id
     * @return Item
     */
    private function itemOfId(string $id): Item
    {
        $idObject = Item::createId($id);

        return $this->itemRepository()
                    ->itemOfId($idObject);
    }

    /**
     * アイテムの一括保存
     *
     * @access private
     * @param Item $item
     * @return void
     */
    private function saveItem(Item $item): void
    {
        DB::transaction(function() use($item) {
            $this->itemRepository()
                ->save($item);
        });
    }

    /**
     * アイテムの一括更新
     *
     * @access public
     * @param string $id
     * @param string $title
     * @param array $keywords
     * @param string $content
     * @param string $operatorId
     * @return void
     */
    public function editAll(
        string $id,
        string $title,
        array $keywords,
        string $content,
        string $operatorId
    ): void
    {
        $item = $this->itemOfId($id);

        $operator = $this->userService()->operatorFrom($operatorId);

        // タイトルの更新
        $titleObject = Item::createTitle($title);
        $item->editTitle($titleObject, $operator);

        // キーワードの更新
        $keywordsObject = Item::createKeywords($keywords);
        $item->editKeywords($keywordsObject, $operator);

        // コンテンツの更新
        $contentObject = Item::createContent($content);
        $item->editContent($contentObject, $operator);

        $this->saveItem($item);
    }

    /**
     * アイテムのDTOを生成
     *
     * @access public
     * @param string $id
     * @return ItemDTO
     */
    public function itemDTO(string $id): ItemDTO
    {
        $item = $this->itemOfId($id);

        return $item->toDTO(new ItemDTOAssembler());
    }

    /**
     * リクエストからキーワードの取り出し
     * 
     * @access public
     * @param string $rawData
     * @return array
     * @throws RangeException
     */
    public function pickUpKeywords(string $rawData): array
    {
        $keywords = explode(',', $rawData);
        foreach ($keywords as $keyword) {
            //\\x0-\x1F\x7F:ASCIIコードで制御文字
            if (preg_match("/[\\x0-\x1F\x7F]/u", $keyword) === 1) {
                throw new RangeException('不適切な文字が含まれています.');
            }

            //\x20:ASCIIコードで半角スペース
            //\xC2\xA0:NO-BREAK SPACE(U+00A0)
            //\xE3\x80\x80:全角スペース(U+3000)
            if (preg_match("/[^\x20\xC2\xA0\xE3\x80\x80]/u", $keyword) === 0) {
                throw new RangeException('文字が含まれていません.');
            }
        }

        return $keywords;
    }
}
