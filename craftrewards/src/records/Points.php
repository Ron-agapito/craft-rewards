<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\records;

use ron\craftrewards\CraftRewards;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class Points extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%craftrewards_points}}';
    }
}
