<?php

namespace barrelstrength\sproutfields;

use barrelstrength\sproutbase\base\SproutDependencyInterface;
use barrelstrength\sproutbase\base\SproutDependencyTrait;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutbasefields\SproutBaseFieldsHelper;
use barrelstrength\sproutfields\fields\Address as AddressField;
use barrelstrength\sproutfields\fields\Email as EmailField;
use barrelstrength\sproutfields\fields\Gender as GenderField;
use barrelstrength\sproutfields\fields\Name as NameField;
use barrelstrength\sproutfields\fields\Notes as NotesField;
use barrelstrength\sproutfields\fields\Phone as PhoneField;
use barrelstrength\sproutfields\fields\Predefined as PredefinedField;
use barrelstrength\sproutfields\fields\PredefinedDate as PredefinedDateField;
use barrelstrength\sproutfields\fields\RegularExpression as RegularExpressionField;
use barrelstrength\sproutfields\fields\Template as TemplateField;
use barrelstrength\sproutfields\fields\Url as UrlField;
use craft\base\Element;
use craft\base\Plugin;
use craft\events\ElementEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Elements;
use craft\services\Fields;
use yii\base\Event;

/**
 * @property array $sproutDependencies
 * @property array $settings
 */
class SproutFields extends Plugin implements SproutDependencyInterface
{
    use SproutDependencyTrait;

    /**
     * @var string
     */
    public $schemaVersion = '3.5.3';

    /**
     * @var string
     */
    public $minVersionRequired = '2.1.3';

    /**
     * @inheritdoc
     * @deprecated - Remove in v4.0
     * This empty method is required to avoid an error related to the Project Config when migrating from Craft 2 to Craft 3
     */
    public function setSettings(array $settings)
    {
    }

    /**
     * @inheritdoc
     * @deprecated - Remove in v4.0
     */
    public function getSettings()
    {
        return null;
    }

    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();
        SproutBaseFieldsHelper::registerModule();

        // Process all of our Predefined Fields after an Element is saved
        Event::on(Elements::class, Elements::EVENT_AFTER_SAVE_ELEMENT, static function(ElementEvent $event) {
            /** @var Element $element */
            $element = $event->element;
            $isNew = $event->isNew;

            $fieldLayout = $element->getFieldLayout();

            if ($fieldLayout) {
                foreach ($fieldLayout->getFields() as $field) {
                    if ($field instanceof PredefinedField || $field instanceof PredefinedDateField) {
                        /** @var PredefinedField $field */
                        $field->processFieldValues($element, $isNew);
                    }
                }
            }
        });

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, static function(RegisterComponentTypesEvent $event) {
            $event->types[] = AddressField::class;
            $event->types[] = EmailField::class;
            $event->types[] = GenderField::class;
            $event->types[] = NameField::class;
            $event->types[] = NotesField::class;
            $event->types[] = PhoneField::class;
            $event->types[] = PredefinedField::class;
            $event->types[] = PredefinedDateField::class;
            $event->types[] = RegularExpressionField::class;
            $event->types[] = TemplateField::class;
            $event->types[] = UrlField::class;
        });
    }

    /**
     * @return array
     */
    public function getSproutDependencies(): array
    {
        return [
            SproutDependencyInterface::SPROUT_BASE,
            SproutDependencyInterface::SPROUT_BASE_FIELDS
        ];
    }
}


