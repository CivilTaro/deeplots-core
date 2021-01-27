<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\Map\MapTitle;
use \InvalidArgumentException;

class MapNoTitle extends MapTitle
{
    /**
     * タイトル
     *
     * @access private
     * @var string
     */
    private $title;

    /**
     * コンストラクタ
     * 
     * @access public
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->title = '';
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $mapTitle
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $mapTitle): bool
    {
        if (!$mapTitle instanceof MapTitle) {
            throw new InvalidArgumentException(MapTitle::class.'ではない値オブジェクトです.');
        }

        if ($mapTitle instanceof MapNoTitle) {
            return true;
        }

        return false;
    }
}
