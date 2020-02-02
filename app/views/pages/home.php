<?php require_once(HEADER); ?>
   
    <?php include_once(ACTION_FEEDBACK); ?>
   	
    <h1>Home</h1>
    
    <?php if(empty($data)): ?>
    	<p>Sorry, no pages at the moment.</p>
    <?php else: ?>
    <ul>
        <?php foreach($data['pages'] as $page): ?>
        <li>
            <a href="<?php echo $page['url_show']; ?>">
                <?php echo $page['label']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
<?php require_once(FOOTER); ?>