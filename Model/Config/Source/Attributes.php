<?php
namespace CI\AttributeEmailTemplates\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Framework\Option\ArrayInterface;
use Magento\Eav\Model\Entity\Attribute;

class Attributes implements ArrayInterface
{
    protected $attributeCollectionFactory;

    public function __construct(AttributeCollectionFactory $attributeCollectionFactory)
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];

        // Load the specific attribute by attribute code
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection->addFieldToFilter('attribute_code', 'course_delivery');

        $attribute = $attributeCollection->getFirstItem();

        if ($attribute && $attribute->usesSource()) {
            $attributeOptions = $attribute->getSource()->getAllOptions();
            foreach ($attributeOptions as $option) {
                $options[] = [
                    'label' => $option['label'],
                    'value' => $option['value']
                ];
            }
        }

        return $options;
    }
}
