<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Chat extends RestController 
{   

    function __construct()
    {
        parent::__construct();
        $this->load->model("Chat_m", "chat");
    }

    public function index_get()
    {
        $id = $this->get('id_service');
        // $status=$this->get('status');

        if($id !== null)
        {
            $chat = $this->chat->getchat($id);
        }
        else
        {
            $chat = $this->chat->getchat();
        }

        if($chat){
            $this->response([
                'status' => true,
                'data' => $chat
            ],self::HTTP_OK);
        }else {
            $this->response([
                'status' => false,
                'message' => 'not found'
            ],self::HTTP_NOT_FOUND);
        }
    }

    //    public function index_get()
    // {
    //     $id = $this->get('id_service');
    //     $iduser=$this->get('id_user');
    //     // $status=$this->get('status');

    //     if($id !== null&&$iduser!==null)
    //     {
    //         $chat = $this->chat->getchat($id);
    //         $user=$this->chat->getUser($iduser);
    //     }
    //     // else
    //     // {
    //     //     $chat = $this->chat->getchat();
    //     // }

    //     if($user&&$chat){
    //         // $this->response(["author"=>$user],self::HTTP_OK);
    //         echo json_encode(["author"=>$user,$chat]);
           
    //     }else {
    //         $this->response([
    //             'status' => false,
    //             'message' => 'not found'
    //         ],self::HTTP_NOT_FOUND);
    //     }
    // }

    public function index_post()
    {
        date_default_timezone_set('Asia/Jakarta');

        $data =[
            'id_service' => $this->post('id_service'),
            'text' => $this->post('text'),
            'tgl' => (new DateTime('now'))->format('Y-m-d H:i:s'),
            'id_user' => $this->post('id_user'),
            'status'=>$this->post('status'),
            'type'=>$this->post('type'),
            //status 0 untuk admin-><-customer,status 1 untuk teknisi-><-customer
        ];


        // cek upload image file
        if (!empty($_FILES["file"])) {
            $data["file"] = $this->_uploadFile($_FILES["file"]);
        } else {
            $data["file"] = "";
        }

        if ($this->chat->addChat($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new chat has been added.'
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
        $id = $this->post('id_chat');
        $data =[
            'id_service' => $this->post('id_service'),
            'text' => $this->post('text'),
            'tgl' => (new DateTime('now'))->format('Y-m-d H:i:s'),
            'id_user' => $this->post('id_user'),
            'status'=>$this->post('status'),
            'type'=>$this->post('type'),
        ];

         // cek upload image user
        if (!empty($_FILES["file"])) {
            if($_FILES["file"]["error"] == 0){
                $data["file"] = $this->_uploadFile($_FILES["file"]);
            }
        }

        if ($this->chat->updateChat($data, $id) > 0) {
            date_default_timezone_set('Asia/Jakarta');
            $this->response([
                'status' => true,
                'message' => 'new chat has been modify'
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
        $id = $this->get('id_chat');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->chat->deleteChat($id) > 0) {
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
        $config['upload_path'] = './chatFile/';
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