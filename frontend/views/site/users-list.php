<h1 class="main-admin-text">ADMIN PANEL</h1>

<div class="center-user-list">
    <div class="one-panel-user row">
            <div class="col-xs-1">Avatar</div>
            <div class="col-xs-1">Username</div>
            <div class="col-xs-2">Full name</div>
            <div class="col-xs-1">Age</div>
            <div class="col-xs-2">Gender</div>
            <div class="col-xs-1">Account</div>
            <div class="col-xs-2">Prime/Usual</div>
            <div class="col-xs-2">Ban/Unban</div>
        </div>
    <?php foreach($profiles as $profile) { ?>
        <div class="one-panel-user row">
            <div class="col-xs-1"><img src="uploads/profile_avatar/<?= $profile->avatar ?>" alt=""></div>
            <div class="col-xs-1"><?= $profile->user->username ?></div>
            <div class="col-xs-2"><?= $profile->first_name ?> <?= $profile->second_name ?></div>
            <div class="col-xs-1"><?php echo date('Y') - date("Y", $profile->birthday) ?> y.</div>
            <div class="col-xs-2"><?php if($profile->gender) echo 'Man'; else echo 'Woman'; ?></div>
            <div class="col-xs-1"><span style="font-weight: 500;"><?php if($profile->ban) echo 'Banned'; else if($profile->prime) echo 'PRIME'; else echo 'Usual'; ?></span></div>
            <div class="col-xs-2"><span class="account-status-change" id_user="<?= $profile->id ?>">Act</span></div>
            <div class="col-xs-2"><span class="account-ban-change" id_user="<?= $profile->id ?>">Act</span></div>
        </div>
    <?php } ?>
</div>
