<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\widgets;

use ron\craftrewards\CraftRewards;
use ron\craftrewards\assetbundles\craftrewardswidgetwidget\CraftRewardsWidgetWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * Craft Rewards Widget
 *
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class CraftRewardsWidget extends Widget
{

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $message = 'Hello, world.';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('craft-rewards', 'CraftRewardsWidget');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias("@ron/craftrewards/assetbundles/craftrewardswidgetwidget/dist/img/CraftRewardsWidget-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['message', 'string'],
                ['message', 'default', 'value' => 'Hello, world.'],
            ]
        );
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'craft-rewards/_components/widgets/CraftRewardsWidget_settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(CraftRewardsWidgetWidgetAsset::class);

        return Craft::$app->getView()->renderTemplate(
            'craft-rewards/_components/widgets/CraftRewardsWidget_body',
            [
                'message' => $this->message
            ]
        );
    }
}
