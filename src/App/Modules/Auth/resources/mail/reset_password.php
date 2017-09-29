<!DOCTYPE html>
<html>
    Your reset token  is <strong><?= $token ?></strong><br />
    Or you can click the link below if you open this email from your Android / iOS phone:</br >
    <a href="android-app://com.bi.starterkit/https/dev.badr.co.id/freedom/auth/reset_password?token=<?= $token ?>&email=<?= $email ?>">Android Link</a><br />
    <a href="https://dev.badr.co.id/freedom/auth/reset_password?token=<?= $token ?>&email=<?= $email ?>">Android Link 2</a><br />
</html>
