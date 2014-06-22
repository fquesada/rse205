<?php

class ReportesController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
        
        public function actionBasico()
	{
            if(Yii::app()->request->isAjaxRequest)
            {
                if(!isset($_POST['ano'])){
                    $respuesta = array('resultado' => false,'mensaje' => 'Debe seleccionar al menos un año');                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                } 
                else{
                    $indsubmateria = false;
                    if(isset($_POST['indsubmateria'])){
                        $indsubmateria = $_POST['indsubmateria'] === 'true'? true: false;
                    }
                    
                    $reporte = new Reportes();
                    $reporte->reportebasico(intval($_POST['cooperativa']), $_POST['ano'], $indsubmateria);
                    $formatoreporte = $reporte->generarreportebasico();
                    
                    $respuesta = array('resultado' => true,'mensaje' => $formatoreporte);                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
            }


            $modelo_ano = Ano::model()->findAll();
            $modelo_cooperativas = Cooperativa::model()->findAll();           

            $this->render('basico',array(
                        'modelo_ano'=>$modelo_ano,'modelo_cooperativas'=>$modelo_cooperativas
            ));
	}
        
        public function actionCruceDatos()
	{
		$this->render('crucedatos');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}