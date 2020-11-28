@extends('layouts.app')

@section('content')

<template v-if="selectedMenu=='menu-dashboard'">
<h1>contenido 0</h1>
</template>

 <template v-if="selectedMenu=='menu-balance'">
 <menu-balance/>

 </template>


 <template v-if="selectedMenu=='menu-matriz'">
 <example-component/>

 </template>

 <template v-if="selectedMenu=='menu-usuarios'">
 <h1>contenido 3</h1>

 </template>

@endsection
