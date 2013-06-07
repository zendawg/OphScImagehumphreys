<?php

class m130528_120523_asset_file_relationship extends OEMigration {

  private $tempTable = "temp_m130528_113934_asset_file_relationship";

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->addColumn(FsScanHumphreyImage::model()->tableName(), 'asset_id', "int(10) unsigned default NULL");
    $this->addForeignKey(FsScanHumphreyImage::model()->tableName() . '_asset_id_fk', FsScanHumphreyImage::model()->tableName(), 'asset_id', Asset::model()->tableName(), 'id');
//    $this->addColumn(FsScanHumphreyXml::model()->tableName(), 'asset_id', "int(10) unsigned default NULL");
//    $this->addForeignKey(FsScanHumphreyXml::model()->tableName() . '_asset_id_fk', FsScanHumphreyXml::model()->tableName(), 'asset_id', Asset::model()->tableName(), 'id');
    $query = $this->dbConnection->createCommand('select file_id, asset_id from ' . $this->tempTable);
    $reader = $query->query();
    print_r(count($reader));
    foreach($reader as $row) {
      $this->update(FsScanHumphreyImage::model()->tableName(), array('asset_id' => $row['asset_id']), 'file_id=' . $row['file_id']);
      $this->update(Asset::model()->tableName(), array('file_id' => $row['file_id']), 'id=' . $row['asset_id']);
    }
  }

  public function safeDown() {
    $this->dropForeignKey(FsScanHumphreyImage::model()->tableName() . '_asset_id_fk', FsScanHumphreyImage::model()->tableName());
    $this->dropColumn(FsScanHumphreyImage::model()->tableName(), 'asset_id');
//    $this->dropForeignKey(FsScanHumphreyXml::model()->tableName() . '_asset_id_fk', FsScanHumphreyXml::model()->tableName());
//    $this->dropColumn(FsScanHumphreyXml::model()->tableName(), 'asset_id');
    return true;
  }

}