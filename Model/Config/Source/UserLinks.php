<?php
namespace WebePower\Sitemap\Model\Config\Source;

class UserLinks implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'login', 'label' => __('Login')],
            ['value' => 'create', 'label' => __('Register')],
            ['value' => 'forgotpassword', 'label' => __('Forget Password')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [['login' => __('Login')], ['create' => __('Register')], ['forgotpassword' => __('Forget Password')]];
    }
}