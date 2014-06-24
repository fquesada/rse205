<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Iniciar Sesion';
$this->breadcrumbs=array(
	'Iniciar sesión',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
        'htmlOptions'=>array(
            'class'=>'frm_login',
        ),
)); ?>

        <h1>Iniciar Sesión</h1>

        <p>Ingrese sus credenciales para iniciar sesión:</p>

	<label>
		<span>Usuario:</span>
		<?php echo $form->textField($model,'username'); ?>
		<?php // echo $form->error($model,'username'); ?>
	</label>

	<label>
                <span>Contraseña:</span>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php // echo $form->error($model,'password'); ?>
        </label>
	
        <label>
            <span><?php echo $form->error($model,'username'); ?></span>          
            <span><?php echo $form->error($model,'password'); ?></span>
        </label>
        
	<?php echo CHtml::submitButton('Iniciar Sesión', array('class'=>'btn_login')); ?>
	

<?php $this->endWidget(); ?>