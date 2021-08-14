<?php

namespace App\Controllers;

use App\Models\VehicleModel;


class Vehicle extends BaseController
{
	public function index()
	{
        $data = [];
        helper(['form']);
		echo view('templates/header', $data);
        echo view('login');
        echo view('templates/footer');
	}

    public function register()
	{
        $data = [];
        helper(['form']);

        if ($this->request->getMethod() == 'post') {
			$rules = [
				'make' => 'required|min_length[3]|max_length[20]',
				'model' => 'required|min_length[3]|max_length[20]',
				'name' => 'required|min_length[3]|max_length[20]|is_unique[vehicle.name]',
				'password' => 'required|min_length[6]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new VehicleModel();

				$newData = [
					'make' => $this->request->getVar('make'),
					'model' => $this->request->getVar('model'),
					'name' => $this->request->getVar('name'),
					'password' => $this->request->getVar('password'),
				];
				$model->save($newData);
				$session = session();
				$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('/');

			}
		}

		echo view('templates/header', $data);
        echo view('register');
        echo view('templates/footer');
	}
}
