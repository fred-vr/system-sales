<template>
   <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">SISTEMA DE COMPRAS - VENTAS</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">

                       <h2>Auditoria</h2><br/>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">

                                    <th>Ip</th>
                                    <th >Datos de Entrada</th>
                                    <th>Datos de Salida</th>
                                    <th>Tabla</th>
                                    <th>Proceso</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="log in arrayLog" :key="log.id">
                                    <td v-text="log.ip"></td>
                                    <td v-text="log.entrada"></td>
                                    <td v-text="log.salida"></td>
                                    <td v-text="log.tabla"></td>
                                    <td v-text="log.proceso"></td>
                                    <td v-text="log.usuario"></td>
                                </tr>

                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>


                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>

    export default {
        data(){

            return {

                log_id:0,
                arrayLog:[],
                errorCategoria:0,
                errorMostrarMsjCategoria:[],
                pagination:{

                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,

                },
                offset:3,
                criterio:'user',
                buscar:''
            }

        },

        computed:{

            isActived: function(){

              return this.pagination.current_page;

            },

             //calcula los elementos de la paginacion
            pagesNumber: function(){

                if(!this.pagination.to){

                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){

                   from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){

                   to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){

                   pagesArray.push(from);
                   from++;
                }
                return pagesArray;


            }

        },

        methods:{

           listarCategoria(page,buscar,criterio){

               let me=this;

               var url= '/auditoria?page=' + page + '&buscar='+ buscar + '&criterio='+criterio;

               axios.get(url).then(function (response) {
                    // handle success
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayLog=respuesta.log.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
           },

           cambiarPagina(page,buscar,criterio) {

               let me = this;

               //Actualiza  la pagina actual

               me.pagination.current_page = page;

               me.listarCategoria(page, buscar, criterio);

           }
        },

        mounted() {
            //console.log('Component mounted.')
            this.listarCategoria(1,this.buscar,this.criterio);
        }
    }
</script>
