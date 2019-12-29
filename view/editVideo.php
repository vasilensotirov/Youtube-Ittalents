<?php
require_once "header.php";
require_once "navigation.php";
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
if (!isset($video)){
    header("Location:main.php");
}
?>
<!--<button><a href="index.php">Home</a></button>-->
<form action="index.php?target=video&action=edit" method="post" enctype="multipart/form-data">
    <table>
        <input type="hidden" name="id" value="<?= $video["id"]; ?>"
        <tr>
            <td><label for="title"><b>Title:</b></label></td>
            <td><input type="text" id="title" name="title" value="<?= $video["title"] ?>" required></td>
        </tr>
        <tr>
            <td><label for="description"><b>Description:</b></label></td>
            <td><textarea id="description" name="description" required><?= $video["description"]; ?></textarea></td>
        </tr>
        <input type="hidden" name="owner_id" value="<?= $_SESSION["logged_user"]["id"]; ?>"
        <tr>
            <td><label for="category_id"><b>Category:</b></label></td>
            <td><select id="category_id" name="category_id" required>
                    <option value="1" <?php if ($video["category_id"] == 1) { echo "selected"; } ?>>Film & Animation</option>
                    <option value="2"<?php if ($video["category_id"] == 2) {echo "selected"; } ?>>Autos & Vehicles</option>
                    <option value="3"<?php if ($video["category_id"] == 3) {echo "selected"; } ?>>Music</option>
                    <option value="4"<?php if ($video["category_id"] == 4) {echo "selected"; } ?>>Pets & Animals</option>
                    <option value="5"<?php if ($video["category_id"] == 5) {echo "selected"; } ?>>Sports</option>
                    <option value="6"<?php if ($video["category_id"] == 6) {echo "selected"; } ?>>Travel & Events</option>
                    <option value="7"<?php if ($video["category_id"] == 7) {echo "selected"; } ?>>Gaming</option>
                    <option value="8"<?php if ($video["category_id"] == 8) {echo "selected"; } ?>>People & Blogs</option>
                    <option value="9"<?php if ($video["category_id"] == 9) {echo "selected"; } ?>>Entertainment</option>
                    <option value="10"<?php if ($video["category_id"] == 10) {echo "selected"; } ?>>News & Politics</option>
                    <option value="11"<?php if ($video["category_id"] == 11) {echo "selected"; } ?>>Education</option>
                    <option value="12"<?php if ($video["category_id"] == 12) {echo "selected"; } ?>>Science & Technology</option>
                </select></td>
        </tr>
        <tr>
            <td>Current thumbnail:</td>
            <td><img width="200px" src="<?= $video["thumbnail_url"] ?>" </td>
        </tr>
        <tr>
            <td><label for="thumbnail"><b>Add new:</b></label></td>
            <td><input type="file" id="thumbnail" name="thumbnail"></td>
            <input type="hidden" name="thumbnail_url" value="<?= $video["thumbnail_url"] ?>">
        </tr>
        <tr>
            <td><input type="submit" name="edit" value="Edit"></td>
        </tr>
    </table>
</form>
</body>
</html>