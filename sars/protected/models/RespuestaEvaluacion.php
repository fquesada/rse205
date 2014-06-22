<?php

/**
 * This is the model class for table "respuesta_evaluacion".
 *
 * The followings are the available columns in table 'respuesta_evaluacion':
 * @property integer $idrespuesta_evaluacion
 * @property integer $idparticipante
 * @property integer $idpregunta
 * @property integer $idrespuesta
 */
class RespuestaEvaluacion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'respuesta_evaluacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idrespuesta_evaluacion', 'required'),
			array('idrespuesta_evaluacion, idparticipante, idpregunta, idrespuesta', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idrespuesta_evaluacion, idparticipante, idpregunta, idrespuesta', 'safe', 'on'=>'search'),
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
			'idrespuesta_evaluacion' => 'Idrespuesta Evaluacion',
			'idparticipante' => 'Idparticipante',
			'idpregunta' => 'Idpregunta',
			'idrespuesta' => 'Idrespuesta',
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

		$criteria->compare('idrespuesta_evaluacion',$this->idrespuesta_evaluacion);
		$criteria->compare('idparticipante',$this->idparticipante);
		$criteria->compare('idpregunta',$this->idpregunta);
		$criteria->compare('idrespuesta',$this->idrespuesta);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RespuestaEvaluacion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
