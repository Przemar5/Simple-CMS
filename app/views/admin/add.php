<?php require_once(HEADER); ?>

    <h2>Add page</h2>

    <form action="<?php echo $data['url_store']; ?>" method="post" autocomplete="off">
        <label for="title">
            Title
            <input type="text" name="title" id="title" value="<?php submitted_data('title'); ?>">
        </label>
       	<?php display_error('title'); ?>

        <br>

        <label for="label">
            Label
            <input type="text" name="label" id="label" value="<?php submitted_data('label'); ?>">
        </label>
       	<?php display_error('label'); ?>

        <br>

        <label for="slug">
            Slug
            <input type="text" name="slug" id="slug" value="<?php submitted_data('slug'); ?>">
        </label>
       	<?php display_error('slug'); ?>

        <br>

        <label for="body">
            Body
            <textarea name="body" id="body"><?php submitted_data('body'); ?></textarea>
        </label>
       	<?php display_error('body'); ?>

        <br>
        
        <input type="submit" value="Add">
    </form>
    
<?php require_once(FOOTER); ?>