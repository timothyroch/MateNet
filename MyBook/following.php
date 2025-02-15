<div style="min-height: 400px;padding-right: 0px;width:;background-color: white;text-align:center;">
    <div style="padding:20px;">
        <?php
        $image_class = new Image();
        $post_class = new Post();
        $user_class = new User();
        $following = $user_class->get_following($user_data['userid'], "user");

        if (is_array($following) && !empty($following)) {
            foreach ($following as $follower) {
                $friend_row = $user_class->get_user($follower['userid']);
                include("user.php");
            }
        } else {
            echo "This user isn't following anyone!";
        }
        ?>
    </div>
</div>
