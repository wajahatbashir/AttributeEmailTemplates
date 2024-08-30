<?php
/**
 * CI
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   CI
 * @package    CI_AttributeEmailTemplates
 * @copyright  Copyright (c) 2024 CI (https://www.magento.com/)
 */

namespace CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field;

/**
 * Block for email template options.
 * @package CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field
 */
class EmailTemplateOptions extends \Magento\Framework\View\Element\Html\Select
{

    /**
     * @var \Magento\Email\Model\ResourceModel\Template\CollectionFactory
     */
    protected $templateCollectionFactory;

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getOptions() as $groupId => $groupLabel) {
                $this->addOption($groupId, addslashes($groupLabel));
            }
        }

        return parent::_toHtml();
    }

    /**
     * @return array
     */
    protected function _getOptions()
    {
        $options    = [];
        //$options[0] = 'Please select';
        if (!empty($this->getAllEmailTemplates())) {
            foreach ($this->getAllEmailTemplates() as $template) {
                $options[$template->getTemplateId()] = $template->getTemplateCode();
            }
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getAllEmailTemplates()
    {
        $collection = $this->templateCollectionFactory->create();
        return $collection->getItems();
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $objectManager      = \Magento\Framework\App\ObjectManager::getInstance();
        $this->templateCollectionFactory = $objectManager->create(\Magento\Email\Model\ResourceModel\Template\CollectionFactory::class);
    }

    /**
     * @param array      $option
     * @param bool|false $selected
     * @return string
     */
    protected function _optionToHtml($option, $selected = false)
    {
        $selectedHtml = $selected ? ' selected="selected"' : '';
        if ($this->getIsRenderToJsTemplate() === true) {
            $selectedHtml .= ' <%= option_extra_attrs.option_' . self::calcOptionHash($option['value']) . ' %>';
        }
        $html = '<option value="' . $this->escapeHtml($option['value']) . '"' . $selectedHtml . '>'
            . $this->escapeHtml($option['label']) .
            '</option>';

        return $html;
    }

    /**
     * @param string $optionValue
     * @return string
     */
    public function calcOptionHash($optionValue)
    {
        return sprintf('%u', crc32($this->getName() . $this->getId() . $optionValue));
    }
}
