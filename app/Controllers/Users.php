<?php

namespace App\Controllers;

use App\Models\Modelusers;
use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelUser = new Modelusers();
        $data = $modelUser->findAll();
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
        $modelUser = new Modelusers();
        $data = $modelUser->orLike('id', $cari)->orLike('username', $cari)->get()->getResult();
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
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function checkLogin()
    {
        $modelUser = new Modelusers();
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        
        $validation = \Config\Services::validation();
        $validUsername = $this->validate([
            'username' => [
                'rules' => 'required[users.username]',
                'label' => 'Username User',
                'errors' => [
                        'required' => "{field} tidak boleh kosong",
                    ]
                ]
            ]);

            $validPassword = $this->validate([
                'password' => [
                    'rules' => 'required[users.password]',
                    'label' => 'Password',
                    'errors' => [
                            'required' => "{field} tidak boleh kosong"
                        ]
                    ]
                ]);

            if(!$validUsername){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("username"),
                ];
                return $this->respond($response, 404);
            }else if(!$validPassword){
                $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("password"),
                ];
                return $this->respond($response, 404);
            }
        
        $data = $modelUser->orLike('username', $username)->get()->getResult();
        
        foreach($data as $row){
            $row->id;
            $row->username;
            $row->password;
            $row->no_telp;
            $row->tgl_lahir;
            $row->email;
        }
        if(count($data) == 1) {
            $response = [
            'status' => 200,
            'error' => "false",
            'message' => 'Data Berhasil ditemukan!',
            'totaldata' => count($data),
            'data' => $data,
            ];
            // return $this->respond($response, 200);
            if($username == $row->username && $password == $row->password){
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => 'Berhasil Login',
                    'totaldata' => count($data),
                    'data' => $data,
                    ];
                    return $this->respond($response, 200);
            } else if ($username != $row->username) {
                $response = [
                    'status' => 404,
                    'error' => "true",
                    'message' => 'Username Salah',
                    'totaldata' => count($data)
                    ];
                    return $this->respond($response, 404);
            } else if ($password != $row->password) {
                $response = [
                    'status' => 404,
                    'error' => "true",
                    'message' => 'Password Salah',
                    'totaldata' => count($data)
                    ];
                    return $this->respond($response, 404);
            } else {
                $response = [
                    'status' => 404,
                    'error' => "false",
                    'message' => 'Gagal Login',
                    'totaldata' => count($data)
                    ];
                    return $this->respond($response, 404);
            }
        }else {
            return $this->failNotFound('maaf data ' . $username .' tidak ditemukan');
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $modelUser = new Modelusers();
        $id = $this->request->getPost("id");
        $nama = $this->request->getPost("nama");
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        $no_telp = $this->request->getPost("no_telp");
        $tgl_lahir = $this->request->getPost("tgl_lahir");
        $email = $this->request->getPost("email");
        $validation = \Config\Services::validation();
        $validId = $this->validate([
        'id' => [
            'rules' => 'is_unique[users.id]',
            'label' => 'ID User',
            'errors' => [
                'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        $validNama = $this->validate([
        'nama' => [
            'rules' => 'required[users.nama]',
            'label' => 'Nama',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

        $validUsername = $this->validate([
        'username' => [
            'rules' => 'is_unique[users.username]|required[users.username]',
            'label' => 'Username User',
            'errors' => [
                    'is_unique' => "{field} sudah ada",
                    'required' => "{field} tidak boleh kosong",
                ]
            ]
        ]);

        $validPassword = $this->validate([
        'password' => [
            'rules' => 'required[users.password]',
            'label' => 'Password',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);
        $validNo_telp = $this->validate([
            'no_telp' => [
                'rules' => 'required[users.no_telp]|max_length[12]|min_length[8]',
                'label' => 'Nomor Telepon',
                'errors' => [
                        'required' => "{field} tidak boleh kosong",
                        'max_length' => "{field} maksimal 12 karakter!",
                        'min_length' => "{field} minimal 8 karakter!"
                    ]
                ]
            ]);

        $validTgl_lahir = $this->validate([
        'tgl_lahir' => [
            'rules' => 'required[users.tgl_lahir]',
            'label' => 'Tanggal Lahir',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

       

        $validEmail = $this->validate([
            'email' => [
                'rules' => 'is_unique[users.email]|required[users.email]|valid_email[users.email]',
                'label' => 'Email User',
                'errors' => [
                        'is_unique' => "{field} sudah ada",
                        'required' => "{field} tidak boleh kosong",
                        'valid_email' => "{field} tidak sesuai format email"
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
        }else if(!$validUsername){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("username"),
            ];
            return $this->respond($response, 404);
        }else if(!$validPassword){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("password"),
            ];
            return $this->respond($response, 404);
       
        }else if(!$validNo_telp){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("no_telp"),
            ];
            return $this->respond($response, 404);
        }else if(!$validTgl_lahir){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("tgl_lahir"),
            ];
            return $this->respond($response, 404);
        }else if(!$validEmail){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("email"),
            ];
            return $this->respond($response, 404);
        }else{
            $modelUser->insert([
            'id' => $id,
            'nama' => $nama,
            'username' => $username,
            'password' => $password,
            'no_telp' => $no_telp,
            'tgl_lahir' => $tgl_lahir,
            'email' => $email,
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
        $model = new Modelusers();
        $data = [
        'nama' => $this->request->getVar("nama"),
        'username' => $this->request->getVar("username"),
        'password' => $this->request->getVar("password"),
        'no_telp' => $this->request->getVar("no_telp"),
        'tgl_lahir' => $this->request->getVar("tgl_lahir"),
        'email' => $this->request->getVar("email"),
        ];
        $data = $this->request->getRawInput();
        $validation = \Config\Services::validation();
        $validId = $this->validate([
        'id' => [
            'rules' => 'is_unique[users.id]',
            'label' => 'ID User',
            'errors' => [
                'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        $validNama = $this->validate([
        'nama' => [
            'rules' => 'required[users.nama]',
            'label' => 'Nama',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

        $validUsername = $this->validate([
        'username' => [
            'rules' => 'is_unique[users.username]|required[users.username]',
            'label' => 'Username User',
            'errors' => [
                    'is_unique' => "{field} sudah ada",
                    'required' => "{field} tidak boleh kosong",
                ]
            ]
        ]);

        $validPassword = $this->validate([
        'password' => [
            'rules' => 'required[users.password]',
            'label' => 'Password',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);
        $validNo_telp = $this->validate([
            'no_telp' => [
                'rules' => 'required[users.no_telp]|max_length[12]|min_length[8]',
                'label' => 'Nomor Telepon',
                'errors' => [
                        'required' => "{field} tidak boleh kosong",
                        'max_length' => "{field} maksimal 12 karakter!",
                        'min_length' => "{field} minimal 8 karakter!"
                    ]
                ]
            ]);

        $validTgl_lahir = $this->validate([
        'tgl_lahir' => [
            'rules' => 'required[users.tgl_lahir]',
            'label' => 'Tanggal Lahir',
            'errors' => [
                    'required' => "{field} tidak boleh kosong"
                ]
            ]
        ]);

       

        $validEmail = $this->validate([
            'email' => [
                'rules' => 'is_unique[users.email]|required[users.email]|valid_email[users.email]',
                'label' => 'Email User',
                'errors' => [
                        'is_unique' => "{field} sudah ada",
                        'required' => "{field} tidak boleh kosong",
                        'valid_email' => "{field} tidak sesuai format email"
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
        }else if(!$validUsername){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("username"),
            ];
            return $this->respond($response, 404);
        }else if(!$validPassword){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("password"),
            ];
            return $this->respond($response, 404);
       
        }else if(!$validNo_telp){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("no_telp"),
            ];
            return $this->respond($response, 404);
        }else if(!$validTgl_lahir){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("tgl_lahir"),
            ];
            return $this->respond($response, 404);
        }else if(!$validEmail){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("email"),
            ];
            return $this->respond($response, 404);
        }else {
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
        $modelUser = new Modelusers();
        $cekData = $modelUser->find($id);
        if($cekData) {
            $modelUser->delete($id);
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
