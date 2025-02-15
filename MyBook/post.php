<div id="post" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); margin-bottom: 20px; padding: 20px; display: flex; gap: 10px;">
    <!-- User Image -->
    <div style="flex-shrink: 0; ">
        <?php
            $image = "images/user_male.jpg";
            if($row_user['gender'] == "Female") {
                $image = "images/user_female.jpg";
            }
            if(file_exists($row_user['profile_image'])) {
                $image_class = new Image();
                $image = $image_class->get_thumb_profile($row_user['profile_image']);
            }
        ?>
        <img src="<?php echo $image ?>" style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover; margin-top:0%;">
        </div>


    <!-- Post Content -->
    <div style="flex-grow: 1;">
        <div style="font-weight: bold; color: #405d9b; font-size: 16px; display: inline-block;">
            <?php
                echo "<a href='profile.php?id=$row[userid]' style='text-decoration: none; color: #405d9b;'>";
                echo htmlspecialchars($row_user['first_name']) . " " . htmlspecialchars($row_user['last_name']);
                echo "</a>";
                if($row['is_profile_image']) {
                    $pronoun = ($row_user['gender'] == "Female") ? "her" : "his";
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun profile image</span>";
                }
                if($row['is_cover_image']) {
                    $pronoun = ($row_user['gender'] == "Female") ? "her" : "his";
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun cover image</span>";
                }
            ?>
        </div>

        <!-- Post Text -->
        <div style="margin-top: 10px; font-size: 14px; line-height: 1.6; color: #333;">
            <?php echo htmlspecialchars($row['post']) ?>
        </div>


        <!-- Post Image -->
        <?php if (file_exists($row['image'])): ?>
            <div style="margin-top: 10px;">
                <?php 
                    $post_image = $image_class->get_thumb_post($row['image']); 
                    echo "<img src='$post_image' style='width: 100%; border-radius: 10px; max-height: 500px; object-fit: cover;' />";
                ?>
            </div>
        <?php endif; ?>

        <!-- Post Actions -->
        <div style="margin-top: 10px; font-size: 14px; color: #555;">
            <?php 
                $likes = ($row['likes'] > 0) ? "({$row['likes']})" : "" ;
            ?>
            <a href="like.php?type=post&id=<?php echo $row['postid']; ?>" style="text-decoration: none; color: #405d9b;">Like<?php echo $likes ?></a> · 
            <?php
                $comments = ($row['comments'] > 0) ? "({$row['comments']})" : "";
            ?>
            <a href="single_post.php?id=<?php echo $row['postid'] ?>" style="text-decoration: none; color: #405d9b;">Comment<?php echo $comments ?></a> · 
            <span><?php echo Time::get_time($row['date']) ?></span>

            <?php 
                if($row['has_image']) {
                    echo " · <a href='image_view.php?id=$row[postid]' style='text-decoration: none; color: #405d9b;'>View Full Image</a>";
                }
            ?>
        </div>

        <!-- Post Admin Actions -->
        <div style="color: #999; float:right; margin-top: 5px;">
            <?php
                $post = new Post();
                if($post->i_own_post($row['postid'], $_SESSION['mybook_userid'])) {
                    echo "<a href='edit.php?id=$row[postid]' style='text-decoration: none; color: #999;'>Edit</a> · 
                          <a href='delete.php?id=$row[postid]' style='text-decoration: none; color: #999;'>Delete</a>";
                }
            ?>
        </div>

        <!-- Liked by Users -->
        <?php
                $i_liked = false;
            if($row['likes'] > 0) {
                echo "<div style='margin-top: 10px; color: #405d9b; font-size: 14px;'>";
                echo "<a href='likes.php?type=post&id=$row[postid]' style='text-decoration: none; color: #405d9b;'>";
                if($row['likes'] == 1) {
                    if($i_liked) {
                        echo "You liked this post";
                    } else {
                        echo "1 person liked this post";
                    }
                } else {
                    if($i_liked) {
                        echo "You and " . ($row['likes'] - 1) . " others liked this post";
                    } else {
                        echo "{$row['likes']} people liked this post";
                    }
                }
                echo "</a></div>";
            }
        ?>
    </div>
</div>
