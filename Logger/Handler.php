<?php
namespace CI\AttributeEmailTemplates\Logger;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base as MagentoBaseHandler;

class Handler extends MagentoBaseHandler
{
    protected $fileName = '/var/log/attribute_email_templates.log'; // Custom log file
    protected $loggerType = Logger::DEBUG;
}
