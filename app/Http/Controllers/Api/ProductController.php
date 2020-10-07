<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\API\ApiError;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function index(){
        return response()->json($this->product->paginate(10));
    }

    public function show($id){

        $product = $this->product->find($id);

        if(!$product) return response()->json(['data' =>['msg' => 'Produto não encontrado']], 404);


        $data = ['data' => $product];
        return response()->json($data);
    }
    
    public function store(Request $request){
        try {
            $productData = $request->all();
            $this->product->create($productData);

            $return = ['data' => ['msg' => 'Produto adicionado com sucesso!']];

            return response()->json($return, 201);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro na operação de adicionar", 1010));
            
        }

    }
    public function update(Request $request, $id){
        try {
            $productData = $request->all();
            $product = $this->product->find($id);
            $product->update($productData);

            $return = ['data' => ['msg' => 'Produto atualizado com sucesso!']];

            return response()->json($return, 201);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1020), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro na operação de atualizar", 1020));
            
        }
    }
    public function delete($id){

        $product = $this->product->find($id);

        if(!$product) return response()->json(['data' =>['msg' => 'Produto não encontrado']], 404);

        try {
            $product->delete();
            return response()->json(['data' => ['msg' => 'Produto '. $product->name . ' deletado com sucesso']], 200);
        } catch (\Exception $e) {
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1030), 500);
            }
            return response()->json(ApiError::errorMessage("Houve um erro na operação de remover", 1030));
            
        }
    }
}
