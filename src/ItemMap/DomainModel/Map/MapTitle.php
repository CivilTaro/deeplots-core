<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Map;

use DeepLots\Common\DomainModel\ValueObject;
use \InvalidArgumentException;
use \RangeException;

class MapTitle extends ValueObject
{
    /**
     * マップのタイトル
     *
     * @access private
     * @var string
     */
    private $title;

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $title
     * @return void
     */
    public function __construct(string $title)
    {
        $this->setTitle($title);
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
     * セッター
     *
     * @access private
     * @param string $title
     * @return void
     * @throws RangeException
     */
    private function setTitle(string $title): void
    {
        if ($title === '') {
            throw new RangeException('タイトルが空です.');
        }

        if (mb_strlen($title) > 100) {
            throw new RangeException('タイトルが100文字を超えています.');
        }

        //\\x0-\x1F\x7F:ASCIIコードで制御文字
        if (preg_match('/[\\x0-\x1F\x7F]/u', $title) === 1) {
            throw new RangeException('不適切な文字が含まれています.');
        }

        // \x20:ASCIIコードで半角スペース
        // \xC2\xA0:NO-BREAK SPACE(U+00A0)
        // \xE3\x80\x80:全角スペース(U+3000)
        if (preg_match('/[^\x20\xC2\xA0\xE3\x80\x80]/u', $title) === 0) {
            throw new RangeException('文字が含まれていません.');
        }

        $this->title = $title;
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

        return $this->title() === $mapTitle->title();
    }
}
