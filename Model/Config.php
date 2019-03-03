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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const PATH_SUCCESS_TEST_ENABLE = 'dev/debug/success_test';
    const PATH_SUCCESS_TEST_SECURE_KEY = 'dev/debug/success_test_secure_key';
    const SECURE_KEY_LENGTH = 12;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(static::PATH_SUCCESS_TEST_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    public function getSecureKey(): string
    {
        return $this->scopeConfig->getValue(static::PATH_SUCCESS_TEST_SECURE_KEY, ScopeInterface::SCOPE_STORE) ?? '';
    }
}
