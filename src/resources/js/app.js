/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 *
 *
 */

require("./bootstrap");
const Vue = require("vue");
window.Vue = Vue;
window.vue = Vue;

import Vuetify from "vuetify";
import VueRouter from "vue-router";

vue.use(VueRouter);
vue.use(Vuetify);

import PageBalance from "./pages/PageBalance.vue";
import PageMatrizIVT from "./pages/PageMatrizIVT.vue";
import PageDashboard from "./pages/PageDashboard.vue";
import PageEditProfile from "./pages/PageEditProfile.vue";
import PageAdministracion from "./pages/PageAdministracion.vue";
import PageImport from "./pages/PageImport.vue";
/**
 * Rutas
 */
const routes = [
    {
        path: "/balance",
        name: "balance",
        component: PageBalance
    },
    {
        path: "/matriz",
        name: "matriz",
        component: PageMatrizIVT
    },

    {
        path: "/dashboard",
        name: "dashboard",
        component: PageDashboard
    },

    {
        path: "/editprofile",
        name: "editprofile",
        component: PageEditProfile
    },
    
    {
        
        path: "/administracion",
        name: "administracion",
        component: PageAdministracion
    },
    {
        path: "/Import",
        name: "Import",
        component: PageImport        
    }
];


const router = new VueRouter({
    base: "/spa",
    mode: "history",
    routes: routes
});

//me impide pasar a administración si no soy administrador
router.beforeEach((to, from, next) => {
    if (to.name == 'administracion' && window.user.current.role !== 'admin') next({ name: 'balance' })
  // if the user is not authenticated, `next` is called twice
  next()

  })


// 1. Define route components.
// These can be imported from other files
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.app = new Vue({
    el: "#app",
    vuetify: new Vuetify(),
    router: router,

   // data: () => ({ drawer: false }),

   data: () => ({
    cards: ['Today', 'Yesterday'],
    drawer: false,
    items:[],
    idpartselected: window.user.current.id_participante,
    idparticipante: window.user.current.id_participante,
    idusuario: window.user.id,
    tst:[],
    miparticipante:[],
    

    
   


  }),

  created() {
      this.listarparticipantes();
      this.mostrarpart();
      
  },


  methods: {
      listarparticipantes(){
            axios
            .get("/api/v1/listpart", {
                params: {
                    id_usuario :  window.user.id,
                }
            })
            .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    // handle error
                    console.log(error);
                });
        
      },

      elegirpart(){
        axios
        .get("/setParticipante", {
            params: {
                id :  this.idpartselected,
            }
        }) .then(response => {
            location.reload();

        })
         .catch(error => {
                // handle error
                console.log(error);
            });

      },

      mostrarpart(){
        axios
        .get("/api/v1/participanteuser", {
            params: {
                id_participante :  this.idparticipante,
                id_usuario : this.idusuario
            }
        })
        .then(response => {
            this.miparticipante = response.data;

        })
         .catch(error => {
                // handle error
                console.log(error);
            });

      }


  },

    
});
