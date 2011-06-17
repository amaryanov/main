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

class Maryanov_Rahunok_PrintController extends Mage_Core_Controller_Front_Action
{
	protected $order = null;
    /**
     * Action predispatch
     *
     * Check customer authentication for some actions
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
	public function invoiceAction()
	{
		if (!$this->_loadValidOrder()) {
			return;
		}
/*		$id = (int) $this->getRequest()->getParam('id');
		$order = Mage::getModel('sales/order')->load($id);*/
		$this->loadLayout();
		if($this->order->canInvoice())
		{
			if($b = $this->getLayout()->getBlock('maryanov.rahunok'))
			{
				$b->setOrder($this->order);
			}
		}
		$this->renderLayout();
	}
	/**
	 * Try to load valid order by order_id and register it
	 *
	 * @param int $orderId
	 * @return bool
	 */
	protected function _loadValidOrder($orderId = null)
	{
		if (null === $orderId)
		{
			$orderId = (int) $this->getRequest()->getParam('id');
		}
		if (!$orderId)
		{
			$this->_forward('noRoute');
			return false;
		}
		$order = Mage::getModel('sales/order')->load($orderId);
		if ($this->_canViewOrder($order))
		{
			$this->order = $order;
			return true;
		}
		else
		{
			$this->_forward('noRoute');
		}
		return false;
	}
			/**
			 * Check order view availability
			 *
			 * @param   Mage_Sales_Model_Order $order
			 * @return  bool
			 */
			protected function _canViewOrder($order)
			{
				$customerId = Mage::getSingleton('customer/session')->getCustomerId();
				$availableStates = Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates();
				if ($order->getId() && $order->getCustomerId() && ($order->getCustomerId() == $customerId) && in_array($order->getState(), $availableStates, $strict = true))
				{
					return true;
				}
				return false;
			}
}
