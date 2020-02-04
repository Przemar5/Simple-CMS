<?php require_once(HEADER); ?>
   
	<?php include_once(ACTION_FEEDBACK); ?>
    
    <?php if(empty($pages )): ?>
        <p>No pages at the moment.</p>
    <?php else: ?>  
		<table class="table table-striped" style="min-width: 400px">
			<thead>
				<tr>
					<th>Label</th>
					<th>Title</th>
					<th>Slug</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($pages as $page): ?>
				<tr>
					<td>
						<?php echo $page['label']; ?>
					</td>
					<td>
						<?php echo $page['title']; ?>
					</td>
					<td>
						<a href="<?php echo $page['url_show']; ?>">
							<?php echo $page['slug']; ?>
						</a>
					</td>
					<td>
						<a href="<?php echo $page['url_edit']; ?>" class="btn btn-sm btn-primary">
							Edit
						</a>
						
						<form action="<?php echo $page['url_delete']; ?>" method="post">
							<input type="hidden" name="id" value="<?php echo $page['id']; ?>">
							<input class="btn btn-sm btn-danger" type="submit" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
    <?php endif; ?>
    
    <a href="<?php echo $data['url_add']; ?>">Add new page</a>
    <a href="<?php echo NAVBAR_MANAGER; ?>">Manage navbar</a>
    
<?php require_once(FOOTER); ?>