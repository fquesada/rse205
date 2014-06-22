<?php

/**
 * This is the model class for table "participante_evaluacion".
 *
 * The followings are the available columns in table 'participante_evaluacion':
 * @property integer $idparticipante
 * @property string $token
 * @property string $nombre
 * @property string $apellidos
 * @property string $idcooperativa
 * @property string $puesto
 * @property integer $idevaluacion
 */
class ParticipanteEvaluacion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'participante_evaluacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idparticipante', 'required'),
			array('idparticipante, idevaluacion', 'numerical', 'integerOnly'=>true),
			array('token, idcooperativa', 'length', 'max'=>50),
			array('nombre, apellidos, puesto', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idparticipante, token, nombre, apellidos, idcooperativa, puesto, idevaluacion', 'safe', 'on'=>'search'),
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
			'idparticipante' => 'Idparticipante',
			'token' => 'Token',
			'nombre' => 'Nombre',
			'apellidos' => 'Apellidos',
			'idcooperativa' => 'Idcooperativa',
			'puesto' => 'Puesto',
			'idevaluacion' => 'Idevaluacion',
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

		$criteria->compare('idparticipante',$this->idparticipante);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('apellidos',$this->apellidos,true);
		$criteria->compare('idcooperativa',$this->idcooperativa,true);
		$criteria->compare('puesto',$this->puesto,true);
		$criteria->compare('idevaluacion',$this->idevaluacion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ParticipanteEvaluacion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
