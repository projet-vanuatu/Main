<?php
if(isset($data['formulaire'])){
    $btnNav = 'Retour';
    $action = 'Modifier';
    $actionForm = 'index.php?action=modifierSite';
}else{
    $btnNav = 'Gérer sites';
    $action = 'Ajouter';
    $actionForm = 'index.php?action=creerSite';
}
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h2><center>Ajouter site</center></h2>
        </div>           
        <div class="col-sm-4">
            <a href="index.php?action=gererSite"><button type="button" class="btn btn-primary" style="margin-top:25px; float:right;"><?php echo $btnNav; ?></button></a>
        </div>
    </div>
</div>
<div class="container" <?php echo ($action == 'Modifier') ? "style='pointer-events:none; opacity:0.7;'" : ""; ?>>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link acstive" href="index.php?action=creerSalle">Salles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?action=creerSite">Site</a>
        </li>
    </ul>
</div>
<br>
<div class="container">
    <div clas="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form action="<?php echo $actionForm; ?>" method="POST">
                <div class="form-group">
                  <label for="site">Nom du site :</label>
                  <input type="text" class="form-control" id="site" name="nomSite" placeholder="Manufacture de tabac.." required 
                         value='<?php echo isset($data['formulaire']['NomSITE']) ? $data['formulaire']['NomSITE'] : ""; ?>'>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="nAdr">Numéro :</label>
                            <input type="number" class="form-control" id="nAdr" name="numRue" placeholder="9" required 
                                   value='<?php echo isset($data['formulaire']['NumRue']) ? $data['formulaire']['NumRue'] : ""; ?>'>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="adr">Rue :</label>
                            <input type="text" class="form-control" id="adr" name="nomRue" placeholder="Rue des lois.." required  
                                   value='<?php echo isset($data['formulaire']['RueSite']) ? $data['formulaire']['RueSite'] : ""; ?>'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ville">Ville :</label>
                    <input type="text" class="form-control" id="ville" name="ville" placeholder="Toulouse.." required
                            value='<?php echo isset($data['formulaire']['Ville']) ? $data['formulaire']['Ville'] : ""; ?>'>
                </div>
                <div class="form-group">
                    <label for="usr">Code postal :</label>
                    <input type="text" class="form-control" id="cp" name="cp" placeholder="31100.." required
                            value='<?php echo isset($data['formulaire']['BoitePostale']) ? $data['formulaire']['BoitePostale'] : ""; ?>'>
                </div>
                <?php echo isset($data['formulaire']['IdSITE']) ? "<input type='hidden' value='".$data['formulaire']['IdSITE']."' name='IdSITE'>" : ""; ?>
                <input type="submit" class="btn btn-primary btn-block" value="<?php echo $action; ?>">
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>