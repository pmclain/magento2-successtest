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

use Pmclain\SuccessTest\Model\GenerateSuccessPage;
use Pmclain\SuccessTest\Model\Config;

class Success
{
    /**
     * @var GenerateSuccessPage
     */
    private $generateSuccessPage;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param GenerateSuccessPage $generateSuccessPage
     * @param Config $config
     */
    public function __construct(
        GenerateSuccessPage $generateSuccessPage,
        Config $config
    ) {

        $this->generateSuccessPage = $generateSuccessPage;
        $this->config = $config;
    }

    /**
     * @param \Magento\Checkout\Controller\Onepage\Success $subject
     * @param $result
     * @return \Magento\Framework\View\Result\Page
     */
    public function afterExecute(\Magento\Checkout\Controller\Onepage\Success $subject, $result)
    {
        if (!$this->config->isEnabled()) {
            return $result;
        }

        $request = $subject->getRequest();

        return $this->generateSuccessPage->execute($request->getParam('order'), $request->getParam('key')) ?? $result;
    }
}
