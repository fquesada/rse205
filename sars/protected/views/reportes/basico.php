<?php
/* @var $this ReportesController */
/* @var $modelo_ano Ano */
/* @var $modelo_cooperativas Cooperativas */


$this->breadcrumbs=array(
        'Reportes'=>array('reportes/index'),
	'Reporte por Cooperativa',
);
?>

<div id="selecciondatos" class="selecciondatos">
    
    <?php echo CHtml::form('','post', array('class' => 'bootstrap-frm')); ?>
    
    <h1>Reporte por Cooperativa
        <span>Materias y SubMaterias</span>
        <span>Seleccione las siguientes opciones para generar el reporte.</span>
    </h1>
    
    <label>
        <span>Seleccione la Cooperativa:</span>
        <?php echo CHtml::dropDownList('ddl_cooperativa', 'ddl_cooperativa', CHtml::listData($modelo_cooperativas,'idcooperativa', 'cooperativa'), array()) ?>
    </label>
    
    <label>
        <span>Seleccione el año:</span>
        <?php echo CHtml::checkBoxList('chbxl_ano', 'chbxl_ano', CHtml::listData($modelo_ano,'id', 'ano'), array('class' => 'chbxl', 'id' => 'chbxl_ano')) ?>
    </label>  

   <label>
        <span>Incluir SubMaterias en el reporte:</span>
       <?php echo CHtml::checkBox('chbx_submaterias', true, array ('id' => 'chbx_submaterias')); ?>
    </label>  
      
    
    <?php echo CHtml::submitButton('Generar Reporte', array('class' => 'button', 'id' => 'btn_generarbasico')); ?>

    <?php echo CHtml::endForm(); ?>
</div>

<div id="reporte" class="reportebasico">
    
</div>


