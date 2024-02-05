<?php

    declare(strict_types=1);
    require_once(__DIR__ . '/../database/order.class.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');


    function drawProfile(Session $session, User $user, array $orders){
        require_once(__DIR__ . '/../database/connection.php');
        require_once(__DIR__ . '/../database/user.class.php');

        $db = getDatabaseConnection(); ?>

    <link rel="stylesheet" href="../CSS/profile.css" type="text/css">
    <script src="../js/profile.js" defer> </script>
    <div class="profile-container">
        <div class="card-profile">
            <div class="profilepic">
                <img class="profilepicImg" src="../<?php echo $user->image->path ?>" width="150" height="150" alt="Your profile pic" />
                <form action="../actions/action_update_pfp.php" method="post" class="profilepicContent" id="picform">
                    <input id="fileInput" name="pfp" accept=".jpg, .jpeg, .png" type="file">
                    <div class="profilepicIcon"><i class="fas fa-camera"></i></div>
                    <div class="profilepicText">Change Picture</div>
                </form>
            </div>
            <div class="username"><?= $user->username ?></div>
            <div class="profile-options">
                <a href="javascript:void(0)" onclick="selectActiveProfile(this)">
                    <div class="profile-type active-profile" id="user-profile-info">User settings</div>
                </a>
                <a href="javascript:void(0)" onclick="selectActiveProfile(this)">
                    <div class="profile-type" id="owner-profile-info">Restaurant settings</div>
                </a>
                <a href="javascript:void(0)" onclick="selectActiveProfile(this)">
                    <div class="profile-type" id="my-orders">My Orders</div>
                </a>
                <a href="javascript:void(0)" onclick="selectActiveProfile(this)">
                    <div class="profile-type" id="delete-account">Delete Account</div>
                </a>
            </div>
        </div>
        <div class="profile-info user-profile-info">
            <div class="title">My account</div>
            <form action="../actions/action_update.php" method="post" class="user-info-wo-pw">
                <div class="user-info">
                    <h6>User info</h6>
                    <div class="user-setting">
                        <label for="username">Username</label>
                        <input id="username" name="username" class="setting-input" type="text" value="<?php echo $user->username; ?>">
                        <span id="usernameError" class="error hiddenError">Invalid username</span>
                        <?php foreach ($session->getMessages() as $messsage) {
                            if($messsage['type'] != 'failed'){
                                break;
                            }
                            ?>
                            <span class="error4 <?= $messsage['type'] ?>">
                                <?= $messsage['text'] ?>
                            </span>
                        <?php } ?>
                        <!--<button onclick="editingSetting(this)" class="edit-button">Edit</button>-->
                    </div>
                    <div class="user-setting">
                        <label for="email-address">Email address</label>
                        <input id="email-address" name="email-address" class="setting-input" type="text" value="<?php echo $user->email; ?>">
                        <span id="emailError" class="error hiddenError">Invalid email</span>
                    </div>
                    <div class="user-setting">
                        <label for=full-name"">Full name</label>
                        <input class="setting-input" name="full-name" id="full-name" type="text" value="<?php echo $user->fullName; ?>">
                        <span id="nameError" class="error hiddenError">Invalid name</span>
                    </div>
                    <div class="user-setting">
                        <label for="phone-no">Phone number</label>
                        <input id="phone-no" name="phone-no" class="setting-input" type="text" value="<?php echo $user->phoneNo; ?>">
                        <span id="phoneError" class="error hiddenError">Invalid phone number</span>
                    </div>
                </div>
                <div class="billing-info">
                    <h6>Billing info</h6>
                    <div>
                        <label for="street">Address</label>
                        <input id="street" name="street" class="setting-input" type="text" value="<?php echo $user->address->street; ?>">
                    </div>
                    <div>
                        <label for="city">City</label>
                        <input id="city" name="city" class="setting-input" type="text" value="<?php echo $user->address->city; ?>">
                    </div>
                    <div>
                        <label for="state">State</label>
                        <input id="state" name="state" class="setting-input" type="text" value="<?php echo $user->address->state; ?>">
                    </div>
                    <div>
                        <label for="country">Country</label>
                        <input id="country" name="country" class="setting-input" type="text" value="<?php echo $user->address->country; ?>">
                    </div>
                    <div>
                        <label for="postal">Postal code</label>
                        <input id="postal" name="postal" class="setting-input" type="text" value="<?php echo $user->address->postalCode;?>">
                    </div>
                </div>
                <button class="submit-user-changes" type="submit" onclick="return validateUserInfo()">Save</button>
            </form>
            <div class="change-password user-info-wo-pw" >
                <div class="change-pw-header">
                    <h6>Change Password</h6><button id="button-pw" onclick="togglePWfORM(this)"><i class="fas fa-chevron-down"></i></button>
                </div>
                <form action="../actions/action_update_pw.php" method="post" class="new-password-settings nonselected password-info ">
                    <div class="user-setting">
                        <label for="currentPassword">Current Password</label>
                        <input id="currentPassword" name="currentPwd" class="setting-input" type="password">
                        <?php foreach ($session->getMessages() as $messsage) {
                            if($messsage['type'] != 'failedPw'){
                                break;
                            } ?>
                            <span class="error4 <?= $messsage['type'] ?>">
                                <?= $messsage['text'] ?>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="user-setting">
                        <label for="newPassword1">New Password</label>
                        <input id="newPassword1" name="newPassword1" class="setting-input"  type="password">
                        <span id="pw1Error" class="error hiddenError">Password must contain 1 uppercase, lowercase and special character and a number!!</span>
                    </div>
                    <div class="user-setting">
                        <label for="newPassword2">Confirm New Password</label>
                        <input id="newPassword2" name="newPassword2" class="setting-input" type="password">
                        <span id="pw2Error" class="error hiddenError">Passwords don't match!!</span>
                    </div>
                    <button class="submit-user-changes" onclick="return validateChangePWInputs()">Save</button>
                </form>
            </div>
        </div>
        <div class="profile-info owner-profile-info nonselected">
            <div class="title"> My Restaurants</div>
            <div class="restaurant-info-card">
                <?php $restaurants = Restaurant::getRestaurantsByOwnerUsername($db, $user->username);
                if(!empty($restaurants)){
                    foreach ($restaurants as $restaurant){?>
                    <div class="restaurant-card-profile">
                        <div class="restaurant">
                            <img class="restaurant-image" src="../<?= $restaurant->image->path ?>" width="150px" height="100px" alt="Your restaurant"/>
                            <div class="restaurant-desc">
                                <div class="restaurantName"><a href="../pages/restaurant.php?id=<?=$restaurant->restaurantID?>"><?=$restaurant->name?></a></div>
                                <div class="restaurantAddr"><i style="color: var(--light-red)" class="fas fa-map-marker-alt"></i><?= $restaurant->address->street?>, <?= $restaurant->address->postalCode?>, <?= $restaurant->address->city?>, <?= $restaurant->address->state?>, <?= $restaurant->address->country?></div>
                            </div>
                            <div class="context-buttons">
                                <a href="../pages/update_restaurant.php?id=<?=$restaurant->restaurantID?>" class="edit-menu">Edit Info</a>
                                <button class="edit-menu" onclick="dropDownOrders(this)">Check Orders</button>
                            </div>
                        </div>
                        <div class="restaurant-orders nonselected">
                        <div class="title">My Orders</div>
                            <table class="orders-table">
                                <thead>
                                    <tr class="table-header">
                                        <th>Order no.</th>
                                        <th>Delivery Status</th>
                                        <th>Price</th>
                                        <th>Order Date</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $orders = Order::getOrdersByRestID($db, $restaurant->restaurantID);
                                foreach($orders as $order) {?>
                                    <tr>
                                        <td><?=$order->orderID?></td>
                                        <td><?=$order->deliveryStatus?></td>
                                        <td><?=$order->price?>€</td>
                                        <td><?=$order->orderDate?></td>
                                        <td><?=$order->user->username?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }
                }
                else{ ?>
                    <p> You don't have any restaurants! </p>
                <?php }?>
                    <a href="../pages/new_restaurant.php" id="newRest">Create a new restaurant</a>
            </div>
        </div>
        <div class="profile-info my-orders nonselected">
            <div class="title">My Orders</div>
            <table class="orders-table">
                <thead>
                    <tr class="table-header">
                        <th>Order no.</th>
                        <th>Delivery Status</th>
                        <th>Price</th>
                        <th>Order Date</th>
                        <th>Restaurant Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($orders as $order) {?>
                    <tr>
                        <td><?=$order->orderID?></td>
                        <td><?=$order->deliveryStatus?></td>
                        <td><?=$order->price?>€</td>
                        <td><?=$order->orderDate?></td>
                        <td><?=$order->restaurant->name?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <div class="profile-info delete-account nonselected">
            <div class="title">Delete Account</div>
            <span>Are you sure you want to delete your account?</span>
            <span>Everything will be lost (Registered restaurants, order history, user info, etc)</span>
            <form action="../actions/action_delete.php" method="post">
                <label for="delete-pwd">Type your password to confirm</label>
                <input id="delete-pwd" type="password" required name="delete-pwd" placeholder="Password" />
                <button type="submit" onclick="">Delete account</button>
                <?php foreach ($session->getMessages() as $messsage) { ?>
                    <div class="error3 <?= $messsage['type'] ?>">
                        <?= $messsage['text'] ?>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
<?php } ?>