<?php
namespace CI\AttributeEmailTemplates\Model\Email;

use Magento\Sales\Model\Order;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use CI\AttributeEmailTemplates\Logger\Logger;

class Sender
{
    protected $transportBuilder;
    protected $inlineTranslation;
    protected $storeManager;
    protected $scopeConfig;
    protected $jsonSerializer;
    protected $logger;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        JsonSerializer $jsonSerializer,
        Logger $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->jsonSerializer = $jsonSerializer;
        $this->logger = $logger;
    }

    public function sendEmailBasedOnAttribute(Order $order, $attributeValue)
    {
        $isEnabled = $this->scopeConfig->isSetFlag(
            'ci_attribute_email_templates/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            $this->logger->info('CI\AttributeEmailTemplates CI Attribute Email Templates module is disabled.');
            return;
        }

        try {
            $templateId = $this->getTemplateIdByAttribute($attributeValue);
            if (!$templateId) {
                $this->logger->info('No template found for attribute value: ' . $attributeValue);
                return;
            }

            $this->inlineTranslation->suspend();

            $store = $this->storeManager->getStore();

            $senderEmail = $this->scopeConfig->getValue(
                'trans_email/ident_sales/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store->getId()
            );
            $senderName = $this->scopeConfig->getValue(
                'trans_email/ident_sales/name',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $store->getId()
            );

            $this->logger->info('Preparing email transport for order ID: ' . $order->getEntityId());

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $store->getId(),
                ])
                ->setTemplateVars(['order' => $order, 'store' => $store])
                ->setFrom(['name' => $senderName, 'email' => $senderEmail])
                ->addTo($order->getCustomerEmail())
                ->getTransport();

            //$this->logger->info('Sending email using template ID: ' . $templateId);

            $transport->sendMessage();

            $this->logger->info('Email successfully sent to: ' . $order->getCustomerEmail());
        } catch (\Exception $e) {
            $this->logger->error('Error in sendEmailBasedOnAttribute: ' . $e->getMessage());
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    protected function getTemplateIdByAttribute($attributeValue)
    {
        //$this->logger->info('Attempting to retrieve template ID for attribute value: ' . $attributeValue);

        $mapping = $this->scopeConfig->getValue(
            'ci_attribute_email_templates/general/attribute_template_mapping',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($mapping) {
           //$this->logger->info('Raw Mapping Retrieved: ' . $mapping);

            $mapping = $this->jsonSerializer->unserialize($mapping);
            //$this->logger->info('Unserialized Mapping: ' . json_encode($mapping));

            foreach ($mapping as $key => $map) {
                //$this->logger->info('Comparing attribute value: ' . $attributeValue . ' with stored option: ' . $map['attribute_options'] . ' in mapping key: ' . $key);

                if ($map['attribute_options'] == $attributeValue) {
                    $this->logger->info('Matching Template ID Found: ' . $map['email_template_options']);
                    return $map['email_template_options'];
                }
            }
        }

        $this->logger->info('No matching template ID found for attribute value: ' . $attributeValue);
        return null;
    }
}
