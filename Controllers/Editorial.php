<?php
class Editorial extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Editora");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function listar()
    {
        $data = $this->model->getEditorial();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Ativo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarEdi(' . $data[$i]['id'] . ');"><i class="fa fa-pencil-square-o"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarEdi(' . $data[$i]['id'] . ');"><i class="fa fa-trash-o"></i></button>
                <div/>';
            } else {
                $data[$i]['estado'] = '<span class="badge badge-danger">Desativado</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarEdi(' . $data[$i]['id'] . ');"><i class="fa fa-reply-all"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $editorial = strClean($_POST['editorial']);
        $id = strClean($_POST['id']);
        if (empty($editorial)) {
            $msg = array('msg' => 'O nome é obrigatório', 'icono' => 'warning');
        } else {
            if ($id == "") {
                $data = $this->model->insertarEditorial($editorial);
                if ($data == "ok") {
                    $msg = array('msg' => 'Editora registrada', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'A editora já existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Erro ao registrar', 'icono' => 'error');
                }
            } else {
                $data = $this->model->actualizarEditorial($editorial, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Editora modificada', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Erro ao modificar', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar($id)
    {
        $data = $this->model->editEditorial($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar($id)
    {
        $data = $this->model->estadoEditorial(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Editora desativada', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Erro ao desativar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar($id)
    {
        $data = $this->model->estadoEditorial(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Editora restaurado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Erro ao restaurar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function buscarEditorial()
    {
        if (isset($_GET['q'])) {
            $valor = $_GET['q'];
            $data = $this->model->buscarEditorial($valor);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
}
