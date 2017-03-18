<?php
class Tm_Catalog_Model_Resource_Layer_Filter_Attribute extends Mage_Catalog_Model_Resource_Layer_Filter_Attribute
{
    public function applyFilterToCollection($filter, $value)
    {
        var_dump('here');die();
        $collection = $filter->getLayer()->getProductCollection();
        $attribute  = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
            $connection->quoteInto("{$tableAlias}.value = ?", $value)
        );

        if ($attribute->getFrontendInput() == 'multiselect') {                        // To check whether is it multiselect attribute
            $conditions = array(
                "{$tableAlias}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
                $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId())
            );
        }

        $collection->getSelect()->join(
        array($tableAlias => $this->getMainTable()),
        implode(' AND ', $conditions),
        array()
    );

        if ($attribute->getFrontendInput() == 'multiselect') {                        // To check whether is it multiselect attribute
        $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');  // For to read sql query
            $c=count($value)-1;
            $query = 'SELECT entity_id FROM catalog_product_index_eav where value in ('. implode(',', array_filter($value)).') GROUP BY entity_id HAVING (COUNT(entity_id)>'.$c.') ';   // get entity id which have both color
           // $results = $readConnection->fetchAll($query);
            $results = $readConnection->query( $query );
           while ( $row = $results->fetch() ) {
               $ids[]=$row['entity_id'];          // collect those entity ids
           }
           $collection->addAttributeToFilter('entity_id', array('in' => $ids));   //then add filter to collection
        }

        return $this;
    }
}