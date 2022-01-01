<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Service extends RestController 
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("Service_m", "service");
    }

    public function index_get()
    {
        
        $id_service=$this->get('id_service');

        $id_user=$this->get('id_user');
        $status=$this->get('status');

        if($id_service !== null&&$id_user===null&&$status===null)
        {
            $service = $this->service->getService($id_service);
        }else if($id_service === null&&$id_user!==null&&$status!==null)
        {
            $service = $this->service->getServiceUser($id_user,$status);
        }if($id_service === null&&$id_user===null&&$status!==null)
        {
            $service = $this->service->getServiceStatus($status);
        }else if($id_service===null&&$id_user===null&&$status==null){
            $service=$this->service->getService();
        }
      
        if($service){
            $this->response([
                'status' => true,
                'data' => $service
            ],self::HTTP_OK);
        }else {
            $this->response([
                'status' => false,
                'message' => 'not found'
            ],self::HTTP_NOT_FOUND);
        }
    }

   
    public function index_post()
    {
        date_default_timezone_set('Asia/Jakarta');
        $qLastId=$this->db->query("SELECT MAX(id_service) id FROM `service`");
        $str=$qLastId->row();
        $convert="KS".$str->id;

        $data =[
            'kode_service'=>$convert,
            'serial_number' => $this->post('serial_number'),
            'tgl_masuk' => (new DateTime('now'))->format('Y-m-d'),
            'tgl_keluar' => '000-00-00',
            'kendala' => $this->post('kendala'),
            'status' => "Service Baru",
            'kerusakan' => $this->post('kerusakan'),
            'kelengkapan'=>$this->post('kelengkapan'),
            'biaya'=>"0",
            'id_user' => $this->post('id_user')
        ];

          // cek upload image file
        if (!empty($_FILES["foto1"])) {
            $data["foto1"] = $this->_uploadFile($_FILES["foto1"]);
        } else {
            $data["foto1"] = "";
        }
         if (!empty($_FILES["foto2"])) {
            $data["foto2"] = $this->_uploadFile($_FILES["foto2"]);
        } else {
            $data["foto2"] = "";
        }

         if (!empty($_FILES["foto3"])) {
            $data["foto3"] = $this->_uploadFile($_FILES["foto3"]);
        } else {
            $data["foto3"] = "";
        }

        if ($this->service->addService($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new Service has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to add new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function edit_post()
    {
        $id = $this->post('id_service');
        $status=$this->post('status');
        $tglKeluar=$this->post('tgl_keluar');
    if($status==="Selesai"&&$tglKeluar==="0000-00-00"){
        $data =[
            'id_service'=>$this->post('id_service'),
            'kode_service'=>$this->post('kode_service'),
            'serial_number' => $this->post('serial_number'),
            'tgl_masuk' => $this->post('tgl_masuk'),
            'tgl_keluar' => (new DateTime('now'))->format('Y-m-d'),
            'kendala' => $this->post('kendala'),
            'status' => $this->post('status'),
            'kerusakan' => $this->post('kerusakan'),
            'kelengkapan'=>$this->post('kelengkapan'),
            'biaya'=>$this->post('biaya'),
            'id_user' => $this->post('id_user')
            ];
            // cek upload image file
            if (!empty($_FILES["foto1"])) {
                $data["foto1"] = $this->_uploadFile($_FILES["foto1"]);
            } 
            if (!empty($_FILES["foto2"])) {
                $data["foto2"] = $this->_uploadFile($_FILES["foto2"]);
           }

            if (!empty($_FILES["foto3"])) {
                $data["foto3"] = $this->_uploadFile($_FILES["foto3"]);
            } 
    }else{
            $data =[
            'id_service'=>$this->post('id_service'),
            'kode_service'=>$this->post('kode_service'),
             'serial_number' => $this->post('serial_number'),
            'tgl_masuk' => $this->post('tgl_masuk'),
            'tgl_keluar' => $this->post('tgl_keluar'),
            'kendala' => $this->post('kendala'),
            'status' => $this->post('status'),
            'kerusakan' => $this->post('kerusakan'),
            'kelengkapan'=>$this->post('kelengkapan'),
            'biaya'=>$this->post('biaya'),
            'id_user' => $this->post('id_user')
         ];
                   // cek upload image file
      // cek upload image file
            if (!empty($_FILES["foto1"])) {
                $data["foto1"] = $this->_uploadFile($_FILES["foto1"]);
            } 
            if (!empty($_FILES["foto2"])) {
                $data["foto2"] = $this->_uploadFile($_FILES["foto2"]);
           }

            if (!empty($_FILES["foto3"])) {
                $data["foto3"] = $this->_uploadFile($_FILES["foto3"]);
            } 
        }
        

        if ($this->service->updateService($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new Service has been modify'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to modify new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function delete_get()
    {
        $id = $this->get('id_service');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->service->deleteService($id) > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'deleted.'
                ],self::HTTP_CREATED);
            }else {
                $this->response([
                    'status' => false,
                    'message' => 'id not found!'
                ],self::HTTP_BAD_REQUEST);
            }
        }
    }


       // upload image
    private function _uploadFile($file)
    {
        $config['upload_path'] = './kelengkapan/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|doc|docx|pdf';
        $config['max_size']     = 15360;
        // $config['file_name'] = $this->post('id') . '-' . date('dmYHis') . '-' . basename($_FILES['abstrak']['name']);
        $this->load->library('upload', $config);

        $_FILES['file']['name'] = date('dmYHis')."_".str_replace("", "", basename($file['name']));
        $_FILES['file']['type'] = $file['type'];
        $_FILES['file']['tmp_name'] = $file['tmp_name'];
        $_FILES['file']['error'] = $file['error'];
        $_FILES['file']['size'] = $file['size'];

        if($this->upload->do_upload('file')){
            $file_name = $this->upload->data('file_name');
        }else{
            $file_name = "";
        }

        return $file_name;

    }
}