
<?php
class BaseDatos extends SQLite3{
	function __construct(){
		$this->open("/var/www/html/raspberry.db");
	}

	$db= new BaseDatos();
	if($db){
		echo "<p>Se conecto a base de datos</p>"
	}
	else{
		echo "<p>hubo un error</p>"
	}

}
?>

<?DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudfare.com/ajax/libs/tailwindcss/2.0.1/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body class="bg-gray-100">
    <nav class="flex py-5 bg-green-500 text-white px-24">
        <div class="w-1/2 px-12 mr-auto">
            <p class="text-2x1 font-bold">My Application</p>
        </div>
        <ul class="w-1/2 px-1/6 ml-auto flex justify-end pt-1">
            <li class="nav-item px-4">
                <a href="/tabla" class="nav-link font-semibold hover:bg-green-700 py-3 px-4 rounded-md">Ver 10 recursos más utilizados</a>
                <a href="/recursos" class="nav-link font-semibold hover:bg-green-700 py-3 px-4 rounded-md">Ver por fecha</a>
            </li>
        </ul>
    </nav>    
</body>

<!--
<div style="padding-top: 20px;">
    <div class="max-w-3x1 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table style="text-align: center;" class="table-fixed w-full" id="tablaRegistros" style="display: none;">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="w-20 py-4" >Archivo</th>
                        <th class="w-1/2 py-4">Visitas</th>
                        <th class="w-1/2 py-4">Formato</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
-->
<form action="" method="post" class="mt-4" style="align-items: center;">
    
    <h1 style="margin-top: 20px;margin-bottom: 20px;margin-left: 20px;"><b>Ingresar una fecha para revisar el historial de los recursos: </b> </h1>
    <div style="margin-left: 20px;" class="row">
        <input name="fecha"id="fecha" type="date">
        <select name="formato" id="formato">
        <option value="ambos">Ambos formatos
        </option> 
        <option value="pdf">PDF</option>
        <option value="mp4">MP4</option>
    </select>
    <button style="width:200px" type="submit" class="rounded-md bg-green-500">Ingresar filtros</button>
    </div>
    
</form>

<canvas style="width: 100%;height: 250px;" id="myChart"></canvas>


<script>
    
    var datos = {{{registros}}}
    var fecha = {{{fecha}}}
    var mensaje ={{{formato}}}
    console.log(datos)
    console.log(mensaje)
    var archivos = []
    var visitas = []
    datos.map((dato) =>{
        archivos.push(dato.archivo);
        visitas.push(dato.visitas);
    });
    var ctx = document.getElementById("myChart").getContext("2d");
        var myNewChart = new Chart(ctx,{
        type:'bar',
        data: {
        labels: archivos,
        datasets: [
                {
                label: "Archivos visitados el " +fecha +" "+mensaje,
                data: visitas
                }
            ]
        }   
    });
</script>

<script>
    var tabla ={{{resultados}}}
    var llenarTabla = document.getElementById("tablaRegistros").getElementsByTagName('tbody')[0]
    for(var i=0;i<tabla.length;i++){
        console.log(tabla[i].archivo)
        llenarTabla.insertRow().innerHTML = `
            <td class="py-3 px-6">`+tabla[i].archivo+`</td>
            <td class="p-3">`+tabla[i].visitas+`</td>
            <td class="p-3" >`+"<b>"+tabla[i].formato+`</td>`;
    }
    document.getElementById("tablaRegistros").style.display="block";
</script>  
</html>
