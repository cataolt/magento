<?php
class Tm_Catalog_Model_Product extends Mage_Catalog_Model_Product
{
    public function isSalable()
    {
        return false;
    }
}