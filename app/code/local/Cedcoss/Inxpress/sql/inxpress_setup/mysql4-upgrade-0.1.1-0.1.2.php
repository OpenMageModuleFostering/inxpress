<?php
$th =  new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup();  
$th->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'inxpress_variant', array(
            'group' => 'General', 
            'type' => 'text',
            'attribute_set' =>  'Default', // Your custom Attribute set
            'backend' => '',
            'frontend' => '',
            'label' => 'Variant',
            'input' => 'text',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '1',
            'searchable' => false,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => true,
            'visible_in_advanced_search' => true,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => 'simple,grouped,configurable,virtual,bundle,customproduct',  // Apply to simple product type
        ) );