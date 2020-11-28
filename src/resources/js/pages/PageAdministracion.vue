<template>
    <v-container fluid>
         <div class="text-h4 transition-swing text-center">Administración</div>

        
        <!-- Datatable de usuarios -->
        <v-data-table :headers="headers" :items="usuarios">
            <template v-slot:top>
                <v-toolbar flat>
                    
                    <v-spacer></v-spacer>
                    <!-- modal para agregar usuarios-->
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                v-bind="attrs"
                                v-on="on"
                            >
                                Agregar usuario
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{
                                    "Agregar Usuario"
                                }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-alert text type="warning" v-model="alerta"
                                    >Debe completar todos los datos</v-alert
                                >
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="name"
                                                label="Nombre"
                                                required
                                            ></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="email"
                                                label="Correo"
                                                required
                                                :rules="rules"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="pass"
                                                label="Clave"
                                                required
                                               :type="show1 ? 'text' : 'password'"

                                            ></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="6" md="4">
                                            <v-select
                                                v-model="rol"
                                                :items="roles"
                                                item-text="nombre"
                                                item-value="id"
                                                label="Rol"
                                                persistent-hint
                                                single-line
                                                required
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancelar
                                </v-btn>
                                <v-btn color="blue darken-1" text @click="save">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- modal para editar -->
                    <v-dialog v-model="dialogedit" max-width="500px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ "editar" }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="newname"
                                                label="Nombre"
                                            ></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="6" md="4">
                                            <v-text-field
                                                v-model="newemail"
                                                label="Correo"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-select
                                                v-model="newrol"
                                                :items="roles"
                                                item-text="nombre"
                                                item-value="id"
                                                
                                                :label="newrolname"
                                                persistent-hint
                                                single-line
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closeedit"
                                >
                                    Cancelar
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="saveedit"
                                >
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- Modal para eliminar -->
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="headline"
                                >¿Realmente deseas borrar este
                                registro?</v-card-title
                            >
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closeDelete"
                                    >Cancelar</v-btn
                                >
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="eliminarusuario"
                                    >Ok</v-btn
                                >
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Modal detalle -->
                      <v-dialog v-model="dialogdet" max-width="500px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ "Empresas Asociadas" }}</span>
                            </v-card-title>

                            <v-card-text>
                              

                            <div class="text--primary"v-for="p in participantesasoc" v-model="participantesasoc" v-text="p.Nombre">
                            </div>
                             
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="closedet"
                                >
                                    Cancelar
                                </v-btn>
                               
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                </v-toolbar>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small  class="mr-2" @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
                  <v-icon small  class="mr-2" @click="detitem(item)">
                    mdi-eye
                </v-icon>

            </template>
        </v-data-table>
    </v-container>
</template>
<script>
export default {
    data: () => ({
        //regla para email
        rules: [
            value => (value || "").length <= 20 || "Max 20 characters",
            value => {
                const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return pattern.test(value) || "Invalid e-mail.";
            }

            
        ],
        show1: false,
        //estado de modals
        dialog: false,
        dialogedit: false,
        dialogDelete: false,
        dialogdet: false,
        //alerta agregar user
        alerta: false,
        //header de tabla
        headers: [
            { text: "Nombre", value: "name" },
            { text: "Email", value: "email" },
            { text: "Rol", value: "nombre" },

            { text: "Acciones", value: "actions", sortable: false }
        ],
        //datos para agregar nuevo elemento
        name: "",
        pass: "",
        email: "",
        rol: "",
        participante: window.user.current.id_participante,

        //llenar cbb roles
        roles: [
            { id: "1", nombre: "Admin" },
            { id: "2", nombre: "Generico" }
        ],
        //array donde llegan todos los usuarios asociados al participante de la sesión
        usuarios: [],
        editedIndex: -1,
        //Array donde llegan participantes para el detalle
        participantesasoc:[],
        //nuevos datos de form edit
        newname: "",
        newemail: "",
        newrol: "",
        newrolname:"",
        //rescata algunas ids
        id_usuario: "",
        id_rol_user: "",
        deleteid: ""
    }),

    computed: {},

    watch: {
        //modals
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },

        dialogedit(val) {
            val || this.closeedit();
        },

        dialogdet(val){
        val || this.closedet();


        }
    },

    created() {
        //carga los datos de la tabla
        this.initialize();
    },

    methods: {
       
        //toma los datos antiguos del user y permite ingresar nuevos
        editItem(item) {
            this.editedIndex = this.usuarios.indexOf(item);
            this.id_usuario = item.id_User;
            this.newname = item.name;
            this.newemail = item.email;
            this.newrol = item.id_Role;
             this.newrolname = item.nombre;
            this.dialogedit = true;
            
        },

        //toma la id de usuario a eliminar
        deleteItem(item) {
            this.id_usuario = item.id_User;
            this.id_rol_user = item.id_Role;
            this.dialogDelete = true;
        },
       //detalle de usuario - empresa asociada
        detitem(item){

         this.id_usuario = item.id_User;
         this.dialogdet = true;

          axios
            .get("/api/v1/listpart", {
                params: {
                    id_usuario :  item.id_User,
                }
            })
            .then(response => {
                    this.participantesasoc = response.data;
                })
                .catch(error => {
                    // handle error
                    console.log(error);
                });



        },

        
        //cierra y limpia los modals
        close() {
            this.dialog = false;
            (this.name = ""),
                (this.email = ""),
                (this.pass = ""),
                (this.rol = ""),
                (this.alerta = false);
        },

        closeedit() {
            this.dialogedit = false;
            this.$nextTick(() => {
                this.editedIndex = -1;
            });
        },
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedIndex = -1;
            });
        },

        closedet(){
        this.dialogdet = false;
        this.participantesasoc = [],
        this.id_usuario = ""

        },
        
        //CRUD 
         //carga lista de usuarios
        initialize() {
            axios
                .get("/api/v1/listar", {
                    params: {
                        id_participante: this.participante
                    }
                })
                .then(response => {
                    this.usuarios = response.data;
                })
                .catch(error => {
                    // handle error
                    console.log(error);
                });
        },

        eliminarusuario() {
            axios({
                method: "post",
                url: "/api/v1/delete",
                data: {
                    id_usuario: this.id_usuario,
                    id_participante: this.participante,
                    id_roluser: this.id_rol_user
                }
            })
                .then(response => {
                    // this.registros = response.data;
                    this.dialogDelete = false;
                    this.initialize();
                })
                .catch(error => {
                    // handle error
                    console.log(error);
                });
        },

        saveedit() {
            axios({
                method: "post",
                url: "/api/v1/update",
                data: {
                    id_usuario: this.id_usuario,
                    nombre: this.newname,
                    email: this.newemail,
                    id_rol: this.newrol,
                    id_participante: this.participante
                }
            })
                .then(response => {
                    // this.registros = response.data;
                    this.dialogedit = false;
                    this.initialize();
                })
                .catch(error => {
                    // handle error
                    console.log(error);
                });
        },

        save() {
            if (this.name == "" || this.password == "" || this.email == "") {
                this.alerta = true;
            } else {
                axios({
                    method: "post",
                    url: "/register",
                    data: {
                        name: this.name,
                        password: this.pass,
                        email: this.email,
                        rol: this.rol
                    }
                })
                    .then(response => {
                        // this.registros = response.data;
                        this.dialog = false;
                        this.initialize();
                    })
                    .catch(error => {
                        // handle error
                        console.log(error);
                    });
            }
        }
    }
};
</script>
<style lang="scss" scoped></style>
