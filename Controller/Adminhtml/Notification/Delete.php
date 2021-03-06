<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   Magenerds
 * @package    Magenerds_WishlistNotification
 * @subpackage Controller
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Controller\Adminhtml\Notification;

use Magenerds\WishlistNotification\Model\NotificationFactory;

/**
 * Class Delete
 * @package Magenerds\WishlistNotification\Controller\Adminhtml\Notification
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenerds\WishlistNotification\Model\NotificationFactory
     */
    protected $_notificationFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param NotificationFactory $notificationFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        NotificationFactory $notificationFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->_notificationFactory = $notificationFactory;
        $this->_logger = $logger;
    }

    /**
     * Delete notification action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $notificationId = (int)$this->getRequest()->getParam('id');
        if ($notificationId) {
            try {
                /** @var $notification \Magenerds\WishlistNotification\Model\Notification */
                $notification = $this->_notificationFactory->create()->load($notificationId);
                $notification->delete();
                $this->messageManager->addSuccess(__('You deleted the notification.'));
            } catch (\Exception $e) {
                $this->_logger->critical($e);
                $this->messageManager->addError(__('Something went wrong while trying to delete the notification.'));
                return $resultRedirect->setPath('notification/*/', ['_current' => true]);
            }
        }
        return $resultRedirect->setPath('notification/*/');
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenerds_WishlistNotification::delete');
    }
}