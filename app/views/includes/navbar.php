    	
    	<nav role="navigation">
    		<div class="navbar">
    			<a href="<?php echo HOME; ?>" class="navbar__brand">
    				<?php echo APP_NAME; ?>
    			</a>
    			
				<ul class="navbar__list">
   				
    				<?php if (count($navbarItems)): ?>
						<?php foreach($navbarItems as $item): ?>
						<li class="navbar__item navbar__item-submenu">
						
							<?php if (!empty($item['children'])): ?>
								<label for="submenu-<?php echo $item['id']; ?>" class="navbar__submenu-label">
									<?php echo $item['label']; ?>
								</label>
								
								<input type="checkbox" id="submenu-<?php echo $item['id']; ?>" class="navbar__submenu-toggler">
								
								<ul class="navbar__submenu-list">
								<?php if (count($item['children'])): ?>
									<?php foreach ($item['children'] as $submenuItem): ?>
									
										<?php if (isset($submenuItem['children'])): ?>
										<li>
											<label for="submenu-<?php echo $item['id']; ?>" class="navbar__submenu-label">
												<?php echo $submenuItem['label']; ?>
											</label>

											<input type="checkbox" id="submenu-<?php echo $item['id']; ?>" class="navbar__submenu-toggler">

											<ul class="navbar__submenu-list">
												<?php?>
											</ul>
										</li>
										<?php else: ?>
										<li class="navbar__submenu-item">
											<a href="<?php echo $submenuItem['slug']; ?>">
												<?php echo $submenuItem['label']; ?>
											</a>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								</ul>
							<?php else: ?>
								<a href="<?php echo $item['slug']; ?>">
									<?php echo $item['label']; ?>
								</a>
							<?php endif; ?>
							
						</li>
						<?php endforeach; ?>
    				<?php endif; ?>
    				
				</ul>
    			
    			<form action="<?php echo SEARCH; ?>" class="navbar__search">
    				<input type="search" name="phrase" class="navbar__search-text" placeholder="Search for...">
    				<input type="submit" class="navbar__search-btn" value="Search">
    			</form>
    		</div>
    	</nav>
    	
<style>
.wrapper {
	/*background-color: red;*/
}

/* Navbar */
.navbar {
	display: flex;
    justify-content: flex-start;
    align-items: center;
	position: fixed;
	top: 0;
	width: 100%;
    height: 4.5rem;
    padding: 0 1.5rem;
    background-color: #333;
    color: #e6e6e6;
    font-size: 2rem;
}

.navbar a {
    color: inherit;
    text-decoration: none;
}

.navbar a:visited {
    color: inherit;
}

.navbar a:focus,
.navbar a:hover {
    color: #fff;
}

.navbar input {
    font-size: inherit;
    font-family: inherit;
}
	
.navbar > ul {
    display: flex;
    justify-content: space-between;
    align-self: center;
}

.navbar__item {
    padding-left: 2.5rem;
    padding-right: 2.5rem;
    display: flex;
    justify-content: center;
}

.navbar__item-submenu {
    position: relative;
	top: 0;
}

.navbar__submenu-label {
	cursor: pointer;
}

.navbar__submenu-label:focus,
.navbar__submenu-label:hover {
    color: #fff;
}
	
.navbar__submenu-toggler {
	display: none;
}

.navbar__submenu-list {
	display: none;
}
	
.navbar__submenu-item {
	display: blook;
	width: 100%;
	height: 5rem;
	padding-top: 1rem;
	padding-bottom: 1rem;
}
	
.navbar__submenu-toggler:checked + .navbar__submenu-list {
	display: block;
	width: 100%;
	height: auto;
	background-color: #333;
	position: absolute;
	top: 100%;
	left: 0px;
}

.navbar__search {
    margin-left: auto;
}



</style>