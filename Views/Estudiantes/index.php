<?php include "Views/Templates/header.php"; ?>
    <div class="row">
        <div class="col text-left">
            <h1 class="text-white">Estudantes</h1>

        </div>
        <div class="col text-right">
            <button class="btn btn-primary mb-2 mr-4" onclick="frmEstudiante()"><i class="fa fa-plus"></i></button>
        </div>

    </div>
    <hr class="white-line">
<div class="row">
    <div class="col-lg-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive col-12">
                    <table class="table table-bordered table-hover table-striped mt-4" id="tblEst">
                        <thead class="thead-dark">
                            <tr>
                                <th class="bg-primary">#</th>
                                <th class="bg-primary">RM</th>
                                <th class="bg-primary">RA</th>
                                <th class="bg-primary">Nome</th>
                                <th class="bg-primary">Série</th>
                                <th class="bg-primary">Bairro</th>
                                <th class="bg-primary">Telefone</th>
                                <th class="bg-primary">Status</th>
                                <th class="bg-primary"></th>
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
<div id="nuevoEstudiante" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="title">Cadastrar estudante</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmEstudiante">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">RM</label>
                                <input type="hidden" id="id" name="id">
                                <input id="codigo" class="form-control" type="text" name="codigo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dni">RA</label>
                                <input id="dni" class="form-control" type="text" name="dni">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombre">Nome completo</label>
                                <input id="nombre" class="form-control" type="text" name="nombre" required >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="carrera">Serie</label>
                                <input id="carrera" class="form-control" type="text" name="carrera">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="telefono">Telefone</label>
                                <input id="telefono" class="form-control" type="text" name="telefono" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="direccion">Bairro</label>
                                <input id="direccion" class="form-control" type="text" name="direccion">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" onclick="registrarEstudiante(event)" id="btnAccion">Salvar</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Voltar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>