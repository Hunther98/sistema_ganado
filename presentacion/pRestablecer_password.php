<?php 
$error='';
$success='';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
}
?>

    <div class="">
        <form action="POST">
            <h2>restablecer contraseña</h2>
            <?php if ($error):?>
                <div class="error-mensaje"><?php echo $error; ?></div>
            <?php endif;?>
            <?php if ($success):?>
                <div class="success-mensaje"><?php echo $success; ?></div>
            <?php endif;?>

            <input type="password" name="nueva_password" placeholder="nueva contraseña" required>
            <input type="password" name="confirmar_password" placeholder="confirmar Nueva contraseña" required>
            <button type="submit">restablecer contraseña</button>
        </form>
    </div>
