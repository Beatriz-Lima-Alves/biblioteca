<?php include "Views/Templates/header.php"; ?>
    <div class="row">
        <div class="col text-left">
            <h1 class="text-white">Editora</h1>

        </div>
        <div class="col text-right">
            <button class="btn btn-primary mb-2 mr-4" onclick="frmEditorial()"><i class="fa fa-plus"></i></button>
        </div>

    </div>
    <hr class="white-line">
<div class="row">
    <div class="col-lg-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive col-12">
                    <table class="table table-bordered table-hover mt-4" id="tblEditorial">
                        <thead class="thead-dark">
                            <tr>
                                <th class="bg-primary">#</th>
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
<div id="nuevoEditorial" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="title">Salvar editora</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmEditorial">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editorial">Nome</label>
                                <input type="hidden" id="id" name="id">
                                <input id="editorial" class="form-control" type="text" name="editorial" required >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" onclick="registrarEditorial(event)" id="btnAccion">Salvar</button>
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