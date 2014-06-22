$(document).ready(function() {

    //Boton Generar Reporte Basico
    $("#btn_generarbasico").click(function(event){
        event.preventDefault();        
        
        $('#reporte').html('<img src="../../images/preload.GIF" class="preload"><span class="preloadspan">Cargando...</span></img>');
        
        
        $.ajax({
            type: "POST",
            url: "basico",
            data: databasico(),
            dataType: 'json',
            timeout: 120000, //2 minutos de espera sino timeout
            error: function (jqXHR, textStatus){
                if (jqXHR.status === 0) {                         
                    window.alert("Problema de red, intente nuevamente.");              
                } else if (jqXHR.status == 404) {
                    window.alert("Solicitud no encontrada.");  
                } else if (jqXHR.status == 500) {
                    window.alert("Error 500. Ha ocurrido un problema con el servidor, contacte al administrador del sistema.");                   
                } else if (textStatus === 'parsererror') {
                    window.alert("Ha ocurrido un inconveniente, intente nuevamente.");
                } else if (textStatus === 'timeout') {
                    window.alert("Tiempo de espera excedido, intente nuevamente.");                    
                } else if (textStatus === 'abort') {
                    window.alert("Se ha abortado la solicitud, intente nuevamente");
                } else {
                    window.alert("Error desconocido, intente nuevamente.");                            
                }
            },
            success: function(respuesta){
               if(!respuesta.resultado){
                   window.alert(respuesta.mensaje); 
               }else{            
                   $('#reporte').html();
                   $("#reporte").html(respuesta.mensaje);
               }
               
            }
         });
     }); 
     
     function databasico(){
        var data = {};
        data['cooperativa'] = $('#ddl_cooperativa').val();
        data['ano'] = getanos();
        data['indsubmateria'] = $('#chbx_submaterias').is(':checked');       
        return data;
     }
     
     function getanos(){
        var anos = [];
        $("[name='chbxl_ano[]']:checked").each(function() {
            anos.push(this.value);
        });
        return anos;
    }
     
 });
 
