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
            if(Yii::app()->request->isAjaxRequest)
            {
                if(!isset($_POST['cooperativa'])){
                    $respuesta = array('resultado' => false,'mensaje' => 'Debe seleccionar al menos Dos Cooperativa.');                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
                else if(count($_POST['cooperativa']) < 2){
                    $respuesta = array('resultado' => false,'mensaje' => 'Debe seleccionar al menos Dos Cooperativa.');                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
                else if(!isset($_POST['ano'])){
                    $respuesta = array('resultado' => false,'mensaje' => 'Debe seleccionar al menos un Año.');                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
                else if(!isset($_POST['materia'])){
                    $respuesta = array('resultado' => false,'mensaje' => 'Debe seleccionar al menos una Materia.');                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
                else{
                    $indsubmateria = false;
                    if(isset($_POST['indsubmateria'])){
                        $indsubmateria = $_POST['indsubmateria'] === 'true'? true: false;
                    }

                    $reporte = new Reportes();
                    $reporte->reportecrucedatos($_POST['cooperativa'], $_POST['ano'], $_POST['materia'],$indsubmateria);
                    $formatoreporte = $reporte->generarreportecrucedatos();

                    $respuesta = array('resultado' => true,'mensaje' => $formatoreporte);                    
                    echo CJSON::encode($respuesta); 
                    Yii::app()->end();
                }
            }
            
            $modelo_ano = Ano::model()->findAll();
            $modelo_cooperativas = Cooperativa::model()->findAll();  
            $modelo_materia = Eje::model()->findAll();

            $this->render('crucedatos',array(
                        'modelo_ano'=>$modelo_ano,'modelo_cooperativas'=>$modelo_cooperativas, 
                'modelo_materia' => $modelo_materia
            ));           
            
	}
}