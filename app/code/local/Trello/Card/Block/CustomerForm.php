<?php
    class Trello_Card_CustomerForm extends Mage_Core_Block_Template
    {
        public function getActionOfForm()
        {
            return $this->getUrl('customer-form/index/submitForm');
        }
    }
?>