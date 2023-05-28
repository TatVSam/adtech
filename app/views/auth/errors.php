<?php
echo '<p>При регистрации произошли следующие ошибки:</p><br>';
            foreach($errors as $error) {
                echo '<small class="form-text text-muted">' .
                $error .'</small>';
            }