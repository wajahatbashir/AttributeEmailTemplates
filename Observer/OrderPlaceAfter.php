<?php
namespace CI\AttributeEmailTemplates\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use CI\AttributeEmailTemplates\Model\Email\Sender;

class OrderPlaceAfter implements ObserverInterface
{
    protected $emailSender;

    public function __construct(Sender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function execute(Observer $observer)
    {
        /*This is working code but i am using plugin instead observer*/
        
        /*$order = $observer->getEvent()->getOrder();
        foreach ($order->getAllItems() as $item) {
            $product = $item->getProduct();
            $attribute = $product->getCustomAttribute('course_delivery'); // Assume 'course_delivery' is the attribute code

            if ($attribute) {
                $this->emailSender->sendEmailBasedOnAttribute($order, $attribute->getValue());
            }
        }*/
    }
}
