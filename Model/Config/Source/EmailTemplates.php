<?php
namespace CI\AttributeEmailTemplates\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class EmailTemplates implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'selfpaced_course_template', 'label' => __('Self-Paced Course Email')],
            ['value' => 'bootcamp_template', 'label' => __('Bootcamp Email')],
            ['value' => 'labs_template', 'label' => __('Labs Email')],
            ['value' => 'assessments_template', 'label' => __('Assessments Email')],
            ['value' => 'subscriptions_template', 'label' => __('Subscriptions Email')],
            ['value' => 'vilt_template', 'label' => __('Virtual Classroom Email')],
            ['value' => 'coaching_template', 'label' => __('Coaching Email')]
        ];
    }
}
