<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');

    $db = getDatabaseConnection();

    if (!$session->isLoggedIn() || $session->isSetup()){
        header('Location: index.php');
        die();
    }


drawHeader($session);
?>
<!--link po JAVASCRIPT!!!!! n sei s qrs o profile.js ou outro separado-->
<link rel="stylesheet" href="../CSS/profile.css" type="text/css">
<div class="account-setup title">Finalize your account setup <br></div>
    <div class="profile-container address-container">
        <form action="../actions/action_setup.php" method="post" class="profile-info">
            <div class="title">Billing Info</div>
            <div class="user-info">
                <h6>Address Settings</h6>
                <div class="user-setting">
                    <!--street, city, state, postalCode, country-->
                    <label for="street">Street:</label>
                    <input id="street" name="street" class="setting-input" type="text" value="" required>
                </div>
                <div class="user-setting">
                    <label for="postal-code">Postal Code:</label>
                    <input id="postal-code" name="postal-code" class="setting-input" type="text" value="" required>
                </div>
                <div class="user-setting">
                    <label for="country">Country:</label>
                    <input id="country" name="country" class="setting-input" type="text" value="" required>
                </div>
                <div class="user-setting">
                    <label for="state">State:</label>
                    <input id="state" name="state" class="setting-input" type="text" value="" required>
                </div>
                <div class="user-setting">
                    <label for="city">City:</label>
                    <input id="city" name="city" class="setting-input" type="text" value="" required>
                </div>
            </div>
            <button type="submit" class="submit-address" onclick="">Submit</button>
        </form>
    </div>

<?php
    drawFooter();
?>