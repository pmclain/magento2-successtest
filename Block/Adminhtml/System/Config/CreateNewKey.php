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

namespace Pmclain\SuccessTest\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Pmclain\SuccessTest\Model\Config;

class CreateNewKey extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'Pmclain_SuccessTest::system/config/createnewkey.phtml';

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    public function getButtonHtml(): string
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id' => 'refresh_success_test_secure_key',
                'label' => __('Refresh Key'),
            ]
        );

        return $button->toHtml();
    }

    public function getAjaxUrl(): string
    {
        return $this->getUrl('*/system_config_system_dev_successtest/refreshkey');
    }

    public function getSecureKey(): ?string
    {
        if (strlen($this->config->getSecureKey()) !== Config::SECURE_KEY_LENGTH) {
            return null;
        }

        return $this->config->getSecureKey();
    }

    /**
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
