<?php
namespace WebePower\Sitemap\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
    protected $_redirectFactory;
    protected $_helper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \WebePower\Sitemap\Helper\Data $helper,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_redirectFactory = $redirectFactory;
        $this->_helper = $helper;
        return parent::__construct($context);
    }

    public function execute()
    {
        if($this->_helper->isEnable()){
            $resultPage = $this->_pageFactory->create();
            $resultPage->getConfig()->getTitle()->set($this->_helper->getConfig('sitemap_title'));
            $resultPage->getLayout()->getBlock('page.main.title')->setPageTitle($this->_helper->getConfig('sitemap_heading'));
            return $resultPage;            
        }
        return $this->_forward('noroute');
    }
}