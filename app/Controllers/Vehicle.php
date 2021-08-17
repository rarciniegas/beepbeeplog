<?php

namespace App\Controllers;

use App\Models\VehicleModel;


class Vehicle extends BaseController
{
	public function index()
	{
        $data = [];
        helper(['form']);

        if ($this->request->getMethod() == 'post') {
			$rules = [
				'name' => 'required|min_length[3]|max_length[20]',
				'password' => 'required|min_length[6]|max_length[255]|validateVehicle[name, password]',
			];

            $errors = [
				'password' => [
					'validateVehicle' => 'Name and Password don\'t match'
				]
			];

			if (! $this->validate($rules, $errors)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new VehicleModel();
                $vehicle = $model->where('name', $this->request->getVar('name'))
											->first();

				$this->setVehicleSession($vehicle);
				return redirect()->to('dashboard');
			}
		}

		echo view('templates/header', $data);
        echo view('login');
        echo view('templates/footer');
	}

    private function setVehicleSession($vehicle){
		$data = [
			'id' => $vehicle['id'],
			'make' => $vehicle['make'],
			'model' => $vehicle['model'],
			'name' => $vehicle['name'],
			'isLoggedIn' => true,
		];

		session()->set($data);
		return true;
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

    public function profile() {

        $data = [];
		helper(['form']);
		$model = new VehicleModel();

        if ($this->request->getMethod() == 'post') {
			$rules = [
				'make' => 'required|min_length[3]|max_length[20]',
				'model' => 'required|min_length[3]|max_length[20]',	
			];

            if ($this->request->getPost('password') != '') {
                $rules['password'] = 'required|min_length[6]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
            }

			if (! $this->validate($rules)) {
				$data['validation'] = $this->validator;
			}else{
				$model = new VehicleModel();

				$newData = [
                    'id' => session()->get('id'),
					'make' => $this->request->getPost('make'),
					'model' => $this->request->getPost('model'),
                ];
                if ($this->request->getPost('password') != '') {
                    $newData['password'] = $this->request->getPost('password');
                    }
				
				$model->save($newData);
				
				session()->setFlashdata('success', 'Successfully Updated');
				return redirect()->to('/profile');

			}
		}

        $data['vehicle'] = $model->where('id', session()->get('id'))->first();
        echo view('templates/header', $data);
        echo view('profile');
        echo view('templates/footer');
    }

    public function logout(){
		session()->destroy();
		return redirect()->to('/');
	}

}


