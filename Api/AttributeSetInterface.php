<?php

namespace Springbot\Main\Api;

/**
 * @api
 */
interface AttributeSetInterface
{
    /**
     * Gets a list of child attributes by parent set id.
     * 
     * @param int $id
     * @return array
     */
    public function getAttributeSetById($id);
    
    /**
     * Get a list of all parent attribute sets and their
     * child attributes.
     * 
     * @return array
     */
    public function getAttributeSets();
}
