<?php include "Views/Templates/header.php"; ?>
    <div class="row">
        <div class="col text-left">
            <h1 class="text-white">Usuarios</h1>

        </div>
        <div class="col text-right">
            <button class="btn btn-primary mb-2 mr-4" onclick="frmUsuario()"><i class="fa fa-plus"></i></button>
        </div>

    </div>
    <hr class="white-line">
<div class="row">
    <div class="col-lg-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tblUsuarios">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Nome</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="title">Novo Usuario</h5>
                                <button class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="frmUsuario">
                                    <div class="form-group">
                                        <label for="usuario">Usuario</label>
                                        <input type="hidden" id="id" name="id">
                                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario">
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre">Nome</label>
                                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del usuario">
                                    </div>
                                    <div class="row" id="claves">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="clave">Senha</label>
                                                <input id="clave" class="form-control" type="password" name="clave" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirmar">Confirmar senha</label>
                                                <input id="confirmar" class="form-control" type="password" name="confirmar" >
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="button" onclick="registrarUser(event);" id="btnAccion">Salvar</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="permisos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white">Atribuir permissões</h5>
                                <button class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="frmPermisos">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include "Views/Templates/footer.php"; ?>