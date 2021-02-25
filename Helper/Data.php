<?php 
namespace WebePower\Sitemap\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $scopeConfig;

	public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnable()
    {
    	if($this->getConfig('enabled')==1)
        {
            if($this->getConfig('category')==1 || $this->getConfig('cms_page')==1 || $this->getConfig('product')==1)
            {
                return true;
            }
        }
        return false;
    }

    public function getUserLinks()
    {
        $allLinks = [
            ['value' => 'login', 'label' => __('Login')],
            ['value' => 'create', 'label' => __('Register')],
            ['value' => 'forgotpassword', 'label' => __('Forget Password')]
        ];
        $userLinks = [];
        $links = explode(',', $this->getConfig('user_links'));
        if(count($links)>0){
            foreach ($allLinks as $lnk) {
                if(in_array($lnk['value'], $links)){
                    $userLinks[$lnk['value']] = $lnk['label'];
                }
            }
        }
        return $userLinks;
    }

    public function getOtherLinks()
    {
        $otherLinks = '';
        $links = explode(',', $this->getConfig('other_links'));
        if(count($links)>0){
            foreach ($links as $lnk) {
                $eachLinks = explode('@', $lnk);
                $otherLinks .= '<li><a href="'.$eachLinks[1].'">'.$eachLinks[0].'</a></li>';
            }
        }
        return $otherLinks;
    }

    public function isCategoryProduct()
    {
        return ($this->getConfig('cat_product')==1);
    }

    public function getConfig($path)
    {
    	return $this->scopeConfig->getValue('html_sitemap/option/'.$path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}