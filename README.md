# Magento 2 Checkout Success Page Testing

Test the checkout success page of your Magento 2 store without completing the
checkout process.

### Installation
In your Magento 2 root directory run  
`composer require pmclain/magento2-successtest`  
`bin/magento setup:upgrade`

### Configuration and Use
After installation, enable via the Magento admin panel under:  
`Stores->Confirugation->Advanced->Developer->Debug->Enable Checkout Success Page Testing`  
You can now navigate to your success page with any increment id include as the
query parameter `order`, ex http://mage2.dev/checkout/onepage/success/?order=000000008&key=securekeyvalue

### License
GNU GENERAL PUBLIC LICENSE Version 3