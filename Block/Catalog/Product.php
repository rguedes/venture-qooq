<?php
namespace Venture\Qooq\Block\Catalog;


/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_product;
    protected $_productloader;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'product_block';

    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
                                \Magento\Catalog\Model\ProductFactory $_productloader,
                                array $data = []){
        $this->imageBuilder = $imageBuilder;

        parent::__construct($context, $data);
        $this->_productloader = $_productloader;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_product = $this->getLoadProduct($this->getData('product_id'));
    }

    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * Retrieve product image
     *
     * @return string
     */
    public function getImageUrl($product, $size='product_page_image_small')
    {
        $imageHelper  = $this->_objectManager->get('\Magento\Catalog\Helper\Image');
        return $imageHelper->init($product, $size)->getUrl();
    }

    public function getProductPrice($value){
        return $this->_objectManager->get('Magento\Framework\Pricing\Helper\Data')->currency($value,true,false);
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getProductId()];
    }

    public function getMediaUrl($path){
        return $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA ).$path;
    }
}
