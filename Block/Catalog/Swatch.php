<?php
namespace Venture\Qooq\Block\Catalog;


/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Swatch extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Swatches\Model\Swatch
     */
    protected $_swatchloader;

    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;


    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Framework\Registry $registry,
                                \Magento\Swatches\Model\Swatch $_swatchloader,
                                array $data = []){
        $this->_swatchloader = $_swatchloader;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    public function loadSwatch($id = null){
        return $this->_swatchloader->getCollection()->addFieldToFilter("option_id", $id)->getFirstItem();
    }

    public function renderSwatch(){
        $id = $this->getProduct()->getData($this->getData('at_code'));
        $swatch = $this->loadSwatch($id);
        if($swatch){
            //Is visual
            if($this->isTextSwatch($swatch) || $this->isVisualSwatch($swatch)){
               return '<div class="'.$this->getData('css_class').'">
                        <p>'.__($this->getData('title')).'</p>
                        <div class="color-red" style="background-color: '.$swatch->getValue().'"></div>
                       </div>';
            }else{
                return $id;
            }
        }
    }

    private function isTextSwatch($attribute)
    {
        return $attribute->getData('type') == \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_TEXT;
    }

    private function isVisualSwatch($attribute)
    {
        return $attribute->getData('type') == \Magento\Swatches\Model\Swatch::SWATCH_TYPE_VISUAL_COLOR;
    }
}
