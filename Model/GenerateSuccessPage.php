<?php
/**
 * Pmclain_SuccessTest extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to the OSL v3 License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 *
 * @category  Pmclain
 * @package   Pmclain_SuccessTest
 * @copyright Copyright (c) 2017-2019
 * @license   Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace Pmclain\SuccessTest\Model;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;

class GenerateSuccessPage
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Config $config
     * @param OrderFactory $orderFactory
     * @param Session $checkoutSession
     * @param ManagerInterface $eventManager
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Config $config,
        OrderFactory $orderFactory,
        Session $checkoutSession,
        ManagerInterface $eventManager,
        PageFactory $pageFactory
    ) {
        $this->config = $config;
        $this->orderFactory = $orderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->eventManager = $eventManager;
        $this->resultPageFactory = $pageFactory;
    }

    public function execute(string $incrementId = '', string $key = ''): ?\Magento\Framework\View\Result\Page
    {
        $order = $this->loadOrder($incrementId, $key);
        if (!$order || !$order->getId()) {
            return null;
        }

        $this->checkoutSession->setLastRealOrderId($order->getIncrementId());
        $this->checkoutSession->setLastOrderId($order->getId());

        $resultPage = $this->resultPageFactory->create();

        $this->eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            ['order_ids' => [$order->getId()]]
        );

        return $resultPage;
    }

    private function loadOrder(string $incrementId, string $key): ?Order
    {
        if (!$this->validateKey($key)) {
            return null;
        }

        /** @var Order $order */
        $order = $this->orderFactory->create();

        return $order->loadByIncrementId($incrementId);
    }

    private function validateKey(string $key): bool
    {
        return strlen($this->config->getSecureKey()) === Config::SECURE_KEY_LENGTH && $this->config->getSecureKey() === $key;
    }
}
