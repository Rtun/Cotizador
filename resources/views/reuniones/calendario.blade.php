@extends('layouts.app')

@section('content_tittle')
Calendario
@endsection

@section('modulo')
    Calendario
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
@endsection

@section('content')
<div class="row" id="app">
    <div class="col-md-3">
        <div class="sticky-top mb-3">
          <!-- /.card -->
          <div class="card">
            <div class="card-header">
                <h3 class="card-title">Definicion de colores</h3>
            </div>
            <div class="card-body">
                <ul class="fc-color">
                  <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a> Prioridad Alta</li>
                  <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a> Prioridad Media</li>
                  <li><a class="text-success" href="#"><i class="fas fa-square"></i></a> Prioridad Normal</li>
                </ul>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Apartar Fecha</h3>
            </div>
            <div class="card-body">
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <ul class="fc-color-picker" id="color-chooser">
                    <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                </ul>
              </div>
              <div class="d-flex custom-control custom-switch justify-content-end mb-3">
                <input v-model="porArchivo" type="checkbox" class="custom-control-input" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">Por archivo ics</label>
              </div>

              <!-- /btn-group -->

              <form @submit.prevent="addEvento" v-if="porArchivo">
                <label for="sala">Sala:</label>
                <select @change="selecionaSala($event)" class="custom-select" id="sala" required>
                    <option value="" disabled selected>Selecciona una sala</option>
                    <option v-for="elemento in sala" :data-nombre_s="elemento.sa_nombre" :value="elemento.idsala">@{{ elemento.sa_nombre}}</option>
                </select>
                <label for="formFile" class="form-label">Archivo:</label>
                <input class="form-control" type="file" id="formFile" @change="seleccionarArchivo($event)" required>
                <button id="add-new-event" type="submit" class="btn btn-success mt-2">Añadir</button>
              </form>



              <form @submit.prevent="addEvento" v-else>
                <label for="sala">Sala:</label>
                <select @change="selecionaSala($event)" class="custom-select" id="sala" required>
                    <option value="" disabled selected>Selecciona una sala</option>
                    <option v-for="elemento in sala" :data-nombre_s="elemento.sa_nombre" :value="elemento.idsala">@{{ elemento.sa_nombre}}</option>
                </select>
                <label for="tittle">Tema:</label>
                <input v-model="newEvent.title" id="tittle" class="form-control" type="text" placeholder="Tema" required />
                <label for="start">Fecha Y Hora Inicial:</label>
                <input v-model="newEvent.start" id="start" class="form-control mt-1" type="datetime-local" required />
                <label for="end">Fecha Y Hora final</label>
                <input v-model="newEvent.end" id="end" class="form-control mt-1" type="datetime-local" required/>
                <label for="descripcion">Descripcion</label>
                <textarea v-model="newEvent.description" id="descripcion" class="form-control mt-1" placeholder="Descripcion"></textarea>
                <button id="add-new-event" type="submit" class="btn btn-success mt-2">Añadir</button>
            </form>

            </div>
          </div>

        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card card-primary">
          <div class="card-body p-0">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->

      <div class="modal fade" id="modal-reunion" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-reunions">Detalles de la reunion</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="card-body">
                            <div class="callout callout-info">
                              <h5>Titulo</h5>

                              <p>@{{ modalEvent.title}}</p>
                            </div>
                            <div class="callout callout-info">
                              <h5>Fecha y hora de inicio</h5>

                              <p>@{{ modalEvent.start}}</p>
                            </div>
                            <div class="callout callout-info">
                              <h5>Fecha y hora de fin</h5>

                              <p>@{{ modalEvent.end}}</p>
                            </div>
                            <div class="callout callout-info">
                              <h5>Sala</h5>

                              <p>@{{ modalEvent.sala}}</p>
                            </div>
                            <div class="callout callout-info">
                              <h5>Reservado por</h5>

                              <p>@{{ modalEvent.user}}</p>
                            </div>
                            <div class="callout callout-info">
                              <h5>Descripcion</h5>

                              <p>@{{ modalEvent.description}}</p>
                            </div>

                          </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
                    <button v-if="modalEvent.idusuario === usuario.id" type="button" class="btn btn-danger" @click="eliminarEvento(modalEvent.idevento)">Eliminar</button>
                </div>
            </div>
        </div>
        </div>
      </div>

</div>
@endsection

@section('java_extensions')
<!-- fullCalendar 2.2.5 -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/main.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/locales/es.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ical.js/1.4.0/ical.min.js"></script>
@endsection

@section('scripts')
<script>
    new Vue({
        el: '#app',
        data: {
            sala:<?php echo json_encode($sala);?>,
            usuario: <?php echo json_encode($usuario);?>,
            newEvent: {
                idevento: '',
                title: '',
                start: '',
                end: '',
                idsala: '',
                Nombresala: '',
                description: '',
                backgroundColor: 'rgb(25, 105, 44)', // Puedes permitir al usuario elegir esto también
                borderColor: 'rgb(25, 105, 44)',
                textColor: '#fff'
            },
            calendar: null,
            porArchivo: false,
            modalEvent: {
                idevento: '',
                title: '',
                start: '',
                end: '',
                user: '',
                sala: '',
                description: ''
            },
        },
        mounted() {
            this.initializeCalendar();
            this.getEventos();
        },
        methods: {
            initializeCalendar() {
                document.addEventListener('DOMContentLoaded', () => {
                    var Calendar = FullCalendar.Calendar;

                    var calendarEl = document.getElementById('calendar');

                    this.calendar = new Calendar(calendarEl, {
                        locale: 'es',
                        headerToolbar: {
                            left  : 'prev,next today',
                            center: 'title',
                            right : 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        themeSystem: 'bootstrap',
                        editable  : false,
                        droppable : false, // if you still want to allow drag and drop
                        events: [],
                        eventClick: (info) => {
                            // Llama a la función para abrir el modal y pasar la información del evento
                            this.mostrarModal(info.event);
                        },
                        eventDidMount: function(info) {
                            var eventEl = info.el;
                            var startDate = new Date(info.event.start);
                            var endDate = info.event.end ? new Date(info.event.end) : null;

                            var tooltipContent = `
                                <strong>Inicio:</strong> ${startDate.toLocaleDateString()} ${startDate.toLocaleTimeString()}<br>
                                <strong>Fin:</strong> ${endDate ? `${endDate.toLocaleDateString()} ${endDate.toLocaleTimeString()}` : 'No definido'}<br>
                                <strong>Usuario:</strong> ${info.event.extendedProps.usuario || 'No disponible'}<br>
                                <strong>Sala:</strong> ${info.event.extendedProps.Nombresala || 'No disponible'}<br>
                                <strong>Descripción:</strong> ${info.event.extendedProps.description || 'No disponible'}<br>
                            `;

                            $(eventEl).tooltip({
                                title: tooltipContent,
                                html: true,
                                placement: 'top'
                            });
                        }
                    });

                    this.calendar.render();
                });
                self = this;
                var currColor = '#008f39'; //Red by default
                document.querySelectorAll('#color-chooser > li > a').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        self.newEvent.backgroundColor = '';
                        self.newEvent.backgroundColor = '';
                        // Save color
                        currColor = getComputedStyle(this).color;
                        // Add color effect to button
                        document.getElementById('add-new-event').style.backgroundColor = currColor;
                        document.getElementById('add-new-event').style.borderColor = currColor;
                        self.newEvent.backgroundColor = currColor;
                        self.newEvent.borderColor = currColor;
                    });
                });
            },
            selecionaSala (event) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const nombre_s = selectedOption.getAttribute('data-nombre_s');
                this.newEvent.idsala = event.target.value;
                this.newEvent.Nombresala = nombre_s;
            },
            getEventos (){
                axios.get('/catalogos/obtener/eventos').then(response => {
                    const eventos = response.data.eventos;
                    eventos.forEach(evento => {
                        this.calendar.addEvent({
                            idusuario: evento.idusuario,
                            idevento: evento.idreunion,
                            title: evento.sare_tema,
                            start: evento.sare_fecha_inicio,
                            end: evento.sare_fecha_fin,
                            description: evento.sare_descripcion,
                            Nombresala: evento.sala,
                            usuario: evento.usuario,
                            backgroundColor: evento.sare_color_fondo,
                            borderColor: evento.sare_color_borde,
                            textColor: evento.sare_color_texto
                        });
                    });
                });
            },
            formatDate(dateString) {
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                return formattedDate;
            },
            addEvento() {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "Agendar Sala de reunion",
                    text: "¿Quieres agendar la sala para la fecha " + this.newEvent.start + "?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Agendar!",
                    cancelButtonText: "No, Cancelar!",
                    reverseButtons: true
                }).then((result) => {
                    if(result.isConfirmed) {
                        datos = {
                            idsala:this.newEvent.idsala,
                            title: this.newEvent.title,
                            start: this.newEvent.start,
                            end: this.newEvent.end,
                            description: this.newEvent.description,
                            backgroundColor: this.newEvent.backgroundColor,
                            borderColor: this.newEvent.borderColor,
                            textColor: this.newEvent.textColor
                        };
                        axios.post('/catalogos/calendario/save', datos).then( response => {
                            if(response.data.status == 'OK') {
                                Swal.fire({
                                    position: "center",
                                    icon: response.data.alert,
                                    title: response.data.mesagge,
                                    showConfirmButton: true,
                                    timer: 3000
                                });
                                // Limpia los campos del formulario
                                this.newEvent.title = '';
                                this.newEvent.start = '';
                                this.newEvent.end = '';
                                this.newEvent.description = '';
                                this.newEvent.idsala = '';
                                document.getElementById('sala').value = '';
                                this.calendar.removeAllEvents();
                                this.getEventos();
                            }
                            else {
                                Swal.fire({
                                    position: "center",
                                    icon: response.data.alert,
                                    title: response.data.mesagge,
                                    showConfirmButton: true,
                                });
                            }
                        }).catch(error => {
                            console.error('Error al reservar la sala:', error);
                            this.cotizando = false;
                            Swal.fire({
                                title: "Error",
                                text: "Hubo un error al guardar, Por favor contactame",
                                icon: "error"
                            });
                        });

                    }
                });

            },
            parseICSFile(icsData) {
                const jcalData = ICAL.parse(icsData);
                const comp = new ICAL.Component(jcalData);
                const events = comp.getAllSubcomponents('vevent');
                const formatDateToMySQL = (date) => {
                    const year = date.getFullYear();
                    const month = ('0' + (date.getMonth() + 1)).slice(-2); // Mes con dos dígitos
                    const day = ('0' + date.getDate()).slice(-2); // Día con dos dígitos
                    const hours = ('0' + date.getHours()).slice(-2); // Hora con dos dígitos
                    const minutes = ('0' + date.getMinutes()).slice(-2); // Minutos con dos dígitos
                    const seconds = ('0' + date.getSeconds()).slice(-2); // Segundos con dos dígitos
                    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                };

                return events.map(event => {
                    const vevent = new ICAL.Event(event);
                    return {
                        title: vevent.summary,
                        start: formatDateToMySQL(vevent.startDate.toJSDate()),
                        end: formatDateToMySQL(vevent.endDate.toJSDate()),
                        description: vevent.description
                    };
                });
            },
            loadICSFile(file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    // Imprimir el contenido del archivo ICS
                    const icsData = event.target.result;
                    const parsedEvents = this.parseICSFile(icsData);
                    parsedEvents.forEach(event => {
                        this.newEvent.title = event.title;
                        this.newEvent.start = event.start;
                        this.newEvent.end = event.end;
                        this.newEvent.description = event.description;
                    });
                };
                reader.readAsText(file);
            },
            seleccionarArchivo(event) {
                const file = event.target.files[0];
                this.loadICSFile(file);
            },
            mostrarModal(event) {
                // Llenar los datos del evento en la variable modalEvent
                this.modalEvent = {
                    idusuario: event.extendedProps.idusuario,
                    idevento: event.extendedProps.idevento,
                    title: event.title,
                    start: event.start.toISOString(),  // Formato de la fecha para mostrar
                    end: event.end ? event.end.toISOString() : 'Sin fecha de fin',
                    user:event.extendedProps.usuario,
                    sala:event.extendedProps.Nombresala,
                    description: event.extendedProps.description || 'Sin descripción'
                };
                // Mostrar el modal
                $('#modal-reunion').modal('show');
            },
            eliminarEvento(idreunion) {
                let datos = {
                    idevento: idreunion
                };

                Swal.fire({
                    title: 'Estas seguro?',
                    text: 'Deseas eliminar la reunion?',
                    icon: 'warning',
                    showCancelButton:true,
                    cancelButtonText: 'No, cancelar',
                    cancelButtonColor: "#d33",
                    confirmButtonText: 'Si, Eliminar'
                }).then(result => {
                    if(result.isConfirmed) {
                        axios.post('/catalogos/calendario/evento/eliminar', datos).then(response => {
                            Swal.fire({
                                title: 'Hecho!',
                                text: response.data.mesagge,
                                icon: 'success'
                            }).then(resutl => {
                                this.calendar.removeAllEvents();
                                this.getEventos();
                            });
                        }).catch(error => {
                            console.log('Este es el error que brinda el servidor => ' + error);
                            Swal.fire({
                                title: 'Error',
                                text:'Hubo un error al eliminar la reunion, por favor contactame',
                                icon: 'error'
                            });
                        });
                    }
                })
            }
        },

    });
</script>
@endsection
