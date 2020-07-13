<?php
/**
 * Craft Rewards plugin for Craft CMS 3.x
 *
 * Craft Commerce loyalty plugin
 *
 * @link      ron.agapito@icloud.com
 * @copyright Copyright (c) 2020 ron.agapito
 */

namespace ron\craftrewards\elements;

use ron\craftrewards\CraftRewards;

use Craft;
use craft\base\Element;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;

/**
 * @author    ron.agapito
 * @package   CraftRewards
 * @since     1
 */
class Points extends Element
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
      #  return Craft::t('craft-rewards', '');
      return "Points"
    }

    /**
     * @inheritdoc
     */
    public static function hasContent(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function hasTitles(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function isLocalized(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function find(): ElementQueryInterface
    {
        return new ElementQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    protected static function defineSources(string $context = null): array
    {
        $sources = [];

        return $sources;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getIsEditable(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getFieldLayout()
    {
        $tagGroup = $this->getGroup();

        if ($tagGroup) {
            return $tagGroup->getFieldLayout();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getGroup()
    {
        if ($this->groupId === null) {
            throw new InvalidConfigException('Tag is missing its group ID');
        }

        if (($group = Craft::$app->getTags()->getTagGroupById($this->groupId)) === null) {
            throw new InvalidConfigException('Invalid tag group ID: '.$this->groupId);
        }

        return $group;
    }

    // Indexes, etc.
    // -------------------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function getEditorHtml(): string
    {
        $html = Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'textField', [
            [
                'label' => Craft::t('app', 'Title'),
                'siteId' => $this->siteId,
                'id' => 'title',
                'name' => 'title',
                'value' => $this->title,
                'errors' => $this->getErrors('title'),
                'first' => true,
                'autofocus' => true,
                'required' => true
            ]
        ]);

        $html .= parent::getEditorHtml();

        return $html;
    }

    // Events
    // -------------------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function beforeSave(bool $isNew): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave(bool $isNew)
    {
      
    if ($isNew) {
        \Craft::$app->db->createCommand()
            ->insert('{{%points}}', [
                'id' => $this->id,
                'uid' => $this->uid,
                'dateLastPurchased' => new DateTime('NOW')
            ])
            ->execute();
    } else {
        \Craft::$app->db->createCommand()
            ->update('{{%products}}', [
                'points' => $this->points,
                'dateLastPurchased' => new DateTime('NOW')
            ], ['uid' => $this->uid])
            ->execute();
    }

    parent::afterSave($isNew);
    
      
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
    }

    /**
     * @inheritdoc
     */
    public function beforeMoveInStructure(int $structureId): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterMoveInStructure(int $structureId)
    {
    }
}
