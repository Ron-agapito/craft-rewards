<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\variables;

use ron\craftrewards\CraftRewards;
use craft\commerce;
use craft\commerce\elements\Order;
use craft\commerce\Plugin;


use Craft;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class CraftRewardsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function showMyPoints($optional = null)
    {
        $id = Craft::$app->getUser()->getId();
 if(!$id)
     return;

        return CraftRewards::$plugin->service->getUserPoints($id);

    }

    public function getAllPoints($optional = null)
    {
        $id = Craft::$app->getUser()->getId();
        if(!$id)
            return;

        return CraftRewards::$plugin->service->getAllPoints();

    }
    public function getTransactionPoints()
    {
        $id = Craft::$app->getUser()->getId();
        if(!$id)
            return;
      $total =  craft\commerce\Plugin::getInstance()->getCarts()->getCart()->getItemSubtotal();
      return CraftRewards::$plugin->service->computePoints($total);

    }

}
