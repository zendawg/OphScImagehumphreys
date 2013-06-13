<?php

class m130612_193837_study_date_time_column_type extends CDbMigration {

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_date', 'date');
    alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_time', 'time');
  }

  public function safeDown() {
    alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_date', 'varchar(10)');
    alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_time', 'varchar(12)');
  }

}