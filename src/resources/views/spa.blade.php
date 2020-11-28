<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Finanzas</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        @auth
            window.user = {!! json_encode(Auth::user()) !!};
            window.user.current = {
                id_participante: {{ session('id_participante') }},
                role: '{{session('role')}}'
            }
        @endauth
    </script>
</head>

<body>
    @auth
    <div id="app">


        <v-app id="inspire">


            <v-navigation-drawer class="sidebar" v-model="drawer" app>
            



                <v-sheet color="#4f5d73" class="pa-4">
                <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-select
                 v-model="idpartselected"
                :items="items"
                item-text="Nombre"
                 item-value="id"
                 persistent-hint
                 @change="elegirpart"
                solo>
                </v-select>
                </v-col>
              
</v-row>
                </v-sheet>

                <v-divider></v-divider>

                <v-list>





                    <router-link :to="{ name: 'balance' }" class="routerlink">
                        <v-list-item class="item">
                            <v-list-item-icon>
                                <span class="iconify" data-icon="bi:table" data-inline="false"
                                    style="color: white;"></span>

                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title class="item">Balance </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>



                    <router-link :to="{ name: 'matriz' }" class="routerlink">
                        <v-list-item class="item">
                            <v-list-item-icon>
                                <span class="iconify" data-icon="mdi:grid" data-inline="false" style="color: white;"
                                    data-width="20" data-height="20"></span>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title class="item">Matriz IVT </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>





                    <router-link :to="{ name: 'dashboard' }" class="routerlink">
                        <v-list-item class="item">
                            <v-list-item-icon>
                                <span class="iconify" data-icon="bi:bar-chart-fill" data-inline="false"
                                    style="color: white;"></span>

                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title class="item">Dashboard </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>


                    @if(Auth::user()->hasRole("admin"))

                    <router-link :to="{ name: 'administracion' }" class="routerlink">
                        <v-list-item class="item">
                            <v-list-item-icon>
                                <span class="iconify" data-icon="mdi:account-group" data-inline="false"
                                    style="color: white;"></span>

                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title class="item">Administración </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                    @endif


                </v-list>





            </v-navigation-drawer>



            <v-app id="inspire">
                <v-app-bar app color="white" flat>


                    <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
                    <v-card>
                    <v-toolbar-title v-for="p in miparticipante" v-model="miparticipante" v-text="p.nombre" class="nombrepart"></v-toolbar-title></v-card>
              
                    <!-- size 50 -->
                    <v-avatar></v-avatar>

                    <v-tabs centered class="ml-n9" color="grey darken-1">
                        
                        <div class="titulo">
                            Sistema Finanzas

                        </div>
                        

                      
                    
                       
                        

                    </v-tabs>
                    


                  

                    <!-- Dropdown user-->
                    
                    <div class="text-center">
                        <v-menu offset-y>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="#4f5d73" dark v-bind="attrs" v-on="on">
                                    {{ Auth::user()->name }}

                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item class="ddlist">
                                    <v-list-item-title>
                                        <router-link :to="{ name: 'editprofile' }" class="ddlist">Editar Perfil
                                        </router-link>
                                    </v-list-item-title>

                                </v-list-item>
                            </v-list>

                            <v-list>
                                <v-list-item class="ddlist">
                                    <a href="{{ route('logout') }}" class="ddlist" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </v-list-item>
                            </v-list>

                        </v-menu>
                        
                    </div>

                    <!-- fin  Dropdown user -->

                </v-app-bar>

                <v-main class="main">


                    <router-view></router-view>



                </v-main>

                <v-footer class="footer">
                <v-toolbar-title v-for="p in miparticipante" v-model="miparticipante" v-text="p.nombre" class="nombrepartfooter"></v-toolbar-title></v-card>
                </v-footer>

            </v-app>


        </v-app>


    </div>
    @endauth

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>

<style>
.main {

    background-color: #D1FFFF !important;

}

.titulo {
    font-size: 40px;

}

.sub-titulo {
    font-size: 15px;

}

.footer {

    background-color: #c4c9d0 !important;

}

#inspire {

    background-color: #c4c9d0 !important;

}

.sidebar {

    background-color: #636f83 !important;

}

.routerlink {

    text-decoration: none;
    color: white !important;


}


.item:hover {
    background-color: #4f5d73 !important;


}

.item {


    color: white !important;



}



.ddlist {

    text-decoration: none;
    color: black !important;

}


.ddlist:hover {

    background-color: #4f5d73 !important;
    color: white !important;
}

.iconify {
    color: red;
}


.adm {
    position: absolute;
    background-color: yellow !important;

    bottom: 5px;
}

.nombrepartfooter{

    display:none;
}


@media (max-width: 600px) {
 .nombrepart{
     display:none;
 }

 .titulo{
    font-size:25px;
    margin:auto;

 }


 
.nombrepartfooter{

display:block;
}

}



</style>