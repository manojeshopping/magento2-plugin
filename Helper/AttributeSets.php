<?php

namespace Springbot\Main\Helper;

use Magento\Eav\Model\Entity\Attribute\Set as MagentoAttributeSet;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class AttributeSets extends AbstractHelper
{
    /**
     * @var MagentoAttributeSet
     */
    protected $_attributeSet;

    /**
     * @var Collection
     */
    protected $_attributeCollection;

    /**
     * AttributeSets constructor.
     *
     * @param Context             $context
     * @param MagentoAttributeSet $attributeSet
     */
    public function __construct(
        Context $context,
        MagentoAttributeSet $attributeSet,
        Collection $attributeCollection
    )
    {
        $this->_attributeSet = $attributeSet;
        $this->_attributeCollection = $attributeCollection;
        parent::__construct($context);
    }

    /**
     * Get attribute set by id and all child attributes
     *
     * @param int $id
     *
     * @return array
     */
    public function getAttributeSetById($id)
    {
        // Here we load the attribute set based on the $id passed
        $attributeSet = $this->_attributeSet->load($id);
        // Convert attribute set object to an array
        $array = $attributeSet->toArray();
        // Load attribute collection and filter by $id
        $attributeCollection = $this->_attributeCollection
            ->setAttributeSetFilter($id);
        // Load attribute collection items
        $attributesArray = $attributeCollection->load()->getItems();
        // Initialize empty array for attribute set items
        $attributeSetItems = [];
        // loop through each attribute and add it to to our array
        foreach ($attributesArray as $attribute) {
            $attributeSetItems[] = $attribute->toArray();
        }
        // Add the set items to our attribute set array
        $array['items'] = $attributeSetItems;
        // Return the array
        return $array;
    }

    /**
     * Get all attribute sets and their child attributes
     *
     * @return array
     */
    public function getAttributeSets()
    {
        $finalArray = [];
        $attributeSetIdArray = [];
        $collection = $this->_attributeSet->getCollection();

        foreach ($collection as $set) {
            $attributeSetIdArray[] = $set['attribute_set_id'];
        }

        for ($i = 0; $i < count($attributeSetIdArray); $i++) {
            $finalArray[] = self::getAttributeSetById($attributeSetIdArray[$i]);
        }

        return $finalArray;
    }
}
