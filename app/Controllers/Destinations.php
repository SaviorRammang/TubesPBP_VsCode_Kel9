<?php

namespace App\Controllers;

use App\Models\Modeldestinations;
use CodeIgniter\RESTful\ResourceController;

class Destinations extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelDes = new Modeldestinations();
        $data = $modelDes->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        return $this->respond($response, 200);
    }

    public function show($cari = null){
        $modelDes = new Modeldestinations();
        $data = $modelDes->orLike('id', $cari)->orLike('nama', $cari)->get()->getResult();
        if(count($data) > 1) {
            $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
            ];
            return $this->respond($response, 200);
        }else if(count($data) == 1) {
            $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
            ];
            return $this->respond($response, 200);
        }else {
            return $this->failNotFound('maaf data ' . $cari .' tidak ditemukan');
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $modelDes = new Modeldestinations();
        $id = $this->request->getPost("id");
        $nama = $this->request->getPost("nama");
        $tglberangkat = $this->request->getPost("tanggal_berangkat");
        $tglpulang = $this->request->getPost("tanggal_pulang");
        $harga = $this->request->getPost("harga");
        $deskripsi = $this->request->getPost("deskripsi");
        $validation = \Config\Services::validation();
        $validId = $this->validate([
            'id' => [
                'rules' => 'is_unique[destinations.id]',
                'label' => 'ID Destination',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                    ]
                ]
            ]);
    
            $validNama = $this->validate([
            'nama' => [
                'rules' => 'required[destinations.nama]',
                'label' => 'Nama',
                'errors' => [
                        'required' => "{field} tidak boleh kosong"
                    ]
                ]
            ]);
    
            $validHarga = $this->validate([
                'harga' => [
                    'rules' => 'required[destinations.harga]',
                    'label' => 'Harga',
                    'errors' => [
                            'required' => "{field} tidak boleh kosong"
                        ]
                    ]
                ]);
    
            $validDeskripsi = $this->validate([
            'deskripsi' => [
                'rules' => 'required[destinations.deskripsi]',
                'label' => 'Deskripsi',
                'errors' => [
                        'required' => "{field} tidak boleh kosong"
                    ]
                ]
            ]);

            if(!$validId){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("id"),
                ];
                return $this->respond($response, 404);
            }else if(!$validNama){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("nama"),
                ];
                return $this->respond($response, 404);
            }else if(!$validHarga){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("harga"),
                ];
                return $this->respond($response, 404);
            }else if(!$validDeskripsi){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("deskripsi"),
                ];
                return $this->respond($response, 404);
            } else {
            $modelDes->insert([
            'id' => $id,
            'nama' => $nama,
            'tanggal_berangkat' => $tglberangkat,
            'tanggal_pulang' => $tglpulang,
            'harga' => $harga,
            'deskripsi' => $deskripsi,
            ]);
            $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Data berhasil disimpan"
            ];
            return $this->respond($response, 201);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $model = new Modeldestinations();
        $data = [
        'nama' => $this->request->getVar("nama"),
        'tanggal_berangkat' => $this->request->getVar("tanggal_berangkat"),
        'tanggal_pulang' => $this->request->getVar("tanggal_pulang"),
        'harga' => $this->request->getVar("harga"),
        'deskripsi' => $this->request->getVar("deskripsi"),
        ];
        $data = $this->request->getRawInput();
        $validation = \Config\Services::validation();
        $validId = $this->validate([
        'id' => [
            'rules' => 'is_unique[destinations.id]',
            'label' => 'ID Destination',
            'errors' => [
                'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        $validNama = $this->validate([
        'nama' => [
            'rules' => 'required[destinations.nama]',
            'label' => 'Nama',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

        $validHarga = $this->validate([
            'harga' => [
                'rules' => 'required[destinations.harga]',
                'label' => 'Harga',
                'errors' => [
                        'required' => "{field} tidak boleh kosong"
                    ]
                ]
            ]);

        $validDeskripsi = $this->validate([
        'deskripsi' => [
            'rules' => 'required[destinations.deskripsi]',
            'label' => 'Deskripsi',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

        if(!$validId){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("id"),
            ];
            return $this->respond($response, 404);
        }else if(!$validNama){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("nama"),
            ];
            return $this->respond($response, 404);
        }else if(!$validHarga){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("harga"),
            ];
            return $this->respond($response, 404);
        }else if(!$validDeskripsi){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("deskripsi"),
            ];
            return $this->respond($response, 404);
        } else {
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Data Anda dengan id $id berhasil dibaharukan"
                ];
            return $this->respond($response);
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $modelDes = new Modeldestinations();
        $cekData = $modelDes->find($id);
        if($cekData) {
            $modelDes->delete($id);
            $response = [
            'status' => 200,
            'error' => null,
            'message' => "Selamat data sudah berhasil dihapus"
            ];
            return $this->respondDeleted($response);
        }else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
