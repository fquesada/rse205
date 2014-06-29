<?php
/* @var $this ReportesController */
/* @var $modelo_ano Ano */
/* @var $modelo_cooperativas Cooperativas */
/* @var $modelo_materia Materia */

$this->breadcrumbs=array(
        'Reportes'=>array('reportes/index'),
	'Reporte de Cruces de Información',
);
?>

<div id="selecciondatos" class="selecciondatos">
    
    <?php echo CHtml::link('Volver al menú de Reportes',array('reportes/index')); ?>
    
    <?php echo CHtml::form('','post', array('class' => 'frm_crucevariables')); ?>
    
    <h1>Reporte de Cruces de Información
        <span>Materias y SubMaterias</span>
        <span>Seleccione las siguientes opciones para generar el reporte.</span>
    </h1>
    
    <label>
        <span>Seleccione la Cooperativa:</span>
        <?php echo CHtml::checkBoxList('chbxl_cooperativa', 'chbxl_cooperativa', CHtml::listData($modelo_cooperativas,'idcooperativa', 'cooperativa'), array('class' => 'chbxl_cooperativa', 'id' => 'chbxl_cooperativa')) ?>        
    </label>
    
    <label>
        <span>Seleccione el año:</span>
        <?php echo CHtml::checkBoxList('chbxl_ano', 'chbxl_ano', CHtml::listData($modelo_ano,'id', 'ano'), array('class' => 'chbxl', 'id' => 'chbxl_ano')) ?>
    </label>  
    
    <label>
       <span>Seleccione la Materia:</span>
       <?php echo CHtml::checkBoxList('chbxl_materia', 'chbxl_materia', CHtml::listData($modelo_materia,'ideje', 'eje'), array('class' => 'chbxl_materia', 'id' => 'chbxl_materia')) ?>
    </label> 

   <label>
       <span id="sp_submaterias">Incluir SubMaterias en el reporte:</span>
       <?php echo CHtml::checkBox('chbx_submaterias', true, array ('id' => 'chbx_submaterias')); ?>
    </label>  
      
    
    <?php echo CHtml::submitButton('Generar Reporte', array('class' => 'btn_cruce', 'id' => 'btn_generarcruce')); ?>

    <?php echo CHtml::endForm(); ?>
</div>

<div id="reporte" class="reportecrucedatos">
</div>
