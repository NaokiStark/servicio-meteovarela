# Servicio de API JSON para Meteovarela

### ¿Qué es esto?

Es un script simple que hace scraping del servicio de meteorología de Florencio Varela y lo mete en un json

### ¿Para qué hiciste esto?

Que se yo, estaba re al pedo y me puse a hacerlo

### ¿Para qué puede servir?

Bueno puede ser para relojes, "tickers" o proyectos con arduino|raspberry o para poner la info en una pagina o lo que quieras

### Se rompio algo

Lo de siempre, issue y explicando bien el problema

### ¿Anda en php x?

Creo que anda desde la version 5, yo probé el script en la 8 y anda


### ¿Qué es lo que devuelve?

Esto: 

```json
{
  "date": 1664209744,
  "fecha": "26\/09\/22",
  "hora": "1:27p",
  "temperatura": "21.1",
  "sensacion_termica": "21.1",
  "record_dia": {
    "minima": "13.9",
    "maxima": "21.4"
  },
  "velocidad_viento": "0.0",
  "direccion_viento": "SW (219)",
  "lluvia_diaria": "0.0 mm",
  "presion_atm": "1008.3 hPa",
  "humedad_relativa": "52",
  "punto_rocio": "10.9",
  "next_take_after": 1664210344
}
```

`date` es la fecha que se hizo la captura de datos de servmetfv
`next_take_after` es el momento en el que se va a permitir capturar los datos nuevamente

Esto lo hice para no sobrecargar el servicio y no levantar sospechas (shhh), asi que cada 10 minutos se va a poder refrescar el tiempo

### EHH ESTO NO ANDA SIEMPRE DA LO MISMO

El servicio de Meteovarela a veces se rompe o deja de andar y siempre deja la última toma que hizo (pueden pasar más de 2 meses sin que lo arreglen), no se que pasará en esos casos pero no soy responsable de nada

### Gracias

De nada, un placer ayudar a la sociedad.