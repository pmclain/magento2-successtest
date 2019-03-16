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

namespace Pmclain\SuccessTest\Test\Integration\Plugin;

class SuccessTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * Test redirect when module is disabled
     * @magentoConfigFixture default_store sales/success_test/enable 0
     * @magentoConfigFixture default_store sales/success_test/secure_key 123456789012
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testLoadSuccessWithModuleDisabled(): void
    {
        $this->dispatch('checkout/onepage/success/key/123456789012/order/100000001');
        $response = $this->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        /** @var \Zend\Http\Header\Location $location */
        $location = $response->getHeader('Location');
        $this->assertContains('checkout/cart', $location->getUri());
    }

    /**
     * Test redirect when module is enabled and valid key provided
     * @magentoConfigFixture default_store sales/success_test/enable 1
     * @magentoConfigFixture default_store sales/success_test/secure_key 123456789012
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testLoadSuccessWithModuleEnabled(): void
    {
        $this->dispatch('checkout/onepage/success/key/123456789012/order/100000001');
        $response = $this->getResponse();

        $this->assertContains(
            'Your order # is:',
            $response->getBody()
        );
    }

    /**
     * Test redirect when module is enabled and invalid key provided
     * @magentoConfigFixture default_store sales/success_test/enable 1
     * @magentoConfigFixture default_store sales/success_test/secure_key 123456789012
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testLoadSuccessWithModuleEnabledInvalidKey(): void
    {
        $this->dispatch('checkout/onepage/success/key/12/order/100000001');
        $response = $this->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        /** @var \Zend\Http\Header\Location $location */
        $location = $response->getHeader('Location');
        $this->assertContains('checkout/cart', $location->getUri());
    }

    /**
     * Test redirect when module is enabled and invalid key provided
     * @magentoConfigFixture default_store sales/success_test/enable 1
     * @magentoConfigFixture default_store sales/success_test/secure_key 123456789012
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testLoadSuccessWithModuleEnabledValidKeyNoSuchOrder(): void
    {
        $this->dispatch('checkout/onepage/success/key/123456789012/order/100');
        $response = $this->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
        /** @var \Zend\Http\Header\Location $location */
        $location = $response->getHeader('Location');
        $this->assertContains('checkout/cart', $location->getUri());
    }
}
