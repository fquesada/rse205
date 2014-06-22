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
            $query = $this->obtenerMateriaxAno($modelo_ano->ano);
            if(!$query)
            {
                $html[] = $this->crearParrafoNoEvaluacion($modelo_ano->ano);
            }
            else
            {
                $html[] = $this->crearTablaMateria($query,$modelo_ano->ano);
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
    
    function obtenerSubMateriaAno($ano){
        
    }
    
    function crearTablaMateria($query, $ano){
       
       $html = "<p class='p_periodo'> Periodo de Evaluacion: ".$ano."</p>";
       $html .= "<table class='tbl_materia'>";
            $html .= "<thead>";
                $html .= "<tr>";
                    $html .= "<th>Materia</th>";
                    $html .= "<th>Puntaje *</th>";
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
        
        return $html;
    }
    
    function crearParrafoNoEvaluacion($ano){
        $html = "<p class='p_noevaluacion'>No existen resultados para el año ".$ano.".</p>";
        return $html;
    }
    
    function crearEncabezadoReporte(){
        $cooperativa = Cooperativa::model()->findByPk($this->_idcooperativa);
        $html = "<h1 class='h1_cooperativa'>Reporte de RS Cooperativo</h1>";
        $html .= "<h2 class='h2_cooperativa'>Cooperativa: ".$cooperativa->cooperativa."</h2>";
        return $html;
    }
    
}

?>
