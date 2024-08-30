<?php
namespace CI\AttributeEmailTemplates\Plugin;

use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\Order;
use CI\AttributeEmailTemplates\Model\Email\Sender as CustomSender;
use CI\AttributeEmailTemplates\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;

class OrderSenderPlugin
{
    protected $customSender;
    protected $logger;
    protected $scopeConfig;

    public function __construct(
        CustomSender $customSender,
        Logger $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->customSender = $customSender;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundSend(OrderSender $subject, callable $proceed, Order $order, $forceSyncMode = false)
    {
        // Log before processing the order
        $this->logger->info('Before processing order: ' . $order->getEntityId());

        $customEmailSent = false;

        foreach ($order->getAllItems() as $item) {
            $product = $item->getProduct();
            $attribute = $product->getCustomAttribute('course_delivery');

            if ($attribute && $attribute->getValue()) {
                // Log before calling the custom email sender
                //$this->logger->info('Before calling sendEmailBasedOnAttribute for attribute: ' . $attribute->getValue());

                // Use the custom sender to send the email
                $this->customSender->sendEmailBasedOnAttribute($order, $attribute->getValue());

                // Mark that a custom email has been sent
                $customEmailSent = true;

                // Log after the email is sent
                $this->logger->info('After processing order with custom email: ' . $order->getEntityId());
            }
        }

        // If no custom email was sent, proceed with the default email
        if (!$customEmailSent) {
            $this->logger->info('Proceeding with default email.');
            return $proceed($order, $forceSyncMode);
        }

        return true; // Prevent the default email from being sent when custom email is sent
    }
}
