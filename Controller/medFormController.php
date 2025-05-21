<?php
require_once '../db.php';
require_once '../model/medForm.php';

class medFormController {
    private $model;

    public function __construct() {
        $db = new Database();
        $this->model = new medForm($db->connect());
    }

    public function index() {
        $forms = $this->model->getAll();
        include 'pages/med_form.php';
    }

    public function show($id) {
        $form = $this->model->getById($id);
        include 'pages/med_form_detail.php';
    }

    public function store($postData) {
        $this->model->create($postData);
        header("Location: ../pages/med_form.php");
    }
}
?>
