<?php

namespace barrelstrength\sproutfields\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\fields\BaseOptionsField;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\ArrayHelper;

use barrelstrength\sproutfields\SproutFields;
use barrelstrength\sproutfields\assetbundles\emailfield\EmailFieldAsset;

class EmailSelect extends BaseOptionsField
{
	public static function displayName(): string
	{
		return Craft::t('sproutFields', 'Email Select');
	}

	/**
	 * @inheritdoc
	 */
	public function getContentColumnType(): string
	{
		return Schema::TYPE_STRING;
	}

	/**
	 * @return string
	 */
	protected function optionsSettingLabel(): string
	{
		return Craft::t('sproutFields', 'Dropdown Options');
	}

	/**
	 * @inheritdoc
	 */
	public function getSettingsHtml()
	{
		$options = $this->options;

		if (!$options)
		{
			$options = [['label' => '', 'value' => '']];
		}

		return Craft::$app->getView()->renderTemplateMacro('_includes/forms',
			'editableTableField',
			[
				[
					'label'        => $this->optionsSettingLabel(),
					'instructions' => Craft::t('sproutFields', 'Define the available options.'),
					'id'           => 'options',
					'name'         => 'options',
					'addRowLabel'  => Craft::t('sproutFields', 'Add an option'),
					'cols'         => [
						'label'   => [
							'heading'      => Craft::t('sproutFields', 'Name'),
							'type'         => 'singleline',
							'autopopulate' => 'value'
						],
						'value'   => [
							'heading' => Craft::t('sproutFields', 'Email'),
							'type'    => 'singleline',
							'class'   => 'code'
						],
						'default' => [
							'heading' => Craft::t('sproutFields', 'Default?'),
							'type'    => 'checkbox',
							'class'   => 'thin'
						],
					],
					'rows' => $options
				]
			]
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getInputHtml($value, ElementInterface $element = null): string
	{
		$valueOptions = $value->getOptions();
		$anySelected  = SproutFields::$api->utilities->isAnyOptionsSelected(
			$valueOptions, 
			$value->value
		);

		$name  = $this->handle;
		$value = $value->value;

		if ($anySelected === false)
		{
			$value = $this->defaultValue();
		}

		$options = $this->options;

		return Craft::$app->getView()->renderTemplate('sproutfields/_fieldtypes/emailselect/input',
			[
				'name'    => $name,
				'value'   => $value,
				'options' => $options
			]
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getElementValidationRules(): array
	{
		$rules = parent::getElementValidationRules();
		$rules[] = 'validateEmailSelect';

		return $rules;
	}

	/**
	 * Validates our fields submitted value beyond the checks
	 * that were assumed based on the content attribute.
	 *
	 *
	 * @param ElementInterface $element
	 *
	 * @return void
	 */
	public function validateEmailSelect(ElementInterface $element)
	{
		$value = $element->getFieldValue($this->handle)->value;

		$emailAddresses = ArrayHelper::toArray($value);

		$emailAddresses = array_unique($emailAddresses);

		if (count($emailAddresses))
		{
			$invalidEmails = array();
			foreach ($emailAddresses as $emailAddress)
			{
				if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL))
				{
					$invalidEmails[] = Craft::t(
						'sproutFields',
						$emailAddress . " email does not validate"
					);
				}
			}
		}

		if (!empty($invalidEmails))
		{
			foreach ($invalidEmails as $invalidEmail) 
			{
				$element->addError($this->handle, $invalidEmail, $this);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getTableAttributeHtml($value, ElementInterface $element)
	{
		$html = '';

		if ($value) 
		{
			$html = $value->label . ': <a href="mailto:' . $value . '" target="_blank">' . $value . '</a>';
		}

		return $html;
	}
}