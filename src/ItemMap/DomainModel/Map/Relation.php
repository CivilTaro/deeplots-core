<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Item\Item;
use DeepLots\ItemMap\DomainModel\Map\Map;
use DeepLots\ItemMap\DomainModel\Map\Connection;
use \InvalidArgumentException;
use \RangeException;

class Relation extends ValueObject
{
    /**
     * アイテムの関係
     * コネクションのコレクション
     *
     * [Connection $c1, Connection $c2, ...]
     * 
     * @access private
     * @var array
     */
    private $relation;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param array $relation
     * @return void
     */
    public function __construct(array $relation)
    {
        $this->setRelation($relation);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return array
     */
    public function relation(): array
    {
        return $this->relation;
    }

    /**
     * セッター
     *
     * @access private
     * @param array $relation
     * @return void
     * @throws RangeException
     */
    private function setRelation(array $relation): void
    {

        if ($relation === []) {
            throw new RangeException('リレーションが空です.');
        }

        $_relation = [];

        /*
         想定する配列は,
         $relation = [
             ['parent' => 'itemId', 'child' => 'itemId'],
             ['parent' => 'itemId', 'child' => 'itemId'],
             ['parent' => 'itemId', 'child' => 'itemId']
         ];
        */
        foreach ($relation as $index => $value) {
            if (!is_array($value)) {
                throw new RangeException('不適切な値が含まれています.');
            }

            if (!array_key_exists('parent', $value) || !array_key_exists('child', $value)) {
                throw new RangeException('不適切な値が含まれています.');
            }

            if (!is_string($value['parent']) || !is_string($value['child'])) {
                throw new RangeException('文字列型ではない値が含まれています.');
            }

            $_relation[] = Map::createConnection(
                Item::createId($value['parent']),
                Item::createId($value['child'])
            );
        }

        for ($i = 0; $i < count($_relation) - 1; $i++) {
            for ($j = $i + 1; $j < count($_relation); $j++) {
                if ($_relation[$i]->equals($_relation[$j])) {
                    throw new RangeException('同じコネクションが含まれています.');
                }
            }
        }

        $this->relation = $_relation;
    }
    
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $relation
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $relation): bool
    {
        if (!$relation instanceof Relation) {
            throw new InvalidArgumentException(Relation::class.'ではない値オブジェクトです.');
        }

        $thisRelation = $this->relation();
        $otherRelation = $relation->relation();

        if (count($thisRelation) !== count($otherRelation)) {
            return false;
        }

        foreach ($thisRelation as $index => $connection) {
            if (!$connection->equals($otherRelation[$index])) {
                return false;
            }
        }

        return true;
    }
}
