<?php require_once(HEADER); ?>

    <h2>Edit page</h2>

    <form action="<?php echo $data['url_update']; ?>" method="post" autocomplete="off">
        <label for="title">
            Title
            <input type="text" name="title" id="title" value="<?php echo $data['page']['title']; ?>">
        </label>
       	<?php displayError($errors['title']); ?>

        <br>

        <label for="label">
            Label
            <input type="text" name="label" id="label" value="<?php echo $data['page']['label']; ?>">
        </label>
       	<?php displayError($errors['label']); ?>

        <br>

        <label for="slug">
            Slug
            <input type="text" name="slug" id="slug" value="<?php echo $data['page']['slug']; ?>">
        </label>
       	<?php displayError($errors['slug']); ?>

        <br>

        <label for="body">
            Body
            <textarea name="body" id="body"><?php echo $data['page']['body']; ?></textarea>
        </label>
       	<?php displayError($errors['body']); ?>

        <br>
        
        <input type="hidden" name="id" value="<?php echo $data['page']['id']; ?>">
       	
        <input type="submit" value="Edit">
    </form>
    
<?php require_once(FOOTER); ?>
