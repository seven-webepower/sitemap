<?php 

namespace WebePower\Sitemap\Block;

class ProductSitemap extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_productVisibility;
    protected $_productData;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		array $data = []
	){
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
		parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        if($this->_productData==null){
            $collection = $this->_productCollectionFactory->create();
            $collection
                ->addAttributeToFilter('status','1')
                ->addStoreFilter()
                ->addWebsiteFilter()
                ->addUrlRewrite()
                ->addAttributeToSelect('*')
                ->setVisibility($this->_productVisibility->getVisibleInCatalogIds())
                ->addAttributeToSort('name','ASC');

            $this->_productData = $collection;
        }

        return $this->_productData;
    }

    public function getTotalSize()
    {
        return $this->getProductCollection()->count();
    }

    public function getSortedData()
    {
        $collection = $this->getProductCollection();
        $sortedArray = [];
        if($collection->count()>0){
            foreach ($collection as $product) {
                $name = $product->getName();
                $sortedArray[$this->getFirstChar($name)][] = ['name' => $name, 'url' => $product->getProductUrl()];
            }
        }
        return $sortedArray;
    }

    protected function getFirstChar($name)
    {
        $name = trim($name);
        return strtoupper($name[0]);
    }
}