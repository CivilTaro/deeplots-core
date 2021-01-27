<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Map\Relaton;
use \InvalidArgumentException;

class NoRelation extends Relation
{
    /**
     * アイテムの関係
     *
     * @access private
     * @var array
     */
    private $relation;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->relation = [];
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
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $relation
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $relation): bool
    {
        if (!$relaiton instanceof Relation) {
            throw new InvalidArgumentException(Relation::class.'ではない値オブジェクトです.');
        }

        if ($relation instanceof NoRelation) {
            return true;
        }

        return false;
    }
}
