<?php
/**
 * Maryanov
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@maryanov.com so we can send you a copy immediately.
 *
 * @category   Maryanov
 * @package    Maryanov_Rahunok
 * @author     Maryanov Anton
 * @copyright  Copyright (c) 2009 Maryanov Anton (http://www.maryanov.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Maryanov_Rahunok_Model_Order extends Mage_Sales_Model_Order
{
    /**
     * Sending email with order data
     *
     * @return Mage_Sales_Model_Order
     */
    public function sendNewOrderEmail()
    {
        if (!Mage::helper('sales')->canSendNewOrderEmail($this->getStore()->getId())) {
            return $this;
        }

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
            ->setIsSecureMode(true);

        $paymentBlock->getMethod()->setStore($this->getStore()->getId());

        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $this->getStoreId());
        if ($copyTo && $copyMethod == 'bcc') {
            foreach ($copyTo as $email) {
                $mailTemplate->addBcc($email);
            }
        }

        if ($this->getCustomerIsGuest()) {
            $template = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $this->getStoreId());
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $template = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $this->getStoreId());
            $customerName = $this->getCustomerName();
        }

        $sendTo = array(
            array(
                'email' => $this->getCustomerEmail(),
                'name'  => $customerName
            )
        );
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $sendTo[] = array(
                    'email' => $email,
                    'name'  => null
                );
            }
        }
//Here is the attachments magic
        $at = null;
        if($this->canInvoice())
        {
            $rahunok = new Maryanov_Rahunok_Block_Template_Rahunok();
            $rahunok->setOrder($this);
            $at = new Zend_Mime_Part($rahunok->toHtml());
            $at->type        = 'text/html; charset=UTF-8';
            $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $at->encoding    = Zend_Mime::ENCODING_BASE64;
            $at->filename    = 'Rahunok-faktura_No_' . $this->getId() . '.html';
        }
//End here
        foreach ($sendTo as $recipient) {
            if(!is_null($at))//here we gonna add attachment
            {
                $mailTemplate->getMail()->addAttachment($at);
            }
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$this->getStoreId()))
                ->sendTransactional(
                    $template,
                    Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $this->getStoreId()),
                    $recipient['email'],
                    $recipient['name'],
                    array(
                        'order'         => $this,
                        'billing'       => $this->getBillingAddress(),
                        'payment_html'  => $paymentBlock->toHtml(),
                    )
                );
        }

        $translate->setTranslateInline(true);

        return $this;
    }
}
