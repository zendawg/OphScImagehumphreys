<?php

/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "et_ophscimagehumphreys_document".
 *
 * The followings are the available columns in table:
 * @property string $id
 * @property integer $event_id
 * @property integer $asset_id
 * @property string $title
 * @property string $description
 *
 * The followings are the available model relations:
 *
 * @property ElementType $element_type
 * @property EventType $eventType
 * @property Event $event
 * @property User $user
 * @property User $usermodified
 * @property Asset $asset
 */
class Element_OphScImagehumphreys_Document extends ElementScannedDocument {

  public $service;

  /**
   * Returns the static model of the specified AR class.
   * @return the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'et_ophscimagehumphreys_document';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('event_id, asset_id, title, description, ', 'safe'),
        array('asset_id, title, description, ', 'required'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('id, event_id, asset_id, title, description, ', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'element_type' => array(self::HAS_ONE, 'ElementType', 'id', 'on' => "element_type.class_name='" . get_class($this) . "'"),
        'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
        'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
        'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
        'asset' => array(self::BELONGS_TO, 'Asset', 'asset_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'id' => 'ID',
        'event_id' => 'Event',
        'asset_id' => 'Asset',
        'title' => 'title',
        'description' => 'description',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;

    $criteria->compare('id', $this->id, true);
    $criteria->compare('event_id', $this->event_id, true);
    $criteria->compare('asset_id', $this->asset_id);
    $criteria->compare('title', $this->title);
    $criteria->compare('description', $this->description);

    return new CActiveDataProvider(get_class($this), array(
                'criteria' => $criteria,
            ));
  }

  /**
   * This method uses reflection to load the module specified by the image
   * type.
   * 
   * @param string $imageType the type of image to use; this is important
   * and is used in the reflective part to load a module named
   * OphScImage[imageType].
   * 
   * @param int $assetId the asset to obtain.
   * 
   * @return null
   */
  public function getScannedDocument($patient_id, $assetId, $params) {
    $eye = 'L';
    if ($params) {
      $eye = $params['eye'];
    }
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = FsFile::model()->tableName() . '.asset_id=' . $assetId;
    $exam_criteria->join =
            ' left join ' . FsFile::model()->tableName()
            . ' on ' . 'file_id=' . FsFile::model()->tableName()
            . '.id'
    ;
    try {
      $f = $exam_criteria->condition;
      $r = $exam_criteria->join;
      $data = FsScanHumphreyImage::model()->find($exam_criteria);
    } catch (Exception $e) {
      $foo = $e;
    }
    return $data;
  }

  /**
   * 
   * @param type $imageType
   */
  public static function getScannedDocuments($pid, $params) {
    $condition =  'pid=\'' . $pid . '\'';
    if ($params) {
      $eye = $params['eye'];
      $strategy = $params['strategy'];
      if ($strategy) {
        $condition = $condition . ' and test_strategy=\'' . $strategy . '\'';
      }
    } else {
      // set some defaults:
      $eye = 'L';
    }
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = $condition . ' and eye=\'' . $eye . '\'';
    return FsScanHumphreyXml::model()->findAll($exam_criteria);
  }

}
