<?php

namespace ImportCustomers\CustomCmd\Model;

use Magento\Framework\Model\AbstractModel;

class Data extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\ImportCustomers\CustomCmd\Model\ResourceModel\Data::class);
    }
}    