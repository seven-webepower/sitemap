<?php 

namespace WebePower\Sitemap\Block;

class PageSitemap extends \Magento\Framework\View\Element\Template
{
    protected $pageCollection;
	protected $storeManagerInterface;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollection,
		array $data = []
	){
        $this->pageCollection = $pageCollection;
		parent::__construct($context, $data);
    }

    public function getPages()
    {
        $collection = $this->pageCollection->create();
        $collection
            ->addFieldToFilter('store_id' , $this->getStoreId())
            ->addFieldToFilter('use_in_sitemap', 1)
            ->addFieldToFilter('is_active' , \Magento\Cms\Model\Page::STATUS_ENABLED);
        return $collection;
    }
}