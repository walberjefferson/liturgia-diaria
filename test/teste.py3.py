#!/usr/bin/python
import datetime as dt
from datetime import date

import json
import urllib.request
import codecs

# http://www.chemcalc.org/web/pythonExamples
def daterange(start_date, end_date):
    for n in range(int ((end_date - start_date).days)):
        yield start_date + dt.timedelta(n)

start_date = dt.datetime(2015, 1, 1)
end_date = dt.datetime(2016, 1, 1)

for data in daterange(start_date, end_date):
    dataTupla = data.timetuple()
    dia = dataTupla[2]
    mes = dataTupla[1]
    ano = dataTupla[0]

    endereco = "http://localhost/liturgia-diaria/?ano="+str(ano)+"&mes="+str(mes)+"&dia="+str(dia)

    try:
        response = urllib.request.urlopen(endereco)
        pageContent = response.read()
        data = json.loads(pageContent.decode('utf-8'))
    except:
        print(endereco)
