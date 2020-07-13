<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\controllers;

use Craft;
use craft\web\Controller;
use ron\craftrewards\CraftRewards;


/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */

    public function actionAddPoints()
    {
        $id = Craft::$app->getRequest()->getParam('customerId', null);
        $points = Craft::$app->getRequest()->getParam('points', null);
        $type  = Craft::$app->getRequest()->getParam('type', null);
        if($type ==  "remove") $points = $points * -1;

        CraftRewards::$plugin->service->addUserPoints($id, $points);
        $this->requireCpRequest();
    }

    public function actionIndex(): Response
    {

    }

}
