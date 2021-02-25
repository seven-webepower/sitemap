<?php

namespace WebePower\Sitemap\Block;

class CategorySitemap extends \Magento\Framework\View\Element\Template
{
    protected $_categoryHelper;
    protected $categoryFlatConfig;
    protected $_productVisibility;
    protected $_productCollectionFactory;
    protected $_filterCategories = [];

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->_productVisibility = $productVisibility;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Return categories helper
     */
    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

    /**
     * Retrieve current store categories
     *
     * @param bool|string $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return \Magento\Framework\Data\Tree\Node\Collection|\Magento\Catalog\Model\Resource\Category\Collection|array
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /**
     * Retrieve child store categories
     *
     */
    public function getChildCategories($category)
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        return $subcategories;
    }

    public function getSubCatsHtml($category)
    {
        if ($childrenCategories = $this->getChildCategories($category)) : ?>
            <ul><?php foreach ($childrenCategories as $childrenCategory) : if (!$childrenCategory->getIsActive()) continue;
                    $this->_filterCategories[] = $childrenCategory->getId(); ?>
                    <li>
                        <a href="<?php echo $this->getCategoryHelper()->getCategoryUrl($childrenCategory) ?>"><?php echo $childrenCategory->getName() ?></a>
                        <?php $this->getSubCatsHtml($childrenCategory); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
<?php endif;
    }

    public function getProducts($category)
    {
        $this->_filterCategories[] = $category->getId();

        $collection = $this->_productCollectionFactory->create()->addCategoriesFilter(
            ['eq' => $this->_filterCategories]
        );

        $this->_filterCategories = [];

        return $collection->addAttributeToFilter('status', '1')
            ->addStoreFilter()
            ->addWebsiteFilter()
            ->addUrlRewrite()
            ->addAttributeToSelect('*')
            ->setVisibility($this->_productVisibility->getVisibleInCatalogIds())
            ->addAttributeToSort('name', 'ASC');
    }
}
