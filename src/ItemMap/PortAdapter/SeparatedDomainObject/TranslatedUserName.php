<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\PortAdapter\SeparatedDomainObject;

use DeepLots\Common\DomainModel\ValueObject;
use DeepLots\ItemMap\DomainModel\User\UserName;
use \RangeException;
use \InvalidArgumentException;

class TranslatedUserName extends ValueObject implements UserName
{
    /**
     * ユーザーネーム
     *
     * @access private
     * @var string
     */
    private $name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param string $name
     * @return void
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * セッター
     *
     * @access private
     * @param string $name
     * @return void
     */
    private function setName(string $name): void
    {
        if ($userName === '') {
            throw new RangeException('ユーザーネームが空です.');
        }

        if (mb_strlen($name) > 20) {
            throw new RangeException('ユーザーネームが20文字を超えています.');
        }

        //\\x0-\x1F\x7F:ASCIIコードで制御文字
        if (preg_match("/[\\x0-\x1F\x7F]/u", $title) === 1) {
            throw new RangeException('不適切な文字が含まれています.');
        }

        //\x20:ASCIIコードで半角スペース
        //\xC2\xA0:NO-BREAK SPACE(U+00A0)
        //\xE3\x80\x80:全角スペース(U+3000)
        if (preg_match("/[^\x20\xC2\xA0\xE3\x80\x80]/u", $title) === 0) {
            throw new RangeException('文字が含まれていません.');
        }

        $this->name = $name;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $userName
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $userName): bool
    {
        if (!$userName instanceof TranslatedUserName) {
            throw new InvalidArgumentException(TranslatedUserName::class.'ではない値オブジェクトです.');
        }

        return $this->name === $userName->name();
    }
}
