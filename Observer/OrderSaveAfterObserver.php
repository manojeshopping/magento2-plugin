<?php

namespace Springbot\Main\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Springbot\Queue\Model\Queue;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order as MagentoOrder;
use Springbot\Main\Model\Handler\OrderHandler;
use Magento\Checkout\Model\Session;

class OrderSaveAfterObserver implements ObserverInterface
{
    private $_logger;
    private $_queue;

    /**
     * ProductSaveAfterObserver constructor
     *
     * @param LoggerInterface $loggerInterface
     * @param Queue $queue
     */
    public function __construct(
        LoggerInterface $loggerInterface,
        Queue $queue
    ) {
        $this->_logger = $loggerInterface;
        $this->_queue = $queue;
    }

    /**
     * Pull the order data from the event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            /* @var MagentoOrder $order */
            $orderId = $order->getId();
            $this->_queue->scheduleJob(OrderHandler::class, 'handle', [$order->getStoreId(), $orderId]);
            $this->_logger->debug("Scheduled sync job for product ID: {$orderId}, Store ID: {$order->getStoreId()}");
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
    }
}
