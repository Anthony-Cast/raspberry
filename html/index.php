
<?php
class BaseDatos extends SQLite3{
	function __construct(){
		$this->open("/var/www/html/raspberry.db");
	}
}
$db= new BaseDatos();
if($db){
	$sql = "select archivo, count(id) as visitas from registros group by archivo";
	$archivos = array();
	$visitas = array();
	$result=$db->query($sql);
	while($row = $result->fetchArray(SQLITE3_ASSOC)){
		array_push($archivos,$row["archivo"]);
		array_push($visitas,$row["visitas"]);
	}
}

?>
<?php
function nuevaGrafica(){
	$paramsFecha = explode("-",$_POST["fecha"]);
	$formato=$_POST["formato"];
	$sql = "select archivo, count(id) as visitas from registros where dia=$paramsFecha[2] and mes=paramsFecha[1]and anio=$paramsFecha[0] group by archivo";
	$result=$db->query($sql);
	while($row=$result->fetchArray(SQLITE3_ASSOC)){
		array_push($archivos,$row["archivo"]);
		array_push($visitas,$row["visitas"]);
	}
	$data = array();
	array_push($data,$archivos);
	array_push($data,$visitas);
	echo $data;
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
<style>

</style>
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
    <button style="width:200px" type="submit" class="rounded-md bg-green-500" onclick="nuevaGrafica()">Ingresar filtros</button>
    </div>
    
</form>

<canvas style="width: 100%;height: 400px;" id="myChart"></canvas>


<script>
    
    
    var archivos = <?php echo json_encode($archivos)?>;
    var visitas = <?php echo json_encode($visitas)?>;
    var ctx = document.getElementById("myChart").getContext("2d");
        var myNewChart = new Chart(ctx,{
        type:'bar',
        data: {
        labels: archivos,
        datasets: [
                {
                label: "Grafica de visitas",
                data: visitas
                }
            ]
        }   
    });
</script>
