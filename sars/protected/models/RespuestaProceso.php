<?php

/**
 * This is the model class for table "respuesta_proceso".
 *
 * The followings are the available columns in table 'respuesta_proceso':
 * @property integer $idrespuesta_proceso
 * @property integer $idpregunta
 * @property string $respuesta
 * @property integer $valor
 */
class RespuestaProceso extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'respuesta_proceso';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idrespuesta_proceso', 'required'),
			array('idrespuesta_proceso, idpregunta, valor', 'numerical', 'integerOnly'=>true),
			array('respuesta', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idrespuesta_proceso, idpregunta, respuesta, valor', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idrespuesta_proceso' => 'Idrespuesta Proceso',
			'idpregunta' => 'Idpregunta',
			'respuesta' => 'Respuesta',
			'valor' => 'Valor',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('idrespuesta_proceso',$this->idrespuesta_proceso);
		$criteria->compare('idpregunta',$this->idpregunta);
		$criteria->compare('respuesta',$this->respuesta,true);
		$criteria->compare('valor',$this->valor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RespuestaProceso the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
