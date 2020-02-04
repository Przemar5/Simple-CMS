<?php require_once(HEADER); ?>

    <h2>Manage navbar</h2>

   	<h3>Add new submenu</h3>
   
    <form action="<?php echo $data['url_submenu_add']; ?>" method="post" autocomplete="off">
        <label for="label">
            Label
            <input type="text" name="label" id="label" value="">
        </label>
       	<?php display_error('label'); ?>

        <br>

        <label for="submenu_id">
            In submenu
        	<select name="submenu_id" id="submenu_id">
        		<option value="">
        			None
        		</option>
        		<?php foreach ($submenus as $submenu): ?>
        			<option value="<?php echo $submenu['id']; ?>">
        				<?php echo $submenu['label']; ?>
        			</option>
        		<?php endforeach; ?>
        	</select>
        </label>

        <br>
        
        <input type="submit" value="Add">
    </form>
    
<?php require_once(FOOTER); ?>
