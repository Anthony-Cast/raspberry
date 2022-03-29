#!/bin/bash
#rm -f archivo.txt
grep -E '.pdf HTTP|.mp4 HTTP' /var/log/nginx/kolibri_uwsgi.log | while read -r line ; do
	myarr=($line)
	dia=${line[@]:18:2}
	mes=${line[@]:21:3}
	anio=${line[@]:25:4}
	hora=${line[@]:31:8}
	archivo=${line[@]:72:36}
	formato=${line[@]:105:3}
	#echo $anio,$mes,$dia,$hora,$archivo,$formato >> archivo.txt
	sqlite3 raspberry.db "insert into registros(anio,mes,dia,hora,archivo,formato) values('$anio','$mes','$dia','$hora','$archivo','$formato');" ".quit"
done
sqlite3 raspberry.db "select * from registros;"

