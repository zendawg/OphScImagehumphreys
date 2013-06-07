<?php

class m130520_110936_add_associated_field extends OEMigration {

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {

    $this->addColumn('ophscimagehumphreys_scan_humphrey_image', 'associated', "tinyint(1) DEFAULT 0");
    $this->addColumn('ophscimagehumphreys_scan_humphrey_image', 'xml_id', "int(10) unsigned DEFAULT NULL");
    $this->createIndex('ophscimagehumphreys_scan_humphrey_image_xml_id_fk', 'ophscimagehumphreys_scan_humphrey_image', 'xml_id');
    $this->addForeignKey('ophscimagehumphreys_scan_humphrey_image_xml_id_fk', 'ophscimagehumphreys_scan_humphrey_image', 'xml_id', 'ophscimagehumphreys_scan_humphrey_xml', 'id');
    $this->addColumn('ophscimagehumphreys_scan_humphrey_xml', 'associated', "tinyint(1) DEFAULT 0");
  }

  public function safeDown() {
    $this->dropForeignKey('ophscimagehumphreys_scan_humphrey_image_xml_id_fk', 'ophscimagehumphreys_scan_humphrey_image');
    $this->dropIndex('ophscimagehumphreys_scan_humphrey_image_xml_id_fk', 'ophscimagehumphreys_scan_humphrey_image');
    $this->dropColumn('ophscimagehumphreys_scan_humphrey_image', 'associated');
    $this->dropColumn('ophscimagehumphreys_scan_humphrey_image', 'xml_id');
    $this->dropColumn('ophscimagehumphreys_scan_humphrey_xml', 'associated');
  }

}