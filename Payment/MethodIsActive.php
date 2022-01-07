<?php


namespace Customization\RestrictPMethod\Observer\Payment;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class MethodIsActive implements ObserverInterface
{

    protected $_cart;
    protected $_checkoutSession;
    protected $productRepository;
    public function __construct(
        Cart $cart,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $quote = $this->getCheckoutSession()->getQuote();
        $categoryID = 167; //Add your category ID to disable specific payment method on its products
        $items = $quote->getAllItems();
        $flag = false;
        foreach($items as $item) {
            $product = $this->getProduct($item->getProductId());
            $categoryIds = $product->getCategoryIds();
            if(in_array($categoryID, $categoryIds)){
                $flag = true;
                break;
            }
        }
        // you can replace "cashondelivery" with your required payment method code to disable it
        if($flag == true && $observer->getEvent()->getMethodInstance()->getCode()=="cashondelivery"){
            $checkResult = $observer->getEvent()->getResult();
            $checkResult->setData('is_available', false);
        }
    }
    public function getProduct($productId)
    {
        return $product = $this->productRepository->getById($productId);
    }
    public function getCart()
    {
        return $this->_cart;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }
}
