<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutfields\migrations;

use craft\db\Migration;

class m180221_161527_email_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Email
        $this->update('{{%fields}}', [
            'type' => 'barrelstrength\sproutfields\fields\Email'
        ], [
            'type' => 'SproutFields_Email', 'context' => 'global'
        ], [], false);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180221_161527_email_fields cannot be reverted.\n";

        return false;
    }
}
