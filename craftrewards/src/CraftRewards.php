<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards;

use Craft;
use craft\base\Component;
use craft\base\Plugin;
use craft\commerce\base\AdjusterInterface;
use craft\commerce\elements\Order;
use craft\commerce\models\OrderAdjustment;
use craft\commerce\services\OrderAdjustments;
use craft\db\Query;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Dashboard;
use craft\services\Elements;
use craft\services\Plugins;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use ron\craftrewards\models\Settings;
use ron\craftrewards\services\CraftRewardsService as CraftRewardsServiceService;
use ron\craftrewards\twigextensions\CraftRewardsTwigExtension;
use ron\craftrewards\variables\CraftRewardsVariable;
use ron\craftrewards\widgets\CraftRewardsWidget as CraftRewardsWidgetWidget;
use yii\base\Event;


/**
 * Class CraftRewards
 *
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 *
 * @property  CraftRewardsServiceService $craftRewardsService
 */
class CraftRewards extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CraftRewards
     */
    public static $plugin;

    public static $amountPerPoint = 100;
    public static $currentPoints = 0;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setComponents([
            'service' => CraftRewardsServiceService::class,
        ]);
        $this->_registerTemplateHooks();
        self::$plugin = $this;
        $uid = Craft::$app->getUser()->getId();
        if ($uid)
            self::$currentPoints = $this->service->getUserPoints($uid);

        if (self::$currentPoints > 0) {
            Event::on(
                OrderAdjustments::class,
                OrderAdjustments::EVENT_REGISTER_ORDER_ADJUSTERS,
                function (RegisterComponentTypesEvent $event) {
                    $event->types[] = RewardsAdjuster::class;
                }
            );

        }



        Event::on(
            Order::class,
            Order::EVENT_AFTER_ORDER_PAID,
            function (Event $event) {
                $uid = Craft::$app->getUser()->getId();

                $amount = $event->sender->storedItemTotal;
                $earnedPoints = CraftRewards::$plugin->service->computePoints($amount);
                CraftRewards::$plugin->service->redeemPoints($uid);
                CraftRewards::$plugin->service->addUserPoints($uid, $earnedPoints);


            }
        );


        Craft::$app->view->registerTwigExtension(new CraftRewardsTwigExtension());
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = 'craft-rewards/default';

            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'craft-rewards/default/do-something';
            }
        );

        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function (RegisterComponentTypesEvent $event) {

            }
        );

        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = CraftRewardsWidgetWidget::class;

            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('craftRewards', CraftRewardsVariable::class);

            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'craft-rewards',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }






    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'craft-rewards/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }


    private function _registerTemplateHooks()
    {
           Craft::$app->getView()->hook('cp.users.edit', [$this->service, 'addEditUserRewardsTab']);
            Craft::$app->getView()->hook('cp.users.edit.content', [$this->service, 'addEditUserRewardsTabContent']);

    }

}

class RewardsAdjuster extends Component implements AdjusterInterface
{

    public function adjust(Order $order): array
    {
        $adjustments = [];

        $adjustment = new OrderAdjustment;
        $adjustment->type = 'Rewards';
        $adjustment->name = "$" . CraftRewards::$currentPoints . " off";
        $adjustment->description = '$' . CraftRewards::$currentPoints . ' off from Loyalty Points';
        $adjustment->sourceSnapshot = ['data' => 'value']; // This can contain information about how the adjustment came to be
        $adjustment->amount = -CraftRewards::$currentPoints;
        $adjustment->setOrder($order);
        $adjustments[] = $adjustment;


        return $adjustments;
    }
} 
