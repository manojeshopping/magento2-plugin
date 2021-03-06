<?php

namespace Springbot\Main\Model;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class StoreConfiguration
 *
 * @package Springbot\Main\Model
 */
class StoreConfiguration extends AbstractModel
{

    private $_config;
    private $_scopeConfig;

    /**
     * @param Config $config
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        Config $config,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->_config = $config;
        $this->_scopeConfig = $scopeConfigInterface;
        parent::__construct($context, $registry);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getSpringbotStoreId($storeId)
    {
        return $this->_scopeConfig->getValue($this->_makeConfigKey('store_id', $storeId));
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getApiToken($storeId)
    {
        return $this->_scopeConfig->getValue($this->_makeConfigKey('security_token', $storeId));
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getGuid($storeId)
    {
        return $this->_scopeConfig->getValue($this->_makeConfigKey('store_guid', $storeId));
    }

    /**
     * Takes an associative array of variables which will be
     *
     * @param int $storeId
     * @param array $vars
     */
    public function saveValues($storeId, $vars)
    {
        foreach ($vars as $key => $value) {
            $configKey = $this->_makeConfigKey($key, $storeId);
            $this->_config->saveConfig($configKey, $value, 'default', 0);
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function saveGlobalValue($key, $value)
    {
        $this->_config->saveConfig($this->_makeConfigKey($key), $value, 'default', 0);
    }

    /**
     * @param string $configKey
     * @param string $storeId
     * @return string
     */
    private function _makeConfigKey($configKey, $storeId = '')
    {
        $configKey = "springbot/configuration/{$configKey}";
        if ($storeId != '') {
            $configKey = "{$configKey}_{$storeId}";
        }
        return $configKey;
    }
}
