<?php require_once(HEADER); ?>

    <h2>Edit page</h2>

    <form action="<?php echo $data['url_update']; ?>" method="post" autocomplete="off">
        <label for="title">
            Title
            <input type="text" name="title" id="title" value="<?php echo $page['title']; ?>">
        </label>
       	<?php display_error('title'); ?>

        <br>

        <label for="label">
            Label
            <input type="text" name="label" id="label" value="<?php echo $page['label']; ?>">
        </label>
       	<?php display_error('label'); ?>

        <br>

        <label for="slug">
            Slug
            <input type="text" name="slug" id="slug" value="<?php echo $page['slug']; ?>">
        </label>
       	<?php display_error('slug'); ?>

        <br>

        <label for="navbar_place">
            Display on navbar
        	<select name="navbar_place" id="navbar_place">
        		<option value="">
        			None
        		</option>
        		<option value="0">
        			Main
        		</option>
        		<?php foreach ($navbarPages as $navItem): ?>
        			<option value="<?php echo $navItem['id']; ?>">
        				<?php echo $navItem['label']; ?>
        			</option>
        		<?php endforeach; ?>
        	</select>
        </label>
       	<?php  ?>

        <br>

        <label for="body">
            Body
            <textarea name="body" id="body"><?php echo $page['body']; ?></textarea>
        </label>
       	<?php display_error('body'); ?>

        <br>
        
        <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
       	
        <input type="submit" value="Edit">
    </form>
    
<?php require_once(FOOTER); ?>
