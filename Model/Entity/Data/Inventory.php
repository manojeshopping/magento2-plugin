<?php

namespace Springbot\Main\Model\Entity\Data;

use Springbot\Main\Api\Entity\Data\InventoryInterface;

/**
 * Class Inventory
 * @package Springbot\Main\Model\Entity\Data
 */
class Inventory extends \Magento\CatalogInventory\Model\Stock\Item implements InventoryInterface
{
}
