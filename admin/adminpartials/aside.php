<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     
<div class="user-panel">
        
      </div>
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><?php echo $_SESSION['email'] ?></li>
        <li class="active treeview">
     <li>
          <a href="adminindex.php">
            <i class="fa fa-th"></i> <span>Aceuill</span>
            
          </a>
        </li>
        <li>
          <a href="categories.php">
            <i class="fa fa-th"></i> <span>Catégorie</span>
            
          </a>
        </li>
         <li>
          <a href="productsshow.php">
            <i class="fa fa-th"></i> <span>Vinyles</span>
            
          </a>
        </li>
        <li>
          <a href="orders.php">
            <i class="fa fa-th"></i> <span>Commandes</span>
            
          </a>
        </li>
        
        
        <li>
          <a href="adminpartials/logout.php">
            <i class="fa fa-th"></i> <span>Log Out</span>
            
          </a>
        </li>
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>