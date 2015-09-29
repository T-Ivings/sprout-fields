<?php
namespace Craft;

class SproutFields_EmailFieldType extends BaseFieldType
{
	/**
	 * Fieldtype name
	 *
	 * @return string
	 */
	public function getName()
	{
		return Craft::t('Email Address');
	}

	/**
	 * Define database column
	 *
	 * @return AttributeType::String
	 */
	public function defineContentAttribute()
	{
		return array(AttributeType::String);
	}

	/**
	 * Define settings
	 *
	 * @return bool  Validate unique email addresses?
	 */
	protected function defineSettings()
	{
		return array(
			'customPattern' => array(AttributeType::String),
			'customPatternErrorMessage' => array(AttributeType::String),
			'uniqueEmail' => array(AttributeType::Bool, 'default' => false),
		);
	}

	/**
	 * Display our settings
	 *
	 * @return string Returns the template which displays our settings
	 */
	public function getSettingsHtml()
	{
		$settings = $this->getSettings();      

		return craft()->templates->render('sproutfields/_fields/emailfield/settings', array(
			'settings' => $settings
		));
	}

	/**
	 * Display our fieldtype
	 *
	 * @param string $name  Our fieldtype handle
	 * @return string Return our fields input template
	 */
	public function getInputHtml($name, $value)
	{       
		$inputId = craft()->templates->formatInputId($name);
		$namespaceInputId = craft()->templates->namespaceInputId($inputId);

		return craft()->templates->render('sproutfields/_fields/emailfield/input', array(
			'id' => $namespaceInputId,
			'name'  => $name,
			'value' => $value
		));
	}

	/**
	 * Validates our fields submitted value beyond the checks 
	 * that were assumed based on the content attribute.
	 *
	 * Returns 'true' or any custom validation errors.
	 *
	 * @param array $value
	 * @return true|string|array
	 */
	public function validate($value)
	{
		return craft()->sproutFields_emailField->validate($value, $this->element, $this->model);
	}
}
