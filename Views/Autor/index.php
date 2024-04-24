<?php include "Views/Templates/header.php"; ?>
    <div class="row">
        <div class="col text-left">
            <h1 class="text-white">Autores</h1>

        </div>
        <div class="col text-right">
            <button class="btn btn-primary mb-2 mr-4" onclick="frmAutor()"><i class="fa fa-plus"></i></button>
        </div>

    </div>
    <hr class="white-line">

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive col-12">
                    <table class="table table-bordered table-hover" id="tblAutor">
                        <thead class="thead-dark">
                            <tr>
                                <th class="bg-primary">#</th>
                                <th class="bg-primary">Foto</th>
                                <th class="bg-primary">Nome</th>
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
<div id="nuevoAutor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="title">Registrar um autor</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmAutor" onsubmit="registrarAutor(event)">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="autor">Nome do autor</label>
                                <input type="hidden" id="id" name="id">
                                <input id="autor" class="form-control" type="text" name="autor" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Foto</label>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <input type="hidden" id="foto_actual" name="foto_actual">
                                        <label for="imagen" id="icon-image" class="btn btn-primary"><i class="fa fa-cloud-upload"></i></label>
                                        <span id="icon-cerrar"></span>
                                        <input id="imagen" class="d-none" type="file" name="imagen" onchange="preview(event)">
                                        <img class="img-thumbnail" id="img-preview" width="150">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" id="btnAccion">Salvar</button>
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