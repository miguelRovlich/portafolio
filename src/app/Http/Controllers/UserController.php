<?php

namespace App\Http\Controllers;
use App\User;
use App\User_Role;
use Illuminate\Http\Request;
use Validator;  
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listar(Request $request){
        $id_participante = $request->input('id_participante');

        $usuarios = User_Role::Join('db_PortalPagos.users', 'users.id', '=', 'id_User')
       ->Join('db_PortalPagos.Roles', 'Roles.id', '=', 'id_Role' )
        ->where('id_Participante', $id_participante)
        ->get(['name','email','nombre','User_Role.id_User','User_Role.id','User_Role.id_Role']);
        return response()->json($usuarios);

    }


    public function update(Request $request){
        $id_usuario = $request->input('id_usuario');
        $nombre = $request->input('nombre');
        $email = $request->input('email');
        $rol = $request->input('id_rol');
        $participante = $request->input('id_participante');
          //actualiza datos de participante

          $upd = User_Role::Join('db_PortalPagos.users', 'users.id', '=', 'id_User')
            ->where('users.id', $id_usuario)
            ->where('id_Participante',$participante)
            ->update(['name' => $nombre,'email' => $email,'User_Role.id_Role' => $rol]);

    }
   

    public function delete(Request $request){
        $id_usuario = $request->input('id_usuario');

        $id_participante = $request->input('id_participante');
        $id_roluser = $request->input('id_roluser');


                $user = User::findOrFail($id_usuario);
                //$user->answers()->delete();
            
                $user->roles()->detach($id_roluser, ['id_Participante',$id_participante]);


                $roluser =  User_Role::all()->where('id_User',$id_usuario);
                

                

                if($roluser == "[]"){
                    $user->delete();

                    return response()->json([
                        'message' => ['msje' => ['eliminado de la bd']]
                    ], 200);
                    

                }else{
                    return response()->json([
                        'message' => ['msje' => ['eliminado del participante']]
                    ], 200);

                }

                

            }



     public function listarpart(Request $request){
        $id_usuario = $request->input('id_usuario');
       $partasoc = User_Role::Join('db_PortalPagos.Participantes','Participantes.id', '=','id_Participante')
       ->where('id_User',$id_usuario)
        ->get();

        return response()->json($partasoc);

         }


    public function miparticipante(Request $request){
        $id_usuario = $request->input('id_usuario');
        $id_participante = $request->input('id_participante');

        $part = User_Role::Join('db_PortalPagos.Participantes','Participantes.id', '=','id_Participante')
        ->where('id_User',$id_usuario)
        ->where('id_Participante',$id_participante)
         ->get('nombre'); 

         return response()->json($part);

    
    
    }


}
