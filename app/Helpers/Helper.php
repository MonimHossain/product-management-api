<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class Helper
{
    public function getModel($model)
    {
        $ModelName = 'App\\Models\\' . $model;
        $model = new $ModelName;
        return $model;
    }

    public function show($model, $relationship = null, $single_id = null, $page = null, $withoutChuck = false)
    {
        $model = $this->getModel($model);

        if ($relationship) {
            $data = $model->with($relationship);
        } else {
            $data = $model;
        }
 
        if ($single_id) {
            $data = $data->where('id', $single_id)->first();
            return $data;
        }
        
        if($withoutChuck){
            $data = $data->orderby('id', 'desc')->get();
        }else{
            $data = $data->orderby('id', 'desc')->paginate(10);
        }

        return $data;
    }

    public function create(Request $request, $model, $fileUpload = false, $fileInputNames = ['image'], $path = 'uploads', $exceptFieldsArray = [])
    {
        $model = $this->getModel($model);

        $storeInput = $request->except($exceptFieldsArray);
        $insertedData = $model::create($storeInput);

        if ($fileUpload) {
            $this->fileUploadOperation($request, $fileInputNames, $path, $insertedData);
        }

        return $insertedData;
    }

    public function update(Request $request, $id, $model, $exceptFieldsArray = [], $fileUpload = false, $fileInputNames = ['image'], $path = 'uploads')
    {
        $model = $this->getModel($model);

        $updatedInput = $request->except($exceptFieldsArray);
        $data = $model::where('id', $id)->update($updatedInput);
        $data = $model::find($id);

        if ($fileUpload) {
            $this->fileUploadOperation($request, $fileInputNames, $path, $data);
        }

        return $data;
    }

    public function delete($id, $model)
    {
        $model = $this->getModel($model);

        $model->where('id', $id)->delete();

        return "Data deleted successfully!";
    }

    public function fileUploadOperation(Request $request, $fileInputNames, $path, $data)
    {
        foreach ($fileInputNames as $fileInputName) {

            if ($request->hasFile($fileInputName) && $request->has($fileInputName)) {
                $filename = $this->uploadFile($request, $fileInputName, $path);
                $filePath = '/' . $path . '/' . $filename;
                $data->$fileInputName = json_encode($filePath);
                $data->save();
            }
        }
    }

    public function uploadFile($request, $inputName = 'image', $path = 'uploads')
    {
        $filename = '';

        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $filename = time() . '_' . rand(10, 100) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
        }

        return $filename;
    }

    public function getUserByEmail($model, $email)
    {
        $model = $this->getModel($model);
        return $model->where('email', $email)->first();
    }
}
