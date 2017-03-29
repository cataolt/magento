<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Resource type which contains sql code for applying filters and related operations
 * @author Mana Team
 * Injected instead of standard resource catalog/layer_filter_attribute in 
 * Mana_Filters_Model_Filter_Attribute::_getResource().
 */
class Mana_Filters_Resource_Filter_Attribute
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
{
    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Mana_Filters_Model_Filter_Attribute $model
     * @return mixed
     */
    public function countOnCollection($collection, $model) {
        //Mana_Core_Profiler2::logQueries(true);
        $select = $collection->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $connection = $this->_getReadAdapter();
        $attribute = $model->getAttributeModel();
        $tableAlias = $attribute->getAttributeCode() . '_idx';

        $conditions = array(
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $model->getStoreId()),
        );

        $select
            ->join(
                array($tableAlias => $this->getMainTable()),
                join(' AND ', $conditions),
                array('value', 'count' => "COUNT(DISTINCT {$tableAlias}.entity_id)")
            )
            ->group("{$tableAlias}.value");

        $result = $connection->fetchPairs($select);
        //Mana_Core_Profiler2::logQueries(false);
        return $result;
    }

    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Mana_Filters_Model_Filter_Attribute $model
     * @param int[] $attributeIds
     * @return array
     */
    public function optimizedCountOnCollection($collection, $model, $attributeIds) {
        $select = $collection->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::GROUP);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        $db = $this->_getReadAdapter();
        $storeId = $model->getStoreId();
        $attributeIds = implode(', ', $attributeIds);

        $columns = array(
            'attribute_id' => new Zend_Db_Expr("`_oc_o`.`attribute_id`"),
            'label' => new Zend_Db_Expr("COALESCE(`_oc_ls`.`value`, `_oc_lg`.`value`)"),
            'value' => new Zend_Db_Expr("`_oc_o`.`option_id`"),
            'sort_order' => new Zend_Db_Expr("`_oc_o`.`sort_order`"),
            'count' => new Zend_Db_Expr("COUNT(DISTINCT `_oc_idx`.`entity_id`)"),
        );
        $select
            ->from(array('_oc_o' => $this->getTable('eav/attribute_option')), null)
            ->joinLeft(array('_oc_lg' => $this->getTable('eav/attribute_option_value')),
                "`_oc_lg`.`option_id` = `_oc_o`.`option_id` AND `_oc_lg`.`store_id` = 0", null)
            ->joinLeft(array('_oc_ls' => $this->getTable('eav/attribute_option_value')),
                $db->quoteInto("`_oc_ls`.`option_id` = `_oc_o`.`option_id` AND `_oc_ls`.`store_id` = ?", $storeId), null)
            ->joinLeft(array('_oc_idx' => $this->getMainTable()),
                "`_oc_idx`.`entity_id` = `e`.`entity_id` AND " .
                "`_oc_idx`.`attribute_id` = `_oc_o`.`attribute_id` AND " .
                "`_oc_idx`.`value` = `_oc_o`.`option_id` AND " .
                $db->quoteInto("`_oc_idx`.`store_id` = ?", $storeId), null)
            ->where("`_oc_o`.`attribute_id` IN ($attributeIds)")
            ->columns($columns)
            ->group(array('attribute_id', 'label', 'value', 'sort_order'))
            ->order("sort_order ASC");

        $sql = $select->__toString();
        return $db->fetchAll($select);
    }

    /**
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param Mana_Filters_Model_Filter_Attribute $model
     * @param array $value
     * @return Mana_Filters_Resource_Filter_Attribute
     */
    public function applyToCollection($collection, $model, $value) {
        $attribute = $model->getAttributeModel();
        $connection = $this->_getReadAdapter();

        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $conditions = array(
            "`{$tableAlias}`.entity_id = e.entity_id",
            $connection->quoteInto("`{$tableAlias}`.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("`{$tableAlias}`.store_id = ?", $collection->getStoreId()),
            "`{$tableAlias}`.value in (" . implode(',', array_filter($value)) . ")"
        );

        if ($attribute->getFrontendInput() == 'multiselect') {
            $conditions = array(
                "{$tableAlias}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
                $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId())
            );
        }
        $conditions = join(' AND ', $conditions);
        $collection->getSelect()
            ->distinct()
            ->join(array($tableAlias => $this->getMainTable()), $conditions, array());

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