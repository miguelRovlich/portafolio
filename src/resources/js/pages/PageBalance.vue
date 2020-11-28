<template>
    <v-container fluid>
        <div class="text-h4 transition-swing text-center">Balance IVT</div>

        <!--filtros -->
        <v-row>
            <v-col cols="12" sm="3" med="2">
                <v-select
                    v-model="searchyear"
                    :items="years"
                    label="Año"
                    persistent-hint
                    single-line
                    dense
                ></v-select>
            </v-col>
            <v-col cols="12" sm="3" med="2">
                <v-select
                    v-model="searchmes"
                    :items="meses"
                    item-text="mes"
                    item-value="id"
                    label="Mes"
                    persistent-hint
                    single-line
                    dense
                ></v-select>
            </v-col>

            <!-- BUTTONS -->
            <v-col cols="12" sm="3" med="2">
                <v-btn @click="buscar">
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
                <v-btn @click="clear">
                    <v-icon>mdi-eraser</v-icon>
                </v-btn>
            </v-col>

            <!--fin filtros -->

            <!-- RESUMEN TABLA -->
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" med="2">
                <v-card class="mx" max-width="100%">
                    <v-card-title>Resumen de datos</v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="4" med="2">
                                <div class="text--primary">
                                    Total Registros:
                                    {{ resumentabla.totalregistros }}
                                </div>
                                <div class="text--primary">
                                    Total de instrucciones con pago asociado:
                                    {{ resumentabla.totalip }}
                                </div>
                            </v-col>

                            <v-col cols="12" sm="4" med="2">
                                <div class="text--primary">
                                    Total de documentos contabilizados:
                                    {{ resumentabla.totalcontab }}
                                </div>
                                <div class="text--primary">
                                    Documentos con diferencia Pago/IVT:
                                    {{ resumentabla.revisar }}
                                </div>
                            </v-col>
                            <v-col cols="12" sm="4" med="2">
                                <div class="text--primary">
                                    Mes: {{ resumentabla.messelected }}
                                </div>
                                <div class="text--primary">
                                    Año: {{ resumentabla.yearselected }}
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!--Tabla de datos -->
        <v-row>
            <v-col cols="12">
                <v-data-table
                    :headers="headers"
                    class="elevation-1"
                    :search="search"
                    sort-by="id"
                    multi-sort
                    dense
                    :no-data-text="nodata"
                    :loading="loading"
                    loading-text="Cargando... por favor espere"
                    :items="listafiltrada"
                    :items-per-page="100"
                    item-key="id"
                    :footer-props="{
                        'items-per-page-options': [100, 200, 500]
                    }"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-toolbar-title>
                            <v-divider class="mx-4" inset vertical></v-divider>

                            <v-spacer> </v-spacer>

                            <div v-if="listafiltrada.length > 0">
                                <json-excel
                                    :data="listafiltrada"
                                    :fields="jsonField"
                                    worksheet="BalanceIVT"
                                    :name="exportFileName"
                                    :before-generate="
                                        () => (downloading = true)
                                    "
                                    :before-finish="() => (downloading = false)"
                                >
                                    <v-btn
                                        color="primary"
                                        :loading="downloading"
                                        small
                                    >
                                        <v-icon>mdi-export-variant</v-icon>
                                        Exportar
                                    </v-btn>
                                </json-excel>
                            </div>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>

        <!--fin tabla -->
    </v-container>
</template>

<style>
/*cambio de idioma */
.v-data-footer__select:first-child div {
    visibility: visible;
}

.inp {
    margin: 0px !important;
}

.v-data-footer__select {
    visibility: hidden;
}

.v-data-footer__select:after {
    content: "Filas por página ";
    visibility: visible;
    margin: auto;
}

/*Ocultar botón agregar */

.add {
    visibility: hidden;
}
</style>

<script>
import dayjs from "dayjs";
import numeral from "numeral";
import "numeral/locales/es";
import JsonExcel from "vue-json-excel";

numeral.locale("es");

export default {
    data: () => ({
        loading: false,
        downloading: false,
        nodata: "Aún no hay datos para mostrar",
        info_user: "",

        years: [],
        meses: [
            { id: "1", mes: "Enero" },
            { id: "2", mes: "Febrero" },
            { id: "3", mes: "Marzo" },
            { id: "4", mes: "Abril" },
            { id: "5", mes: "Mayo" },
            { id: "6", mes: "Junio" },
            { id: "7", mes: "Julio" },
            { id: "8", mes: "Agosto" },
            { id: "9", mes: "Septiembre" },
            { id: "10", mes: "Octubre" },
            { id: "11", mes: "Noviembre" },
            { id: "12", mes: "Diciembre" }
        ],

        searchmes: "",
        messelected: [],
        searchyear: "",
        searchparticipante: window.user.current.id_participante,
        searchmodo: "d",
        search: "",
        date: new Date().toISOString().substr(0, 10),

        //headers columnas de tabla
        headers: [
            { text: "Concepto", value: "Concepto" },
            { text: "Proveedor", value: "acreedor.Nombre" },
            { text: "Concepto Balance", value: "matriz_pago.NaturalKey" },
            { text: "Valor Neto", value: "MontoNeto", filterable: false },
            { text: "Total", value: "MontoBruto", filterable: false },
            { text: "Rut", value: "acreedor.Rut" },
            { text: "N° Factura", value: "libro_diario_pago.Folio" },
            // { text: "Fecha Carga", value: "fechacarga" },
            { text: "Fecha Pago", value: "libro_diario_pago.Fecha" },
            { text: "N° Egreso", value: "libro_diario_pago.Correlativo" },
            {
                text: "Monto Pagado",
                value: "libro_diario_pago.control",
                filterable: false
            },
            { text: "Diferencia Pago/IVT", value: "diffPago" },
            {
                text: "Fecha Contabilización",
                value: "libro_diario_contab.Fecha"
            },
            { text: "N° Traspaso", value: "libro_diario_contab.Correlativo" },
            {
                text: "Monto Contabilizado",
                value: "libro_diario_contab.control"
            },
            { text: "¿Contabilizado? (Si/No)", value: "isContab" }
        ],
        listafiltrada: [],
        infotabla: [],
        resumentabla: {
            totalregistros: 0,
            totalip: 0,
            totalcontab: 0,
            revisar: 0,
            messelected: "",
            yearselected: ""
        },
        headresumen: [
            { text: "Total de datos", value: "total" },
            { text: "Instrucción pago", value: "ip" }
        ]
    }),

    components: {
        JsonExcel
    },

    computed: {
        jsonField() {
            let fields = {
                Año: "year",
                Mes: "month"
            };

            this.headers.forEach(h => {
                fields[h.text] = h.value;
            });

            return fields;
        },

        exportFileName() {
            return "BalanceIVT" + this.searchyear + this.searchmes + ".xls";
        }
    },

    //modals
    watch: {
        dialog(val) {
            val || this.close();
        },

        dialogDelete(val) {
            val || this.closeDelete();
        },

        menu(val) {
            val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
        }
    },

    created() {
        // set default year
        let now = dayjs();
        this.searchyear = now.year();

        //set default month
        let x = dayjs();
        let mesactual = x.month() + 1;
        this.searchmes = mesactual.toString();

        //crear rango años
        this.years = _.rangeRight(2017, now.year() + 1);
    },

    methods: {
        //mantiene la fecha seleccionada
        savedate(date) {
            this.$refs.menu.save(date);
        },

        buscar() {
            this.resumentabla.totalcontab = 0;
            this.resumentabla.revisar = 0;
            this.resumentabla.totalip = 0;
            this.resumentabla.totalregistros = 0;
            this.listafiltrada = [];
            this.loading = true;
            this.resumentabla.yearselected = "";
            this.resumentabla.messelected = "";
            document.body.style.cursor = "wait";

            axios
                .get("/api/v1/bce", {
                    params: {
                        id_participante: this.searchparticipante,
                        year: this.searchyear,
                        month: this.searchmes,
                        modo: this.searchmodo
                    }
                })
                .then(response => {
                    if (response.data.data.length === 0) {
                        this.loading = false;
                        this.nodata = "No hay datos para mostrar";
                    }

                    this.listafiltrada = this.processAfterFetch(response);

                    //resumen data
                    this.resumentabla.totalregistros = this.listafiltrada.length;
                    this.resumentabla.messelected = this.meses[
                        this.searchmes - 1
                    ].mes;
                    this.resumentabla.yearselected = this.searchyear;
                })
                .catch(error => {
                    console.log(error);
                })
                .then(() => {
                    this.loading = false;
                    document.body.style.cursor = "default";
                });
        },

        processAfterFetch(response) {
            let month = this.searchmes;
            let year = this.searchyear;

            return response.data.data.map(d => {
                // agrego a cada registro mes y anio que vemos
                d.month = month;
                d.year = year;

                //formateo rut
                d.acreedor.Rut = numeral(d.acreedor.Rut).format("0,0");
                d.acreedor.Rut = d.acreedor.Rut + "-" + d.acreedor.DV;

                // PAGO - Libro Diario
                if (
                    d.hasOwnProperty("libro_diario_pago") &&
                    d.libro_diario_pago !== null
                ) {
                    d.libro_diario_pago.control = numeral(
                        Math.abs(
                            d.libro_diario_pago.Debe - d.libro_diario_pago.Haber
                        )
                    ).format("$0,0");

                    //formateofecha
                    d.libro_diario_pago.Fecha = dayjs(
                        d.libro_diario_pago.Fecha
                    ).format("DD-MM-YYYY");

                    this.resumentabla.totalip = this.resumentabla.totalip + 1;
                }

                // Contabilización
                if (
                    d.hasOwnProperty("libro_diario_contab") &&
                    d.libro_diario_contab !== null
                ) {
                    //formateofecha
                    d.libro_diario_contab.Fecha = dayjs(
                        d.libro_diario_contab.Fecha
                    ).format("DD-MM-YYYY");

                    d.libro_diario_contab.control = numeral(
                        Math.abs(
                            d.libro_diario_contab.Debe -
                                d.libro_diario_contab.Haber
                        )
                    ).format("$0,0");

                    d.isContab =
                        d.libro_diario_contab.Correlativo != "" ? "Si" : "No";

                    //resumen data
                    this.resumentabla.totalcontab =
                        this.resumentabla.totalcontab + 1;
                } else {
                    d.isContab = "No";
                }

                // TIENE PAGO Y CONTAB
                if (
                    d.hasOwnProperty("libro_diario_pago") &&
                    d.libro_diario_pago !== null &&
                    d.hasOwnProperty("libro_diario_contab") &&
                    d.libro_diario_contab !== null
                ) {
                    // diff con pagado & contabilizado
                    let diffContabPago =
                        d.isContab == "Si"
                            ? Math.abs(
                                  d.libro_diario_contab.control -
                                      d.libro_diario_pago.control
                              )
                            : 0;
                    let diffContabIVT = Math.abs(
                        d.libro_diario_contab.control - d.MontoBruto
                    );

                    d.diffPago =
                        diffContabPago > 3 || diffContabIVT > 3
                            ? "REVISAR"
                            : "OK";
                    //resumen data

                    if (d.diffPago == "REVISAR") {
                        this.resumentabla.revisar =
                            this.resumentabla.revisar + 1;
                    }
                }

                // formateo de valores
                d.MontoNeto = numeral(d.MontoNeto).format("$0,0");
                d.MontoBruto = numeral(d.MontoBruto).format("$0,0");

                if (
                    d.hasOwnProperty("contabilizacion") &&
                    d.libro_diario_contab !== null
                ) {
                    d.libro_diario_contab.control = numeral(
                        d.libro_diario_contab.control
                    ).format("$0,0");
                }

                if (
                    d.hasOwnProperty("libro_diario") &&
                    d.libro_diario_pago !== null
                ) {
                    d.libro_diario_pago.control = numeral(
                        d.libro_diario_pago.control
                    ).format("$0,0");
                }

                // concepto
                if (d.hasOwnProperty("matriz_pago") && d.matriz_pago !== null) {
                    d.Concepto =
                        d.matriz_pago.TipoPago == "L" ? "IVT" : "RELIQ";
                } else {
                    d.Concepto = null;
                }

                return d;
            });
        },

        //limpiar filtros
        clear() {
            // set default year
            let now = dayjs();
            this.searchyear = now.year();

            //set default month
            let x = dayjs();
            let mesactual = x.month() + 1;
            this.searchmes = mesactual.toString();
        }
    }
};
</script>
