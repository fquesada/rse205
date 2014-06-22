<?php
/* @var $this ReportesController */

$this->breadcrumbs=array(
	'Reportes',
);
?>
<h1>Reportes</h1>

<p>Seleccione el tipo de reporte que desea generar:</p>


<p>
    <ol>
        <li><?php echo CHtml::link('Reporte por Cooperativa',array('reportes/basico')); ?></li>
        <li><?php echo CHtml::link('Reporte de Cruce de Informacion',array('reportes/crucedatos')); ?></li> 
    </ol>
</p>