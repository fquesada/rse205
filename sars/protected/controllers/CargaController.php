<?php

class CargaController extends Controller
{
	public function actionIndex()
	{    
	}
        
        
        public function actionCarga()
	{         
            
            if(isset($_FILES['file']))
            {
               if(isset($_POST['preguntas']))
                    $guardarpreguntas = $_POST['preguntas'];
               else
                   $guardarpreguntas = false;
               
               $instancefile = $_FILES['file'];
               $pathfiletemp = $instancefile["tmp_name"];     
               
               // array que va contener un array por cada linea del archivo
               $csv = array (); 
                
               $file = fopen($pathfiletemp,"r");  
               while (($line = fgetcsv($file)) !== FALSE) {
                  array_push($csv, $line);
               }
               fclose($file);
               
               $respuesta = false;
               if($guardarpreguntas){
                   $respuesta = $this->guardarpreguntas($csv);
                   if($respuesta)
                       $mensaje = "Las preguntas se guardaron de manera correcta";
                   else
                       $mensaje = "Ocurrio un error al intentar guardar las preguntas, revise la BD.";
                   
                   //$this->render('carga',array('mensaje'=>$mensaje)); 
               }
               else
                   $respuesta = $this->guardarrespuestas($csv);               
               
               $this->render('carga'); 
             
            }
            
            $this->render('carga',array('mensaje'=>"hola",));
        }
        
        
        private function guardarpreguntas($csv)
        {
            $resultado = false;
            $preguntas = $csv[0];            
            
            $idpreg = 1;
            $cantpreg = count($preguntas);
            
            for ($i=0; $i<=$cantpreg-1; $i++) {
                
                //Se excluye de las preguntas
                //Posicion 0 = ID de respuesta
                //Posicion 1 = Contraseña o Token            
                //Posicion $cantpreg - 5 = "Nombre/s"
                //Posicion $cantpreg - 4 = "Apellido/s"
                //Posicion $cantpreg - 3 = CooperativaID
                //Posicion $cantpreg - 2 = Cooperativa
                //Posicion $cantpreg - 1 = Puesto  
                
                if(!(($i == 0) || ($i == 1) || ($i == $cantpreg - 5) || ($i == $cantpreg - 4) || ($i == $cantpreg - 3)  || ($i == $cantpreg - 2) || ($i == $cantpreg - 1)))
                {    
                    $preg = $preguntas[$i]; 
                    $preguntar_guardar = str_replace('"', "", $preg);
                    
                    $pregunta = new Pregunta();
                    $pregunta->idpregunta = $idpreg;
                    $pregunta->pregunta = $preguntar_guardar;
                    $resultado = $pregunta->save();
                    
                    $idpreg++;
                }
            }
            return $resultado;
        }
        
        private function guardarrespuestas($csv)
        {
            $resultado = false;   
            
            $cantresultado = count($csv)-1;
            
            //El ciclo empieza en 1 porque a partir de la fila hay respuestas
            for ($i=1; $i<=$cantresultado; $i++) {
                                
                $respuestas = $csv[$i];
                $idpreg = 1;
                $cantres = count($respuestas);
                
                //Posicion 0 = ID de respuesta
                //Posicion 1 = Contraseña o Token            
                //Posicion $cantpreg - 5 = "Nombre/s"
                //Posicion $cantpreg - 4 = "Apellido/s"
                //Posicion $cantpreg - 3 = CooperativaID
                //Posicion $cantpreg - 2 = Cooperativa
                //Posicion $cantpreg - 1 = Puesto                
                
                $evaluado = new ParticipanteEvaluacion();
                $evaluado->idparticipante = str_replace('"', "", $respuestas[0]);
                $evaluado->token = str_replace('"', "", $respuestas[1]);
                $evaluado->nombre = str_replace('"', "", $respuestas[$cantres - 5]);
                $evaluado->apellidos = str_replace('"', "", $respuestas[$cantres - 4]);
                $evaluado->idcooperativa = str_replace('"', "", $respuestas[$cantres - 3]);
                $evaluado->puesto = str_replace('"', "", $respuestas[$cantres - 1]);
                $resultado = $evaluado->save();
                
//                for ($x=0; $x<=$cantres-1; $x++) {
//                    
//                    
//                    
//                    
//                    
//                }
            }
            
            return $resultado;
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