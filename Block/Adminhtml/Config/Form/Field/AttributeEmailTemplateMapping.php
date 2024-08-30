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

declare(strict_types=1);

namespace CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field;

use CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\AttributeOptions;
use CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\EmailTemplateOptions;

/**
 * Adminhtml CI "Attribute Email Mapping" field
 *
 * @api
 * @since 100.0.2
 * @package CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field
 */
class AttributeEmailTemplateMapping extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    /**
     * @var Attributes
     */
    protected $attributesOptionsRenderer;

    /**
     * @var EmailTemplates
     */
    protected $emailTemplatesOptionsRenderer;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_addAfter = false;

        $attributeSelect = $this->_getAttributeOptionsRenderer();
        $emailTemplateSelect = $this->_getEmailTemplateOptionsRenderer();
        $this->addColumn('attribute_options', ['label' => __('Course Attributes'), 'renderer' => $attributeSelect, 'class' => 'attribute-options required-entry']);
        $this->addColumn('email_template_options', ['label' => __('Email Templates'), 'renderer' => $emailTemplateSelect, 'class' => 'email-template-options required-entry']);

        parent::_construct();
    }

    /**
     * @return \CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\AttributeOptions
     */
    protected function _getAttributeOptionsRenderer()
    {
        if (!$this->attributesOptionsRenderer) {
            $this->attributesOptionsRenderer = $this->getLayout()->createBlock(
                \CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\AttributeOptions::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->attributesOptionsRenderer->setClass('attribute_options_select');
            $this->attributesOptionsRenderer->setExtraParams('style="width:150px; font-size:14px"');
        }

        return $this->attributesOptionsRenderer;
    }

    /**
     * @return \CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\EmailTemplateOptions
     */
    protected function _getEmailTemplateOptionsRenderer()
    {
        if (!$this->emailTemplatesOptionsRenderer) {
            $this->emailTemplatesOptionsRenderer = $this->getLayout()->createBlock(
                \CI\AttributeEmailTemplates\Block\Adminhtml\Config\Form\Field\EmailTemplateOptions::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->emailTemplatesOptionsRenderer->setClass('email_template_options_select');
            $this->emailTemplatesOptionsRenderer->setExtraParams('style="width:150px; font-size:14px"');
        }

        return $this->emailTemplatesOptionsRenderer;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        if ($row->getData('attribute_options')) {
            $options['option_' . $this->_getAttributeOptionsRenderer()->calcOptionHash($row->getData('attribute_options'))]
                = 'selected="selected"';
        }
        if ($row->getData('email_template_options')) {
            $options['option_' . $this->_getEmailTemplateOptionsRenderer()->calcOptionHash($row->getData('email_template_options'))]
                = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
