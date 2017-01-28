<?php 

class Tm_ProductListGallery_Helper_Data extends Mage_Core_Helper_Data
{
	public function getConfigData($group)
	{
		if (!isset($group)) {			
			return false;
		} 
		$config = array(
			'active'				=> Mage::getStoreConfig('productlistgallery/'. $group .'/active', Mage::app()->getStore()),
			'image_width'			=> Mage::getStoreConfig('productlistgallery/'. $group .'/image_width', Mage::app()->getStore()),
	        'image_height'			=> Mage::getStoreConfig('productlistgallery/'. $group .'/image_height', Mage::app()->getStore()),
	        'thumb_size_w'           => Mage::getStoreConfig('productlistgallery/grid/thumb_size_w', Mage::app()->getStore()),
			'thumb_size_h'           => Mage::getStoreConfig('productlistgallery/grid/thumb_size_h', Mage::app()->getStore()),
		);
		return $config;
	}
}