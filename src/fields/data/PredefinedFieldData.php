<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutfields\fields\data;

use Craft;
use craft\base\ElementInterface;
use Throwable;
use yii\base\Exception;

class PredefinedFieldData
{
    /**
     * The Field Format that will be used to render the Predefined value
     *
     * @var string
     */
    public string $fieldFormat;

    /**
     * The rendered value of the Predefined Field. This value is created when an Element is saved.
     *
     * @var string
     */
    public string $value;

    /**
     * The Element that will be used to render the Predefined Field
     *
     * @var ElementInterface
     */
    private ElementInterface $element;

    /**
     * Returns the value processed when the Predefined Field was saved by default
     *
     * @return string
     */
    public function __toString() : ?string
    {
        return $this->value ?? '';
    }

    /**
     * Sets the active Element if it is not set already
     *
     * @param ElementInterface|null $element
     */
    public function setElement(ElementInterface|null $element = null)
    {
        // Only set the Element once
        if (!$this->element) {
            $this->element = $element;
        }
    }

    /**
     * Process the Predefined Value, right now.
     *
     * This method does not grab the saved Pre-defined data and instead
     * returns the Predefined value rendered with the most recent data
     * when we render the template.
     *
     * @return mixed
     * @throws Throwable
     * @throws Exception
     */
    public function getHtml() : mixed
    {
        return Craft::$app->view->renderObjectTemplate($this->fieldFormat, $this->element);
    }
}