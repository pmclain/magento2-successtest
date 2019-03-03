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

namespace Pmclain\SuccessTest\Controller\Adminhtml\System\Config\System\Dev\Successtest;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Config\Model\ResourceModel\Config as ResourceConfig;
use Magento\Framework\Math\Random;
use Magento\Store\Model\Store;
use Pmclain\SuccessTest\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;

class Refreshkey extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Magento_Backend::system';

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var ResourceConfig
     */
    private $resourceConfig;

    /**
     * @var Random
     */
    private $random;

    /**
     * @var TypeListInterface
     */
    private $cache;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ResourceConfig $resourceConfig
     * @param Random $random
     * @param TypeListInterface $cache
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ResourceConfig $resourceConfig,
        Random $random,
        TypeListInterface $cache
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->resourceConfig = $resourceConfig;
        $this->random = $random;
        $this->cache = $cache;
    }

    public function execute(): \Magento\Framework\Controller\Result\Json
    {
        $key = $this->random->getRandomString(Config::SECURE_KEY_LENGTH);
        $this->resourceConfig->saveConfig(
            Config::PATH_SUCCESS_TEST_SECURE_KEY,
            $key,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );

        $this->cache->cleanType('config');

        $jsonResponse = $this->jsonFactory->create();

        return $jsonResponse->setData(['key' => $key]);
    }
}
