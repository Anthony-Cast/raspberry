#primero obetenemos los registros del txt
from google.cloud import bigquery
import os
import json


rowsToBD = []
registro = open('archivo.txt',"r")

for line in registro:
	line=line.strip('\n')
	dataArry=line.split(",")
	dataFormat = {
		"anio":dataArry[0],
		"mes":dataArry[1],
		"dia":dataArry[2],
		"hora":dataArry[3],
		"archivo":dataArry[4],
		"formato":dataArry[5]
}	
	rowsToBD.append(dataFormat)

ruta = "/var/www/html/key.json"
os.environ['GOOGLE_APPLICATION_CREDENTIALS']=ruta


client = bigquery.Client()

table_id="bqpruebas-346416.registros.busquedas"
errors = client.insert_rows_json(table_id,rowsToBD)

if errors:
	print("Hubo un error al actualizar la base de datos")
	print(errors)
else:
	print("La base de datos se actualizo")
