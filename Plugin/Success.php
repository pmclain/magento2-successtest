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
namespace Pmclain\SuccessTest\Plugin;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;

class Success
{
  /** @var ManagerInterface */
  protected $_eventManager;

  /** @var PageFactory */
  protected $_resultPageFactory;

  /** @var ScopeConfigInterface */
  protected $_scopeConfig;

  /** @var OrderFactory */
  protected $_orderFactory;

  /** @var Order */
  protected $_order;

  /** @var Session */
  protected $_checkoutSession;

  /**
   * Success constructor.
   * @param ManagerInterface $eventManager
   * @param PageFactory $resultPageFactory
   * @param ScopeConfigInterface $scopeConfig
   * @param OrderFactory $orderFactory
   * @param Session $session
   */
  public function __construct(
    ManagerInterface $eventManager,
    PageFactory $resultPageFactory,
    ScopeConfigInterface $scopeConfig,
    OrderFactory $orderFactory,
    Session $session
  ) {
    $this->_eventManager = $eventManager;
    $this->_resultPageFactory = $resultPageFactory;
    $this->_scopeConfig = $scopeConfig;
    $this->_orderFactory = $orderFactory;
    $this->_checkoutSession = $session;
  }

  /**
   * @param \Magento\Checkout\Controller\Onepage\Success $subject
   * @param $result
   * @return \Magento\Framework\View\Result\Page
   */
  public function afterExecute(\Magento\Checkout\Controller\Onepage\Success $subject, $result)
  {
    if (!$this->_isEnabled()) {
      return $result;
    }

    $order = $this->_getTestOrder($subject->getRequest()->getParam('order'));

    if (!$order->getId()) {
      return $result;
    }

    $this->_checkoutSession->setLastRealOrderId($order->getIncrementId());

    $resultPage = $this->_resultPageFactory->create();

    $this->_eventManager->dispatch(
      'checkout_onepage_controller_success_action',
      ['order_ids' => [$order->getId()]]
    );

    return $resultPage;
  }

  /**
   * @return bool
   */
  protected function _isEnabled()
  {
    if ($this->_scopeConfig->getValue('dev/debug/success_test', ScopeInterface::SCOPE_STORE)) {
      return true;
    }

    return false;
  }

  /**
   * @param $incrementId string|bool
   * @return Order
   */
  protected function _getTestOrder($incrementId)
  {
    /** @var Order $order */
    $order = $this->_orderFactory->create();

    $order->loadByIncrementId($incrementId);

    return $order;
  }
}