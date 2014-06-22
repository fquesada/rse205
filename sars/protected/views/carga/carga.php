<?php
/* @var $this CargaController */
/* @var $mensaje Mensaje */

$this->breadcrumbs=array(
	'Carga',
);
?>

<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>

<?php echo CHtml::label('Indique si desea guardar las preguntas', 'preguntas', array ( )); ?>

<?php echo CHtml::checkBox('preguntas', false, array ( )); ?>

<?php echo '</br>'; ?>
<?php echo '</br>'; ?>

<?php echo CHtml::fileField('file', 'file', array ( )); ?>

<?php echo '</br>'; ?>
<?php echo '</br>'; ?>

<?php echo CHtml::submitButton('Cargar'); ?>

<?php echo CHtml::endForm(); ?>

<?php echo '</br>'; ?>
<p>
    <?php //echo $mensaje[0]; ?>    
</p>

