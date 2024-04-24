<?php include "Views/Templates/header.php"; ?>
<!--<div class="app-title">-->
<!--    <div>-->
<!--        <h1><i class="fa fa-dashboard"></i> Painel</h1>-->
<!--    </div>-->
<!--</div>-->
<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-small primary coloured-icon"><i style="background-color: #007bff" class="icon bg-purple fa fa-users fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Usuarios">
                <h4>Usuários</h4>
                <p><b><?php echo $data['usuarios']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small info coloured-icon"><i  style="background-color: #6610f2" class="icon bg-purple fa fa-book fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Libros">
                <h4>Livros</h4>
                <p><b><?php echo $data['libros']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small warning coloured-icon"><i  style="background-color: #6f42c1" class="icon bg-pink fa fa-address-book-o fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Autor">
                <h4>Autores</h4>
                <p><b><?php echo $data['autor']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small danger coloured-icon"><i  style="background-color: #e83e8c" class="icon bg-red fa fa-tags fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Editorial">
                <h4>Editoras</h4>
                <p><b><?php echo $data['editorial']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small warning coloured-icon"><i  style="background-color: #dc3545" class="icon bg-orange fa fa-graduation-cap fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Estudiantes">
                <h4>Estudantes</h4>
                <p><b><?php echo $data['estudiantes']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small danger coloured-icon"><i  style="background-color: #fd7e14" class="icon bg-yellow fa fa-hourglass-start fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Prestamos">
                <h4>Empréstimos</h4>
                <p><b><?php echo $data['prestamos']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small info coloured-icon"><i  style="background-color: #ffc107" class="icon bg-green fa fa-list-alt fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Materia">
                <h4>Matérias</h4>
                <p><b><?php echo $data['materias']['total'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="widget-small primary coloured-icon"><i  style="background-color: #28a745" class="icon bg-teal fa fa-cogs fa-3x"></i>
            <a class="info" href="<?php echo base_url; ?>Configuracion">
                <h6>Configuração</h6>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Livros disponiveis</h3>
            <div class="embed-responsive embed-responsive-16by9 bg-dark">
                <canvas class="embed-responsive-item" id="reportePrestamo"></canvas>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>