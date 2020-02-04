    	<!--
    	<nav role="navigation">
    		<div class="navbar">
    			<a href="<?php //echo HOME; ?>" class="navbar__brand">
    				<?php //echo APP_NAME; ?>
    			</a>
    			
    			<ul class="navbar__list">
    				<?php //foreach(NAVBAR_ITEMS as $name => $url): ?>
    				<li class="navbar__item">
    					<a href="<?php //echo $url; ?>" class="navbar__link">
    						<?php //echo $name; ?>
    					</a>
    				</li>
    				<?php //endforeach; ?>
    			</ul>
    			<form action="<?php //echo SEARCH; ?>" class="navbar__search">
    				<input type="search" name="phrase" class="navbar__search-text" placeholder="Search for...">
    				<input type="submit" class="navbar__search-btn" value="Search">
    			</form>
    		</div>
    	</nav>
   	    -->
    	<nav role="navigation">
    		<div class="navbar">
    			<a href="<?php echo HOME; ?>" class="navbar__brand">
    				<?php echo APP_NAME; ?>
    			</a>
    			
				<ul class="navbar__list">
    				<?php if (count($navbarItems)): ?>
						<?php foreach($navbarItems as $navItem): ?>
						<li class="navbar__item">
							<a href="<?php echo $navItem['slug']; ?>" class="navbar__link">
								<?php echo $navItem['label']; ?>
							</a>
						</li>
						<?php endforeach; ?>
    				<?php endif; ?>
    				
    				<?php if (count($navbarSubmenus)): ?>
						<?php foreach($navbarSubmenus as $submenu): ?>
						<li class="navbar__item navbar__item-submenu">
							<label for="submenu-<?php echo $submenu['id']; ?>" class="navbar__submenu-label">
								<?php echo $submenu['label']; ?>
							</label>
							<input type="checkbox" id="submenu-<?php echo $submenu['id']; ?>" class="navbar__submenu-toggler">
							<ul class="navbar__submenu-list">
								<li class="navbar__submenu-item">
									Item 1
								</li>
								<li class="navbar__submenu-item">
									Item 2
								</li>
							</ul>
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
    margin-left: 5rem;
    display: flex;
    justify-content: center;
}

.navbar__item-submenu {
    position: relative;
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
	
.navbar__submenu-toggler:checked + .navbar__submenu-list {
	display: block;
	width: 100%;
	height: 100px;
	background-color: red;
	position: absolute;
	top: 100%;
	left: 0px;
}

.navbar__search {
    margin-left: auto;
}



</style>