<?php
declare(strict_types=1);

namespace DeepLots\Common\PortAdapter;

interface DTO
{
    /**
     * 連想配列に変換する.
     *
     * @access public
     * @param void
     * @return array
     */
    public function toArray(): array;

    /**
     * JSONフォーマットに変換する.
     *
     * @access public
     * @param void
     * @return string
     */
    public function toJson(): string;
}
