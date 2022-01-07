# Restrict Payment Method Module for Magento 2

## Requirements

* Magento >= 2.3

## Description

Disable specific payment methods on checkout page when cart have at least one product from specific category.

## Installation Steps

1. Create app/code/Customization directory in the root of your Magento 2 project.
2. Extract the files in the created directory.
3. Run the following commands:

```shell
bin/magento maintenance:enable
bin/magento setup:upgrade
```

4. Recompile your Magento project: In Production mode, you may receive a message to “Please rerun Magento compile command”. Magento does not prompt you to run the compile command in Developer mode

```shell
bin/magento setup:di:compile
```

5. Verify that extension is enabled:

```shell
bin/magento module:status Customization_RestrictPMethod
```

You should see output verifying that the extension is no longer disabled:

```shell
Module is enabled
```

If it is disabled, enter the following command

```shell
bin/magento module:enable Customization_RestrictPMethod
```

6. Run the following commands to upgrade, deploy, and clean the cache:

```shell
bin/magento setup:upgrade
bin/magento setup:static-content:deploy
bin/magento cache:clean
```

7. Disable Maintenance Mode:

```shell
bin/magento maintenance:disable
```

## Configuration

1. Open the Following File Customization/RestrictPMethod/Observer/Payment/MethodIsActive.php
2. Enter the Category ID you need at the following line.
```php
$categoryID = ""; //Add your category ID to disable specific payment method on its products
``` 

3. Enter your payment method code that you need to disable.

```php
if ($flag == true && $observer->getEvent()->getMethodInstance()->getCode() == "YOUR PAYMENT METHOD CODE") {
    $checkResult = $observer->getEvent()->getResult();
    $checkResult->setData('is_available', false);
}
```

Note: You can disable and enable more than one payment method by create more than if conditon same as above and only changing the payment method code.
