<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Icon -->
        <div class="fadeIn first">
            <img src="Public/Images/logo.JPG" id="icon" alt="User Icon" />
        </div>
        <!-- Login Form -->
        <form action="index.php?action=connexion" method="POST" name="connexion">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8" style="padding:20px;">
                    <div class="fadeIn second form-group">
                        <!--<label for="email">Email address:</label>-->
                        <input type="text" class="form-control" id="identifiant" name="id" placeholder="Identifiant" style="text-align:center;">
                    </div>
                    <div class="fadeIn third form-group">
                        <!--<label for="pwd">Password:</label>-->
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe" style="text-align:center;">
                    </div>
                    <button type="submit" class="btn btn-lg btn-block btn-sm" style="background-color: #FCA848;">Connexion</button>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </form>    
    </div>
</div>