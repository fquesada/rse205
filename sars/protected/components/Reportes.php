<?php

/*
 * Esta clase genera los reportes
 */

/**
 * Clase que genera los reportes
 *
 * @author Fabian
 */
class Reportes {
    
    
    //public $reporte;
    
    private $_idcooperativa;
    private $_ano = array();
    private $_indsubmateria;
    
    public function reportebasico($idcooperativa, $anos, $indsubmateria)
    {
        $this->_idcooperativa = $idcooperativa;
        $this->_ano = $anos;
        $this->_indsubmateria = $indsubmateria;
    }
        
    public function generarreportebasico(){
        
        $html = array();
        $html[] = $this->crearEncabezadoReporte();
        
        foreach ($this->_ano as $key => $ano) {
            
            $modelo_ano = Ano::model()->findByPk($ano);    
            $querymateria = $this->obtenerMateriaxAno($modelo_ano->ano);
            if(!$querymateria)
            {
                $html[] = $this->crearParrafoNoEvaluacion($modelo_ano->ano);
            }
            else
            {
                $html[] = $this->crearTablaMateria($querymateria,$modelo_ano->ano);
                if($this->_indsubmateria){
                    $querysubmateria = $this->obtenerSubMateriaxAno($modelo_ano->ano);
                    $html[] = $this->crearTablaSubMateria($querysubmateria,$modelo_ano->ano);
                }
            }
        }
        
       return $html;
    }
    
    /*
    Retorna False si la consulta retorna 0 Filas
    Retorna array de Eje y Puntaje      
    */    
    function obtenerMateriaxAno($ano){
        $connection = Yii::app()->db;        
        $sql =  "   SELECT ej.eje, re.puntaje
                    FROM  ((resultados_eje re INNER JOIN (SELECT ev.*, c.cooperativa nombrecooperativa 
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa where ev.cooperativa = :idcooperativa and ev.ano = :ano) e 
                    ON re.idevaluacion = e.id) INNER JOIN eje ej ON re.eje = ej.ideje)
                ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":idcooperativa", $this->_idcooperativa, PDO::PARAM_INT);
        $command->bindParam(":ano", $ano, PDO::PARAM_INT);
        $query = $command->queryAll();
        
        if (empty($query))
            return false;
        else
            return $query;
    }
    
    /*    
    Retorna array de Eje y Puntaje      
    */    
    function obtenerSubMateriaxAno($ano){
        $connection = Yii::app()->db;        
        $sql =  "   SELECT s.subeje, rs.puntaje 
                    FROM  (((resultados_subeje rs INNER JOIN (SELECT ev.*, c.cooperativa nombrecooperativa 
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa where ev.cooperativa = :idcooperativa and ev.ano = :ano) e 
                    ON rs.idevaluacion = e.id) INNER JOIN eje ej ON rs.eje = ej.ideje) INNER JOIN subeje s ON rs.subeje = s.idsubeje)
                ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":idcooperativa", $this->_idcooperativa, PDO::PARAM_INT);
        $command->bindParam(":ano", $ano, PDO::PARAM_INT);
        $query = $command->queryAll();
        
        return $query;
    }
    
    function crearTablaMateria($query, $ano){
       
       //$html = "<div class='div_periodo'>";
       $html = "<p class='p_periodo'>Periodo de Evaluacion: ".$ano."</p>";
       $html .= "<table class='tbl_materia'>";
            $html .= "<thead>";
                $html .= "<tr>";
                    $html .= "<th>Materia</th>";
                    $html .= "<th>Puntaje(%)*</th>";
                $html .= "</tr>"; 
            $html .= "</thead>";
            $html .= "<tbody>";
            foreach ($query as $fila) {
                $html .= "<tr>";
                    $html .= "<td>".$fila['eje']."</td>";
                    $html .= "<td>".$fila['puntaje']."</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje es contra una escala del 100%</p>";
        
        //Cerrar el div del periodo con solo Materia, sino, se procede a cerrar en CrearTablaSubMateria
//        if(!$this->_indsubmateria){
//            $html .= "</div>";
//        }
        
        return $html;
    }
    
    function crearTablaSubMateria($query, $ano){
       
       $ejexsubeje = $this->obtenerEjexSubEje();
       
       $punteroejeactual = 0;
       $punteroeje = 0;
       $cantsubmateriasactual = 0;
       
       $html = "";
       
       foreach ($ejexsubeje as $fila) {
                   
       $html .= "<p class='p_materia'>Materia: ".$fila['eje']."</p>";
       $html .= "<table class='tbl_submateria'>";
            $html .= "<thead>";
                $html .= "<tr>";
                    $html .= "<th>SubMateria</th>";
                    $html .= "<th>Puntaje(%)*</th>";
                $html .= "</tr>"; 
            $html .= "</thead>";
            $html .= "<tbody>";
            
            $cantsubmateriasactual = $cantsubmateriasactual + intval($fila['cant_subeje']);
            $punteroeje = $cantsubmateriasactual - 1;
            
            for ($i = $punteroejeactual; $i <= $punteroeje; $i++) {
                $html .= "<tr>";
                    $html .= "<td>".$query[$i]['subeje']."</td>";
                    $html .= "<td>".$query[$i]['puntaje']."</td>";
                $html .= "</tr>";                
            }
            
            $punteroejeactual = $punteroeje + 1;
            
            $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje es contra una escala del 100%</p>";        
        }
        
        //Cerrar el div del periodo con Materia y SubMaterias,
        //$html .= "</div>";
        
        return $html;
    }
    
    
    function crearParrafoNoEvaluacion($ano){
        //$html = "<div class='div_periodo'>";
        $html = "<p class='p_noevaluacion'>No existen resultados para el año ".$ano.".</p>";
        //$html .= "</div>";
        
        return $html;
    }
    
    function crearEncabezadoReporte(){
        $cooperativa = Cooperativa::model()->findByPk($this->_idcooperativa);
        $html = "<h1 class='h1_cooperativa'>Reporte de RS Cooperativo</h1>";
        $html .= "<h2 class='h2_cooperativa'>Cooperativa: ".$cooperativa->cooperativa."</h2>";
        return $html;
    }
    
    function obtenerEjexSubEje(){
        $connection = Yii::app()->db;        
        $sql =  "   SELECT e.eje, COUNT(s.idsubeje) cant_subeje FROM eje e INNER JOIN subeje s ON  e.ideje = s.ideje
                    GROUP BY e.eje
                    ORDER BY e.ideje
                ";
        $command = $connection->createCommand($sql);       
        $query = $command->queryAll();
        
        return $query;
    }
    
}

?>
