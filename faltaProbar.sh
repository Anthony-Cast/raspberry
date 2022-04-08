#!/bin/bash
cd html
#crear ka base de datos si no existe
sqlite3 raspberry.db "create table if not exists registros(id integer primary key autoincrement,anio text not null,mes text not null,dia text not null,hora text not null,archivo text not null,formato not null);" ".quit"

#rm -f archivo.txt
#obtener dia y mes actual para extraer datos del dìa
diaActual=$(date +"%d")
mesActual=$(date +"%b")

#obtencion de todas las visitas a los archivos
grep -E '.pdf HTTP|.mp4 HTTP' /var/log/nginx/kolibri_uwsgi.log | while read -r line ; do
#separacion del contenido de las visitas para poder insertarlas en la nase de datos
	myarr=($line)
	dia=${line[@]:19:2}
	mes=${line[@]:22:3}
	anio=${line[@]:26:4}
	hora=${line[@]:31:8}
	archivo=${line[@]:73:36}
	formato=${line[@]:106:3}
<<"COMMENT"
    #busqueda de las bases de datos para obtener el nombre del archivo asociado
	cd /~/.kolibri/content/databases
	ls | grep -E '.sqlite3' | while read -r line ; do
        #se busca en cada base de datos en caso se encuentre se finaliza el bucle
		sqlite3 $line 
		titulo="select title from content_contentnode where content_id=$archivo"

		if [ $titulo ]
		then
		".quit"
        break
		fi
	done
COMMENT
    #se compara si el dia de la busqueda es la del dia actual
	if [ $diaActual == $dia ] && [ $mesActual == $mes ]
	then
        #se escribe tanto en la base de datos como en el archivo txt
		echo $anio,$mes,$dia,$hora,$titulo,$formato >> archivo.txt
		sqlite3 raspberry.db "insert into registros(anio,mes,dia,hora,archivo,formato) values('$anio','$mes','$dia','$hora','$titulo','$formato');" ".quit"
	fi
done
#se visualiza si los registros se añadieron
sqlite3 raspberry.db "select * from registros;"

#PENDIENTE
#falta corregir hora ya que de 1:00 - 9:00am tiene 7 caracteres y despues posee 8 caracteres
