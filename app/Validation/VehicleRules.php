<?php
namespace App\Validation;
use App\Models\VehicleModel;

class VehicleRules
{

  public function validateVehicle(string $str, string $fields, array $data){
    $model = new VehicleModel();
    $vehicle = $model->where('name', $data['name'])
                  ->first();

    if(!$vehicle)
      return false;

    //return true;
    return password_verify($data['password'], $vehicle['password']);
  }
}