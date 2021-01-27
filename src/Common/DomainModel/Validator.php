<?php
declare(strict_types=1);

namespace DeepLots\Common\DomainModel;

use DeepLots\Common\DomainModel\ValidationNotificationHandler;

abstract class Validator
{
    /**
     * 通知ハンドラ
     *
     * @access private
     * @var ValidationNotificationHandler
     */
    private $validationNotificationHandler;

    /**
     * ゲッター
     *
     * @access public
     * @param void
     * @return ValidationNotificationHandler
     */
    public function validationNotificationHandler(): ValidationNotificationHandler
    {
        return $this->validationNotificationHandler;
    }

    /**
     * セッター
     *
     * @access private
     * @param ValidationNotificationHandler $handler
     * @return void
     */
    private function setValidationNotificationHandler(ValidationNotificationHandler $handler): void
    {
        $this->validationNotificationHandler = $handler;
    }

    /**
     * バリデーション
     * 
     * @access public
     * @param void
     * @return void
     */
    abstract public function validate(): void;
}
