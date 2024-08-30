<?php
namespace CI\AttributeEmailTemplates\Model\Config\Backend;

use Magento\Framework\App\Config\Value;

class AttributeTemplateMapping extends Value
{
    public function beforeSave()
    {
        $value = $this->getValue();

        if (is_array($value) && !empty($value)) {
            $serializedValue = serialize($value);
            $this->setValue($serializedValue);
        } else {
            $this->setValue('');
        }

        return parent::beforeSave();
    }

    public function afterLoad()
    {
        $value = $this->getValue();

        if ($value) {
            $unserializedValue = @unserialize($value);
            if ($unserializedValue !== false && is_array($unserializedValue)) {
                $this->setValue($unserializedValue);
            } else {
                $this->setValue([]);
            }
        } else {
            $this->setValue([]);
        }

        return parent::afterLoad();
    }
}
