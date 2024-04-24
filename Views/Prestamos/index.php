<?php include "Views/Templates/header.php"; ?>
    <div class="row">
        <div class="col text-left">
            <h1 class="text-white">Empréstimos</h1>

        </div>
        <div class="col text-right">
            <button class="btn btn-primary mb-2 mr-4" onclick="frmPrestar()"><i class="fa fa-plus"></i></button>
        </div>

    </div>
    <hr class="white-line">

    <div class="tile">
    <div class="tile-body">
        <div class="table-responsive col-12">
            <table class="table table-bordered table-hover table-striped mt-4 " id="tblPrestar">
                <thead class="thead-dark">
                    <tr>
                        <th class="bg-primary">#</th>
                        <th class="bg-primary">Livro</th>
                        <th class="bg-primary">Estudante</th>
                        <th class="bg-primary">Emprestado em</th>
                        <th class="bg-primary">Devolução em</th>
                        <th class="bg-primary">Quantidade</th>
                        <th class="bg-primary">Nota/Observação</th>
                        <th class="bg-primary">Status</th>
                        <th class="bg-primary">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="prestar" class="modal fade" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="title">Emprestar livro</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmPrestar" onsubmit="registroPrestamos(event)">
                    <div class="form-group">
                        <label for="libro">Livro</label><br>
                        <select id="libro" class="form-control libro" name="libro" onchange="verificarLibro()" required style="width: 100%;">

                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="estudiante">Estudante</label><br>
                                <select name="estudiante" id="estudiante" class="form-control estudiante" required style="width: 100%;">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cantidad">Quantidade</label>
                                <input id="cantidad" class="form-control" min="1" type="number" name="cantidad" min="1" required onkeyup="verificarLibro()">
                                <strong id="msg_error"></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_prestamo">Emprestado em</label>
                                <input id="fecha_prestamo" class="form-control" type="date" name="fecha_prestamo" value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_devolucion">Devolução em</label>
                                <input id="fecha_devolucion" class="form-control" type="date" name="fecha_devolucion" value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observacion">Nota/Observação</label>
                        <textarea id="observacion" class="form-control" name="observacion" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit" id="btnAccion">Salvar</button>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>