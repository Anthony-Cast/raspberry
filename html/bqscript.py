
from google.cloud import bigquery
import os
import json
from google.oauth2 import service_account

key={
  "type": "service_account",
  "project_id": "bqpruebas-346416",
  "private_key_id": "cdb58b58acbf9ebdae8095387731d002a8c6ca7f",
  "private_key": "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0MMqH8BvX9/MFRdLNKVSOl10CJ7s/PBVbkmgvJVonnDCjUOdYjqTsnyVfdaeFnFAuoSRNhyGh8QLygnHkl28CalPk473KGu7f52LCGHV5I/ig6oV4wJYYNtVMBLO35kw90X++gl2HTkF8WbvYSMdYACAQHou2lSOyvCOZLics+eDZgIq+L0fLUTEtC8iV4k",
  "client_email": "my-bigquery-sa@bqpruebas-346416.iam.gserviceaccount.com",
  "client_id": "114910494983763665364",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/my-bigquery-sa%40bqpruebas-346416.iam.gserviceaccount.com"
}

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

key_path = "/var/www/html/key.json"
os.environ['GOOGLE_APPLICATION_CREDENTIALS']=key_path

client = bigquery.Client()

table_id="bqpruebas-346416.registros.busquedas"
errors = client.insert_rows_json(table_id,rowsToBD)

if errors:
	print("Hubo un error al actualizar la base de datos")
	print(errors)
else:
	print("La base de datos se actualizo")
