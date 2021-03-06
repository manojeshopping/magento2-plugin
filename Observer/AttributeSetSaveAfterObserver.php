<?php

namespace Springbot\Main\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Springbot\Queue\Model\Queue;
use Magento\Framework\Event\Observer;
use Springbot\Main\Model\Handler\AttributeSetHandler;
use Magento\Eav\Model\Entity\Attribute\Set as MagentoAttributeSet;

class AttributeSetSaveAfterObserver implements ObserverInterface
{
    private $_logger;
    private $_queue;

    /**
     * @param LoggerInterface $loggerInterface
     * @param Queue $queue
     */
    public function __construct(LoggerInterface $loggerInterface, Queue $queue)
    {
        $this->_logger = $loggerInterface;
        $this->_queue = $queue;
    }

    /**
     * Pull the attribute data from the event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $attributeSet = $observer->getEvent()->getObject();
            /* @var MagentoAttributeSet $attributeSet */
            $this->_queue->scheduleJob(AttributeSetHandler::class, 'handle', [1, $attributeSet->getId()]);
            $this->_logger->debug("Created/Updated Attribute ID: " . $attributeSet->getId());
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
    }
}
