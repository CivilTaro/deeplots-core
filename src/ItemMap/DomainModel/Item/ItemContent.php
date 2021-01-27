<?php
declare(strict_types=1);

namespace DeepLots\ItemMap\DomainModel\Item;

use DeepLots\Common\DomainModel\ValueObject;
use \Exception;
use \InvalidArgumentException;
use \RangeException;

class ItemContent extends ValueObject
{
    /**
     * コンテンツ
     * 
     * 改行コードはすべて\n(LF)に置換済み.
     *
     * @access private
     * @var string
     */
    private $content;
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param string $content
     * @return void
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * セッター
     *
     * @access private
     * @param string $content
     * @return void
     * @throws RangeException
     */
    private function setContent(string $content): void
    {
        if ($content === '') {
            throw new RangeException('コンテンツが空です.');
        }

        // 第1引数の'/\r\n/u'は改行コードとして評価される."/\r\n/u"も同じ.
        // 第2引数の'\n'は文字列だが、"\n"は改行コードとして評価される.
        $replacedContent = preg_replace('/\r\n|\r/u', "\n", $content);

        if (mb_strlen($replacedContent) > 5000) {
            throw new RangeException('コンテンツが5000文字を超えています.');
        }

        //\\x0-\x1F\x7F:ASCIIコードで制御文字
        //\x20:ASCIIコードで半角スペース
        //\xC2\xA0:NO-BREAK SPACE(U+00A0)
        //\xE3\x80\x80:全角スペース(U+3000)
        if (preg_match('/[^\\x0-\x1F\x7F\x20\xC2\xA0\xE3\x80\x80]/u', $replacedContent) === 0) {
            throw new RangeException('文字が含まれていません.');
        }

        $this->content = $replacedContent;
    }

    /**
     * 与えられた値オブジェクトがこの値オブジェクトと等しいか判定する.
     *
     * @access public
     * @param ValueObject $content
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function equals(ValueObject $content): bool
    {
        if (!$content instanceof ItemContent) {
            throw new InvalidArgumentException(ItemContent::class.'ではない値オブジェクトです.');
        }

        return $this->content() === $content->content();
    }
}
