<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutfields\migrations;

use craft\db\Migration;
use craft\db\Query;
use craft\helpers\Json;

/**
 * m180221_161523_phone_fields migration.
 */
class m180221_161523_phone_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Phone - Update settings and Type
        $newSettings = [
            'placeholder' => '',
            'multiline' => '',
            'initialRows' => '4',
            'charLimit' => '255',
            'columnType' => 'string'
        ];

        $phoneFields = (new Query())
            ->select(['id', 'handle', 'settings'])
            ->from(['{{%fields}}'])
            ->where(['type' => 'SproutFields_Phone', 'context' => 'global'])
            ->all();

        foreach ($phoneFields as $phoneField) {
            $settingsAsJson = Json::encode($newSettings);
            $this->update('{{%fields}}', [
                'type' => 'craft\fields\PlainText',
                'settings' => $settingsAsJson
            ], [
                'id' => $phoneField['id']
            ], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180221_161523_phone_fields cannot be reverted.\n";

        return false;
    }
}
