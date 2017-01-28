<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Tests
 * @package     Tests_Functional
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Mage\Adminhtml\Test\Block\Sales\CreditMemos;

use Mage\Adminhtml\Test\Block\Sales\AbstractGrid;

/**
 * Sales credit memos grid.
 */
class Grid extends AbstractGrid
{
    /**
     * Products table identifier.
     *
     * @var string
     */
    protected $tableIdentifier = '@id="order_creditmemos_table"';

    /**
     * Selector for title id column.
     *
     * @var array
     */
    protected $idColumnName = '.="Credit Memo #"';

    /**
     * Base part of row locator template for getRow() method.
     *
     * @var string
     */
    protected $location = './/div[@class="grid"]//tr[';

    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'id' => [
            'selector' => 'input[name="increment_id"]',
        ],
        'order_id' => [
            'selector' => 'input[name="order_increment_id"]',
        ],
        'from' => [
            'selector' => 'input[name*="grand_total[from]"]',
        ],
        'to' => [
            'selector' => 'input[name*="grand_total[to]"]',
        ],
    ];
}
