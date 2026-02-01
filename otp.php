<?php

?>

<div class="otp-box">
            <h1> Verify OTP</h1>

           <form method="post" action="veri_otp.php">
                <div>Your code was send to you via email<strong><?php echo htmlspecialchars($_GET['email'] ?? ''); ?></strong></div>
                <div class="input">
                    <input type="text" name="otp" maxlength="6" required placeholder="Enter OTP">
                </div>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>   
</div>
