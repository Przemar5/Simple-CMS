<?php require_once(VIEW_ROOT . '/templates/header.php'); ?>
   
    <?php if(isset($_SESSION['last_action']['success'])): ?>
        <p style="color:green;">
            <?php echo $_SESSION['last_action']['success']; unset($_SESSION['last_action']['success']); ?>
        </p>
    <?php elseif(isset($_SESSION['last_action']['error'])): ?>
        <p style="color:red;">
            <?php echo $_SESSION['last_action']['error']; unset($_SESSION['last_action']['error']); ?>
        </p>
    <?php endif; ?>
    
    <?php if(empty($data)): ?>
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
			<?php foreach($data as $page): ?>
				<tr>
					<td>
						<?php echo $page['label']; ?>
					</td>
					<td>
						<?php echo $page['title']; ?>
					</td>
					<td>
						<a href="<?php echo BASE_URL; ?>/page.php?page=<?php echo $page['slug']; ?>">
							<?php echo $page['slug']; ?>
						</a>
					</td>
					<td>
						<a href="<?php echo URL['EDIT_PAGE'] . '/' . $page['slug']; ?>">
							Edit
						</a>
					</td>
					<td>
						<form action="<?php echo URL['DELETE_PAGE']; ?>" method="post">
							<input type="hidden" name="id" value="<?php echo $page['id']; ?>">
							<input type="submit" value="Delete">
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
    <?php endif; ?>
    
    <a href="<?php echo URL['ADD_PAGE']; ?>">Add new page</a>
    
<?php require_once(VIEW_ROOT . '/templates/footer.php'); ?>