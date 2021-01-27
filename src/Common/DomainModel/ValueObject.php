<?php
declare(strict_types=1);

namespace DeepLots\Common\DomainModel;

/**
 * 値オブジェクトの抽象クラス
 */
abstract class ValueObject
{
    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $valueObject
     * @return boolean
     */
    abstract public function equals(ValueObject $valueObject): bool;
}
