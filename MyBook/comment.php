<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Display</title>
    <style>
        /* General styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Post container styling */
        .post-container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .post-container:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        /* Header styling */
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 12px;
            border: 2px solid #405d9b;
        }

        .post-user-info {
            display: flex;
            flex-direction: column;
        }

        .post-user-name {
            font-size: 18px;
            font-weight: bold;
            color: #405d9b;
            text-decoration: none;
        }

        .post-user-name:hover {
            text-decoration: underline;
        }

        .post-date {
            font-size: 14px;
            color: #aaa;
            margin-top: 4px;
        }

        /* Content styling */
        .post-content {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 12px;
        }

        .post-image {
            width: 100%;
            border-radius: 8px;
            margin-top: 12px;
        }

        /* Actions styling */
        .post-actions {
            display: flex;
            gap: 12px;
            font-size: 14px;
        }

        .post-action {
            color: #405d9b;
            text-decoration: none;
            font-weight: bold;
        }

        .post-action:hover {
            text-decoration: underline;
        }

        /* Likes styling */
        .post-likes {
            margin-top: 16px;
            font-size: 14px;
            color: #666;
        }

        .post-likes-info {
            text-decoration: none;
            color: #405d9b;
            font-weight: bold;
        }

        .post-likes-info:hover {
            text-decoration: underline;
        }

        
    </style>
</head>
<body>

<div id="post" class="post-container">
    <div class="post-header">
        <?php
        $image = "images/user_male.jpg";
        $DB = new Database();
        $commenter_id = $comment['userid'];
        $query = "SELECT * FROM users WHERE userid = '$commenter_id' LIMIT 1";
        $result = $DB->read($query);
        $commenter = $result[0];

        if ($commenter['gender'] == "Female") {
            $image = "images/user_female.jpg";
        }

        if (file_exists($commenter['profile_image'])) {
            $image_class = new Image();
            $image = $image_class->get_thumb_profile($commenter['profile_image']);
        }
        ?>
        <img src="<?php echo $image ?>" class="profile-pic">
        <div class="post-user-info">
            <a href="profile.php?id=<?php echo $comment['userid']; ?>" class="post-user-name">
                <?php echo htmlspecialchars($commenter['first_name']) . " " . htmlspecialchars($commenter['last_name']); ?>
            </a>
            <span class="post-date"><?php echo Time::get_time($comment['date']); ?></span>
        </div>
    </div>
    
    <div class="post-content">
        <p><?php echo htmlspecialchars($comment['post']); ?></p>
        <?php
        if (file_exists($comment['image'])) {
            $post_image = $image_class->get_thumb_post($comment['image']);
            echo "<img src='$post_image' class='post-image'>";
        }
        ?>
    </div>
    
    <div class="post-actions">
        <?php 
        $likes = ($comment['likes'] > 0) ? "({$comment['likes']})" : "";
        ?>
        <a href="like.php?type=post&id=<?php echo $comment['postid']; ?>" class="post-action">Like<?php echo $likes; ?></a>
        
        <?php
        $comments = ($comment['comments'] > 0) ? "({$comment['comments']})" : "";
        ?>
        <a href="single_post.php?id=<?php echo $comment['postid']; ?>" class="post-action">Reply<?php echo $comments; ?></a>
        
        <?php
        if ($comment['has_image']) {
            echo "<a href='image_view.php?id=$comment[postid]' class='post-action'>View Full Image</a>";
        }
        ?>

        <?php
        $post = new Post();
        if ($post->i_own_post($comment['postid'], $_SESSION['mybook_userid'])) {
            echo "<a href='edit.php?id=$comment[postid]' class='post-action'>Edit</a>";
            echo "<a href='delete.php?id=$comment[postid]' class='post-action'>Delete</a>";
        }
        ?>
    </div>
    
    <div class="post-likes">
        <?php
        $i_liked = false;
        if (isset($_SESSION['mybook_userid'])) {
            $DB = new Database();
            $sql = "SELECT likes FROM likes WHERE type='post' AND contentid = '$comment[postid]' LIMIT 1";
            $result = $DB->read($sql);
            if (is_array($result)) {
                $likes = json_decode($result[0]['likes'], true);
                $user_ids = array_column($likes, "userid");
                if (in_array($_SESSION['mybook_userid'], $user_ids)) {
                    $i_liked = true;
                }
            }
        }

        if ($comment['likes'] > 0) {
            echo "<a href='likes.php?type=post&id=$comment[postid]' class='post-likes-info'>";
            if ($comment['likes'] == 1) {
                echo $i_liked ? "You liked this comment" : "1 person liked this comment";
            } else {
                if ($i_liked) {
                    echo "You and " . ($comment['likes'] - 1) . " others liked this comment";
                } else {
                    echo $comment['likes'] . " people liked this comment";
                }
            }
            echo "</a>";
        }
        ?>
    </div>
</div>

</body>
</html>
