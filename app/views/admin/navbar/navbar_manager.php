<?php require_once(HEADER); ?>

	<?php include_once(ACTION_FEEDBACK); ?>

    <h2>Manage navbar</h2>

   	<h3>Add new submenu</h3>
   
    <form action="<?php echo $data['url_submenu_add']; ?>" method="post" autocomplete="off">
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

        <label for="parent_id">
            In submenu
        	<select name="parent_id" id="parent_id">
        		<option value="0">
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

       
       	<!-- Use AJAX for displaying choosen above submenu item indexes/names -->
        <!--
        <label for="item_index">
            Item index
        	<select name="item_index" id="item_index">
        		<option value="0">
        			None
        		</option>
        		<?php foreach ($submenus as $submenu): ?>
        			<option value="<?php echo $submenu['id']; ?>">
        				<?php echo $submenu['label']; ?>
        			</option>
        		<?php endforeach; ?>
        	</select>
        </label>
		-->
       	
       	<label for="item_index">
       		Item index
       		<input type="number" name="item_index" id="item_index" value="0">
       	</label>
       	
        <br>
        
        <input type="submit" value="Add">
    </form>
    
    <?php if (!empty($navigationItems) && count($navigationItems)): ?>
    	<table>
    		<thead>
    			<th>Id</th>
    			<th>Label</th>
    			<th>Slug</th>
    			<th>Type</th>
    			<th>Parent</th>
    			<th>Index</th>
    			<th></th>
    			<th></th>
    		</thead>
    		<tbody>
			<?php foreach ($navigationItems as $item): ?>
				<tr>
					<td>
						<?php echo $item['id']; ?>
					</td>
					<td>
						<?php echo $item['label']; ?>
					</td>
					<td>
						<?php echo $item['slug']; ?>
					</td>
					<td>
						<?php echo $item['type']; ?>
					</td>
					<td>
						<?php echo $item['parent']; ?>
					</td>
					<td>
						<?php echo $item['item_index']; ?>
					</td>
					<td>
						<a href="<?php echo $item['url_edit']; ?>" class="btn btn-sm btn-primary">
							Edit
						</a>
						
						<form action="<?php echo $item['url_delete']; ?>" method="post">
							<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
							
							<input class="btn btn-sm btn-danger" type="submit" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
    		</tbody>
    	</table>
    <?php endif; ?>
    
<?php require_once(FOOTER); ?>
