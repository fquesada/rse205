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
    
    private $_cooperativas = array();
    private $_materias = array();
    
    private $_tiporeporte; /*0 = Basico, 1 = Cruce Variables*/
    
    public function reportebasico($idcooperativa, $anos, $indsubmateria)
    {
        $this->_idcooperativa = $idcooperativa;
        $this->_ano = $anos;
        $this->_indsubmateria = $indsubmateria;
        $this->_tiporeporte = 0;
    }
    
    public function reportecrucedatos($cooperativas, $anos, $materias, $indsubmateria)
    {
        $this->_cooperativas = $cooperativas;
        $this->_ano = $anos;        
        $this->_materias = $materias;
        $this->_indsubmateria = $indsubmateria;
        $this->_tiporeporte = 1;
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
                    $html[] = $this->crearTablaSubMateria($querysubmateria);
                }
            }
        }
        
        return $html;
    }
    
    public function generarreportecrucedatos()
    {
        $html = array();
        $html[] = $this->crearEncabezadoReporte();
        
        foreach ($this->_ano as $key => $ano) {
            
            $modelo_ano = Ano::model()->findByPk($ano);              
            
            $querymateria = $this->obtenerComparativoMateria($modelo_ano->ano);
            if(!$querymateria)
            {
                $html[] = $this->crearParrafoNoEvaluacion($modelo_ano->ano);
            }
            else
            {
                $html[] = $this->crearTablaComparativaMateria($querymateria,$modelo_ano->ano);
                if($this->_indsubmateria){
                    $querysubmateria = $this->obtenerComparativoSubMateria($modelo_ano->ano);
                    $html[] = $this->crearTablaComparativaSubMateria($querysubmateria);
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
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa WHERE ev.cooperativa = :idcooperativa AND ev.ano = :ano) e 
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
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa WHERE ev.cooperativa = :idcooperativa AND ev.ano = :ano) e 
                    ON rs.idevaluacion = e.id) INNER JOIN eje ej ON rs.eje = ej.ideje) INNER JOIN subeje s ON rs.subeje = s.idsubeje)
                ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":idcooperativa", $this->_idcooperativa, PDO::PARAM_INT);
        $command->bindParam(":ano", $ano, PDO::PARAM_INT);
        $query = $command->queryAll();
        
        return $query;
    }
    
    /*    
    Retorna False si la consulta retorna 0 Filas
    Retorna array de IdCooperativa, IdEje, Eje, Puntaje      
    */ 
    function obtenerComparativoMateria($ano){
          
        $param_cooperativas = implode($this->_cooperativas, ',');
        $param_materias = implode($this->_materias, ',');
        
        $connection = Yii::app()->db;        
        $sql =  "   SELECT e.cooperativa, ej.ideje, ej.eje, re.puntaje
                    FROM  ((resultados_eje re INNER JOIN (SELECT ev.*, c.cooperativa nombrecooperativa 
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa WHERE ev.cooperativa IN (".$param_cooperativas.") AND ev.ano = :ano) e 
                    ON re.idevaluacion = e.id) INNER JOIN eje ej ON re.eje = ej.ideje) WHERE re.eje IN (".$param_materias.")
                ";
        $command = $connection->createCommand($sql);        
        $command->bindParam(":ano", $ano, PDO::PARAM_INT);
        $query = $command->queryAll();
        
        if (empty($query))
            return false;
        else
            return $query;
    }
    
    /*    
    Retorna array de IdCooperativa, IdEje, IdSubEje, SubEje, Puntaje    
    */ 
    function obtenerComparativoSubMateria($ano){
          
        $param_cooperativas = implode($this->_cooperativas, ',');
        $param_materias = implode($this->_materias, ',');
        
        $connection = Yii::app()->db; 
        $sql =  "   SELECT e.cooperativa, ej.ideje, s.idsubeje, s.subeje, rs.puntaje 
                    FROM  (((resultados_subeje rs INNER JOIN (SELECT ev.*, c.cooperativa nombrecooperativa 
                    FROM evaluacion ev INNER JOIN cooperativa c ON ev.cooperativa = c.idcooperativa WHERE ev.cooperativa IN (".$param_cooperativas.") AND ev.ano = :ano) e 
                    ON rs.idevaluacion = e.id) INNER JOIN eje ej ON rs.eje = ej.ideje) INNER JOIN subeje s ON rs.subeje = s.idsubeje) WHERE rs.eje IN (".$param_materias.")
                ";
        $command = $connection->createCommand($sql);        
        $command->bindParam(":ano", $ano, PDO::PARAM_INT);
        $query = $command->queryAll();
        
        if (empty($query))
            return false;
        else
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
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje hace referencia a una escala del 100%</p>";
        
        //Cerrar el div del periodo con solo Materia, sino, se procede a cerrar en CrearTablaSubMateria
//        if(!$this->_indsubmateria){
//            $html .= "</div>";
//        }
        
        return $html;
    }
    
    function crearTablaSubMateria($query){
       
       $ejexsubeje = $this->obtenerEjexSubEje();
       
       $punteroejeinicio = 0;
       $punteroejefinal = 0;
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
            $punteroejefinal = $cantsubmateriasactual - 1;
            
            for ($i = $punteroejeinicio; $i <= $punteroejefinal; $i++) {
                $html .= "<tr>";
                    $html .= "<td>".$query[$i]['subeje']."</td>";
                    $html .= "<td>".$query[$i]['puntaje']."</td>";
                $html .= "</tr>";                
            }
            
            $punteroejeinicio = $punteroejefinal + 1;
            
            $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje hace referencia a una escala del 100%</p>";        
        }
        
        //Cerrar el div del periodo con Materia y SubMaterias,
        //$html .= "</div>";
        
        return $html;
    }  
    
    function crearTablaComparativaMateria($query, $ano){
       
       $html = "<p class='p_periodo'>Periodo de Evaluacion: ".$ano."</p>";
       $html .= "<table class='tbl_comparativamateria'>";
            $html .= "<thead>";
                $html .= "<tr>";
                    $html .= "<th>Materia</th>";
                    foreach ($this->_cooperativas as $key => $idcooperativa) {
                        
                        $modelo_cooperativa = Cooperativa::model()->findByPk($idcooperativa);
                        $html .= "<th>".$modelo_cooperativa->cooperativa."</th>";
                    }                    
                $html .= "</tr>"; 
            $html .= "</thead>";
            $html .= "<tbody>";
            
            foreach ($this->_materias as $key => $idmateria) {
                        $html .= "<tr>";           
                            $modelo_materia = Eje::model()->findByPk($idmateria);
                            $html .= "<td>".$modelo_materia->eje."</td>";
                            
                            foreach ($this->_cooperativas as $key => $idcooperativa) {
                                
                                $indicadorsinpuntaje = true;
                                foreach ($query as $fila) {
                                    if($idmateria == $fila['ideje'] && $idcooperativa == $fila['cooperativa'])
                                    {
                                         $html .= "<td>".$fila['puntaje']."</td>";
                                         $indicadorsinpuntaje = false;
                                    }
                                }
                                if($indicadorsinpuntaje)
                                {
                                    $html .= "<td>N/A</td>";
                                }
                            }                                   
                        $html .= "</tr>";
            } 
            
            $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje hace referencia a una escala del 100%</p>";
        
        return $html;
    }
    
    function crearTablaComparativaSubMateria($query){
       
        $ejexsubeje = $this->obtenerEjexSubEjeFiltrado();

        $punteroejeinicio = 0;
        $punteroejefinal = 0;
        $cantsubmateriasactual = 0;

        $html = "";

        foreach ($ejexsubeje as $fila) {

        $html .= "<p class='p_materia'>Materia: ".$fila['eje']."</p>";
        $html .= "<table class='tbl_comparativasubmateria'>";
            $html .= "<thead>";
                $html .= "<tr>";
                    $html .= "<th>SubMateria</th>";
                    foreach ($this->_cooperativas as $key => $idcooperativa) {

                        $modelo_cooperativa = Cooperativa::model()->findByPk($idcooperativa);
                        $html .= "<th>".$modelo_cooperativa->cooperativa."</th>";
                    }                    
                $html .= "</tr>"; 
            $html .= "</thead>";
            $html .= "<tbody>";
            
                $ideje = $fila['ideje'];            
                $submaterias = Subeje::model()->findAll(array('condition'=>'ideje=:ideje','params'=>array(':ideje'=>$ideje),));

                foreach ($submaterias as $key => $submateria) {
                    $html .= "<tr>";
                    $html .= "<td>".$submateria['subeje']."</td>";

                    foreach ($this->_cooperativas as $key => $idcooperativa) {

                        $indicadorsinpuntaje = true;
                        foreach ($query as $fila) {
                            if($idcooperativa == $fila['cooperativa'] && $ideje == $fila['ideje'] &&  $submateria['idsubeje'] == $fila['idsubeje'])
                            {
                                 $html .= "<td>".$fila['puntaje']."</td>";
                                 $indicadorsinpuntaje = false;
                            }
                        }
                        if($indicadorsinpuntaje)
                        {
                            $html .= "<td>N/A</td>";
                        }
                    }                                   
                    $html .= "</tr>";
                } 
            
            $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<p class='p_detallepuntaje'>* Cada puntaje hace referencia a una escala del 100%</p>";        
        }
        return $html; 
        
    }
    
    function crearParrafoNoEvaluacion($ano){
        //$html = "<div class='div_periodo'>";
        $html = "<p class='p_noevaluacion'>No existen resultados para el año ".$ano.".</p>";
        //$html .= "</div>";
        
        return $html;
    }
    
    function crearEncabezadoReporte(){
        
        if($this->_tiporeporte == 0){
            $cooperativa = Cooperativa::model()->findByPk($this->_idcooperativa);
            $html = "<h1 class='h1_cooperativa'>Reporte de Autodiagnostico de Responsabilidad Social</h1>";
            $html .= "<h2 class='h2_cooperativa'>Cooperativa: ".$cooperativa->cooperativa."</h2>";
            
            setlocale(LC_TIME, 'spanish');           
            $html .= "<h3 class='h3_cooperativa'>Generado:".strftime("%A %#d de %B del %Y ")."  a las ".date("h:i:s. A")."</h2>";
            
            return $html;        
        }
        else if($this->_tiporeporte == 1){
            $html = "<h1 class='h1_cooperativa'>Reporte de Autodiagnostico de Responsabilidad Social</h1>";
            $html .= "<h2 class='h2_cooperativa'>Reporte de Cruce de Informacion</h2>";
            
            setlocale(LC_TIME, 'spanish');           
            $html .= "<h3 class='h3_cooperativa'>Generado:".strftime("%A %#d de %B del %Y ")."  a las ".date("h:i:s. A")."</h2>";
            
            return $html;  
        }
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
    
    function obtenerEjexSubEjeFiltrado(){
        
        $param_materias = implode($this->_materias, ',');
        
        $connection = Yii::app()->db;        
        $sql =  "   SELECT e.ideje, e.eje, COUNT(s.idsubeje) cant_subeje FROM eje e INNER JOIN subeje s ON  e.ideje = s.ideje WHERE e.ideje IN (".$param_materias.")
                    GROUP BY e.eje
                    ORDER BY e.ideje
                ";
        $command = $connection->createCommand($sql);       
        $query = $command->queryAll();
        
        return $query;
    }
    
}

?>
