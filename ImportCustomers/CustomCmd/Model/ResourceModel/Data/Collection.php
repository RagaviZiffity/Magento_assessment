<?php
namespace ImportCustomers\CustomCmd\Model\ResourceModel\Data;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \ImportCustomers\CustomCmd\Model\Data::class,
            \ImportCustomers\CustomCmd\Model\ResourceModel\Data::class
        );
    }
}
