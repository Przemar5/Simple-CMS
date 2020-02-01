<?php require_once(HEADER); ?>
   
    <?php if(isset($_SESSION['last_action']['success'])): ?>
        <p style="color:green;">
            <?php echo $_SESSION['last_action']['success']; unset($_SESSION['last_action']['success']); ?>
        </p>
    <?php elseif(isset($_SESSION['last_action']['error'])): ?>
        <p style="color:red;">
            <?php echo $_SESSION['last_action']['error']; unset($_SESSION['last_action']['error']); ?>
        </p>
    <?php endif; ?>
    
    <?php if(empty($data['pages'])): ?>
        <p>No pages at the moment.</p>
    <?php else: ?>  
		<table style="min-width: 400px">
			<thead>
				<tr>
					<th>Label</th>
					<th>Title</th>
					<th>Slug</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($data['pages'] as $page): ?>
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
						<a type="button" href="<?php echo $page['url_edit']; ?>">
							Edit
						</a>
					</td>
					<td>
						<form action="<?php echo $page['url_delete']; ?>" method="post">
							<input type="hidden" name="id" value="<?php echo $page['id']; ?>">
							<input type="submit" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
    <?php endif; ?>
    
    <a href="<?php echo $data['url_add']; ?>">Add new page</a>
    
<?php require_once(FOOTER); ?>