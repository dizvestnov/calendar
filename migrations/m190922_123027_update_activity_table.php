<?php

use yii\db\Migration;

/**
 * Class m190922_123027_update_activity_table
 */
class m190922_123027_update_activity_table extends Migration
{
   public function up()
   {
       $this->addColumn('activity', 'created_at', $this->integer());
       $this->addColumn('activity', 'updated_at', $this->integer());
   }

   public function down()
   {
       $this->dropColumn('activity', 'created_at');
       $this->dropColumn('activity', 'updated_at');
   }
}
