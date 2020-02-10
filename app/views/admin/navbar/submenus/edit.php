<?php require_once(HEADER); ?>

    <h2>Edit submenu</h2>
   
    <form action="<?php echo $data['url_update']; ?>" method="post" autocomplete="off">
        <label for="label">
            Label
            <input type="text" name="label" id="label" value="<?php echo $submenu['label']; ?>">
        </label>
       	<?php display_error('label'); ?>

        <br>

        <label for="slug">
            Slug
            <input type="text" name="slug" id="slug" value="<?php echo $submenu['slug']; ?>">
        </label>
       	<?php display_error('slug'); ?>
        <br>

        <label for="parent_id">
            In submenu
        	<select name="parent_id" id="parent_id">
        		<option value="0">
        			None
        		</option>
        		<?php foreach ($submenus as $sub): ?>
        			<?php if ($sub['id'] !== $submenu['id']): ?>
        			
        			<option value="<?php echo $sub['id']; ?>" <?php echo ($submenu['parent_id'] == $sub['id']) ? 'selected' : ''; ?>>
        				<?php echo $sub['label']; ?>
        			</option>
        			
        			<?php endif; ?>
        		<?php endforeach; ?>
        	</select>
        </label>

        <br>

       	<!-- Use AJAX for displaying choosen above submenu item indexes/names -->
        <!--
        <label for="item_index">
            Item index
        	<select name="item_index" id="item_index">
        		<option value="0">
        			None
        		</option>
        		<?php foreach ($submenus as $sub): ?>
        			<option value="<?php echo $sub['id']; ?>">
        				<?php echo $sub['label']; ?>
        			</option>
        		<?php endforeach; ?>
        	</select>
        </label>
		-->
       
       	<label for="item_index">
       		Item index
       		<input type="number" name="item_index" id="item_index" value="<?php echo $submenu['item_index']; ?>">
       	</label>
       	
        <br>
       	
       	<input type="hidden" name="submenu_id" id="submenu_id" value="<?php echo $submenu['submenu_id']; ?>">
        
        <input type="submit" value="Edit">
    </form>
    
<?php require_once(FOOTER); ?>
