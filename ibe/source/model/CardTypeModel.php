<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-23
 * Time: 10:10 AM
 */

class CardTypeModel {

    private $cardTypeId;
    private $cardTypeCode;
    private $cardTypeName;

    /**
     * @return mixed
     */
    public function getCardTypeId()
    {
        return $this->cardTypeId;
    }

    /**
     * @param mixed $cardTypeId
     */
    public function setCardTypeId($cardTypeId)
    {
        $this->cardTypeId = $cardTypeId;
    }

    /**
     * @return mixed
     */
    public function getCardTypeCode()
    {
        return $this->cardTypeCode;
    }

    /**
     * @param mixed $cardTypeCode
     */
    public function setCardTypeCode($cardTypeCode)
    {
        $this->cardTypeCode = $cardTypeCode;
    }

    /**
     * @return mixed
     */
    public function getCardTypeName()
    {
        return $this->cardTypeName;
    }

    /**
     * @param mixed $cardTypeName
     */
    public function setCardTypeName($cardTypeName)
    {
        $this->cardTypeName = $cardTypeName;
    }

}