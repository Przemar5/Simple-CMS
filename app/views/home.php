<?php require_once(VIEW_ROOT . '/templates/header.php'); ?>
   
    <h1>Home</h1>
    
    <?php if(empty($data)): ?>
    	<p>Sorry, no pages at the moment.</p>
    <?php else: ?>
    <ul>
        <?php foreach($data as $page): ?>
        <li>
            <a href="<?php echo BASE_URL; ?>/page.php?page=<?php echo $page['slug']; ?>">
                <?php echo $page['label']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
<?php require_once(VIEW_ROOT . '/templates/footer.php'); ?>