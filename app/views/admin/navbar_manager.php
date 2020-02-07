<?php require_once(HEADER); ?>
   
	<?php include_once(ACTION_FEEDBACK); ?>

    <h2>Manage navbar</h2>

   	<h3>Add new submenu</h3>
   
    <form action="<?php echo $data['url_submenu_add']; ?>" method="post" autocomplete="off">
        <label for="label">
            Label
            <input type="text" name="label" id="label" value="">
        </label>
       	<?php display_error('label'); ?>

        <br>

        <label for="parent_id">
            In submenu
        	<select name="parent_id" id="parent_id">
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

       
       	<!-- Use AJAX for displaying choosen above submenu item indexes/names -->
        <label for="item_index">
            Item index
        	<select name="item_index" id="item_index">
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
    
    <?php if (count($navbarItems)): ?>
    	<table>
    		<thead>
    			<th>Id</th>
    			<th>Label</th>
    			<th>Type</th>
    			<th>Parent</th>
    			<th>Index</th>
    			<th></th>
    			<th></th>
    		</thead>
    		<tbody>
			<?php foreach ($navbarItems as $item): ?>
				<tr>
					<td>
						<?php echo $item['id']; ?>
					</td>
					<td>
						<?php echo $item['label']; ?>
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
							<input type="hidden" name="page_id" value="<?php echo $item['page_id']; ?>">
							<input type="hidden" name="submenu_id" value="<?php echo $item['submenu_id']; ?>">
							<input type="hidden" name="parent_id" value="<?php echo $item['parent_id']; ?>">
							<input type="hidden" name="item_index" value="<?php echo $item['item_index']; ?>">
							<input class="btn btn-sm btn-danger" type="submit" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
    		</tbody>
    	</table>
    <?php endif; ?>
    
<?php require_once(FOOTER); ?>
