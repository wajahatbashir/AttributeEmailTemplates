# Product Attribute Email Templates

This Magento 2 module enables the sending of custom email templates based on specific product attributes in an order. The module allows you to map product attributes to email templates, and when an order is placed, the appropriate email template is sent to the customer based on the attributes of the purchased products.

## Features

- **Attribute-Based Email Templates**: Assign specific email templates to product attributes.
- **Custom Logging**: Detailed logging of email sending processes and attribute mappings.
- **Magento Admin Configuration**: Manage attribute-to-template mappings through the Magento Admin.
- **Fallback Template**: If no specific template is found for an attribute, a fallback template can be used.
- **Default Email Handling**: The module intelligently decides whether to send a custom email or proceed with Magento's default order confirmation email based on the presence of mapped attributes.

## Installation

1. Download or clone this repository into your Magento 2 `app/code/CI/AttributeEmailTemplates` directory.
2. Run the following commands to install the module:

   ```bash
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   bin/magento cache:clean
   bin/magento cache:flush
   ```

3. Log in to the Magento Admin, and go to `Stores > Configuration > CI Attribute Email Templates` to configure the module.

## Configuration

1. **Enable Module**: You can enable or disable the module from the admin configuration.
2. **Attribute to Email Template Mapping**: Define mappings between product attribute values and email templates. When a product with a specific attribute value is ordered, the corresponding email template is used.

## Usage

1. **Order Placement**: When an order is placed, the module checks the product attributes in the order. If a mapping is found, the corresponding email template is sent. If no mapping is found, the module uses the default email template.
2. **Logging**: Logs are stored in `var/log/attribute_email_templates.log`. These logs include information about the email templates used, attribute mappings, and any errors that occurred during email sending.

## Customization

- **Adding New Mappings**: To add new mappings, update the configuration in the Magento Admin under `Stores > Configuration > CI Attribute Email Templates`.
- **Fallback Template**: The fallback template can be defined in the `Sender.php` file if no matching template is found.

## Uninstallation

To uninstall the module, remove the directory `app/code/CI/AttributeEmailTemplates`, and run the following commands:

```bash
bin/magento module:uninstall CI_AttributeEmailTemplates
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:clean
bin/magento cache:flush
```

## Support

If you encounter any issues or have questions, please refer to the logs in `var/log/attribute_email_templates.log` or contact support.

---