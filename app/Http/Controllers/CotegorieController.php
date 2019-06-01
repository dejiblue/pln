<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Http\Requests\CategorieRequest;
use App\Http\Requests\CategorieUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotegorieController extends Controller
{


    public function index()
    {
        
        $user = Auth::user() ; 
        $user->load('categories') ;
        return $this->successJson($user->categories()->get());

    }

    public function store(CategorieRequest $request)
    {
        
        $user = Auth::user() ; 
        $user->load('categories') ;
        $all = $request->all() ; 
        $user->categories()->create($request->only('name' ,'color')) ; 
        return $this->successJson($all);

    }

    public function show($id)
    {
        
        $user = Auth::user() ;
        $ca = Categorie::find( $id ) ; 
        $this->authorize('view', $ca ) ; 
        return $this->successJson( $ca );

    }

    public function update(CategorieRequest $request, $id)
    {
        
        $all = $request->all() ;
        $ca = Categorie::find( $id ) ; 
        $this->authorize('update', $ca ) ; 
        $only = array_filter( $request->only('name','color') );
        $ca->update( $only );
        return $this->successJson( $all );

    }

    public function destroy($id)
    {
        
        $ca = Categorie::find( $id ) ; 
        $this->authorize('delete', $ca ) ; 
        $ca->delete() ; 
        return $this->successJson( true );

    }
}