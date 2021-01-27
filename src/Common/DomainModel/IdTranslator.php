<?php
declare(strict_types=1);

namespace DeepLots\Common\DomainModel;

use DeepLots\Common\DomainModel\UniqueId;
use \RangeException;

class IdTranslator
{
    /**
     * URIにIDを含める際フォーマット
     * 
     * @access private
     */
    private const RESOURCE_PATTERN = '/\A[a-z0-9]{32}\z/u';

    /**
     * アプリケーションで管理する用のIDフォーマットに変換
     *
     * @access public
     * @param string $resource
     * @return string
     * @throws RangeException
     */
    public static function toId(string $resource): string
    {
        if (preg_match(self::RESOURCE_PATTERN, $resource) !== 1) {
            throw new RangeException('不適切な形式なリソースです.');
        }

        $part1 = mb_substr($resource, 0, 8);
        $part2 = mb_substr($resource, 8, 4);
        $part3 = mb_substr($resource, 12, 4);
        $part4 = mb_substr($resource, 16, 4);
        $part5 = mb_substr($resource, 20, 12);
        
        return $part1.'-'.$part2.'-'.$part3.'-'.$part4.'-'.$part5;
    }

    /**
     * URIに含める用のIDフォーマットに変換
     *
     * @access public
     * @param string $id
     * @return string
     * @throws RangeException
     */
    public static function toResource(string $id): string
    {
        if (preg_match(UniqueId::PATTERN, $id) !== 1) {
            throw new RangeException('不適切な形式のIDです.');
        }

        return preg_replace('/-/u', '', $id);
    }
}
