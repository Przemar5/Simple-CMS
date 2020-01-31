<?php require_once(VIEW_ROOT . '/templates/header.php'); ?>

    <h2>Add page</h2>

    <form action="<?php echo BASE_URL; ?>/admin/store.php" method="post" autocomplete="off">
        <label for="title">
            Title
            <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>">
        </label>
       	<?php displayError($errors['title']); ?>

        <br>

        <label for="label">
            Label
            <input type="text" name="label" id="label" value="<?php echo $data['label']; ?>">
        </label>
       	<?php displayError($errors['label']); ?>

        <br>

        <label for="slug">
            Slug
            <input type="text" name="slug" id="slug" value="<?php echo $data['slug']; ?>">
        </label>
       	<?php displayError($errors['slug']); ?>

        <br>

        <label for="body">
            Body
            <textarea name="body" id="body"><?php echo $data['body']; ?></textarea>
        </label>
       	<?php displayError($errors['body']); ?>

        <br>
        
        <input type="submit" value="Add">
    </form>
    
<?php require_once(VIEW_ROOT . '/templates/footer.php'); ?>