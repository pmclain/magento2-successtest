# Magento 2 Checkout Success Page Testing

Test the checkout success page of your Magento 2 store without completing the
checkout process.

### Installation
In your Magento 2 root directory run  
`composer require pmclain/magento2-successtest`  
`bin/magento setup:upgrade`

### Configuration and Use
After installation, enable via the Magento admin panel under:  
`Stores->Configuration->Sales->Sales->Success Page Test->Enable Checkout Success Page Testing`  
You can now navigate to your success page with any increment id. Generate a new key in `Success Test Secure Key` and change the increment id to the one you want to test with, ex http://mage2.dev/checkout/onepage/success/key/ZW8Wi6krPYif/order/100000008

### License
GNU GENERAL PUBLIC LICENSE Version 3
