<?php

namespace barrelstrength\sproutfields\fields;

use barrelstrength\sproutfields\SproutFields;
use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use yii\db\Schema;

class Hidden extends Field implements PreviewableFieldInterface
{
	/**
	 * @var bool
	 */
	public $allowEdits = 0;

	/**
	 * @var string|null The maximum allowed number
	 */
	public $value;

	public static function displayName(): string
	{
		return SproutFields::t('Hidden');
	}

	/**
	 * @inheritdoc
	 */
	public function getContentColumnType(): string
	{
		return Schema::TYPE_STRING;
	}

	/**
	 * @inheritdoc
	 */
	public function getSettingsHtml()
	{
		return Craft::$app->getView()->renderTemplate('sprout-fields/_fieldtypes/hidden/settings',
			[
				'field' => $this,
			]);
	}

	/**
	 * @inheritdoc
	 */
	public function getInputHtml($value, ElementInterface $element = null): string
	{
		return Craft::$app->getView()->renderTemplate('sprout-core/sproutfields/fields/hidden/input',
			[
				'id'    => $this->handle,
				'name'  => $this->handle,
				'value' => $value,
				'field' => $this
			]);
	}
}
